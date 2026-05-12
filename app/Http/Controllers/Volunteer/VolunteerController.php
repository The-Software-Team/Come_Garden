<?php

namespace App\Http\Controllers\Volunteer;

use App\Contracts\Volunteer\VolunteerServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Volunteer\FundProposal;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{DB, View as ViewFacade, Str};
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function __construct(private readonly VolunteerServiceInterface $volunteerService)
    {
        ViewFacade::composer('layouts.volunteer', function ($view) {
            $user = auth()->user();
            $ledger = $this->volunteerService->getServiceLedger($user->id);
            $view->with('totalHours', $ledger->total_hours ?? 0);
        });
    }

    public function index(): View
    {
        $user = auth()->user();
        $isAdmin = $user->roles->contains('name', 'admin');
        
        if ($isAdmin) {
            return $this->adminDashboard();
        }
        
        return $this->dashboard();
    }

    public function userDashboard(): View
    {
        $user = auth()->user();
        $ledger = $this->volunteerService->getServiceLedger($user->id);
        $totalHours = $ledger->total_hours ?? 0;
        $requiredHours = $ledger->required_hours ?? 10;

        // My shifts
        $myAssignments = \App\Models\Volunteer\Assignment::with(['shift', 'task'])
            ->where('member_id', $user->id)
            ->whereIn('status', ['assigned', 'completed'])
            ->get()->map(function($a) {
                return [
                    'id' => $a->id,
                    'date' => $a->shift?->start_date?->format('d M'),
                    'task' => $a->task?->name ?? 'Assigned task',
                    'role' => $a->role,
                    'status' => $a->status,
                    'hours' => $a->hours,
                ];
            });

        // My service hours
        $completedCount = \App\Models\Volunteer\Assignment::where('member_id', $user->id)
            ->where('status', 'completed')->count();
        $thisMonthHours = \App\Models\Volunteer\Assignment::where('member_id', $user->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->startOfMonth())->sum('hours');

        // My votes
        $myVotes = \App\Models\Volunteer\FundVote::where('member_id', $user->id)->get();
        $myProposals = \App\Models\Volunteer\FundProposal::where('proposed_by', $user->id)->get();

        // My mentor
        $myMentor = \App\Models\Volunteer\MentorshipPair::where('mentee_id', $user->id)
            ->where('status', 'active')->first();
        $myMentorData = null;
        if ($myMentor) {
            $mentor = \App\Models\Member::find($myMentor->mentor_id);
            $myMentorData = [
                'name' => $mentor->name ?? 'Unknown',
                'interests' => json_decode($myMentor->shared_interests ?? '[]'),
            ];
        }

        // My incidents
        $myIncidents = \App\Models\Volunteer\Incident::where('reported_by', $user->id)
            ->latest()->take(5)->get()->map(function($i) {
                return [
                    'id' => $i->id,
                    'title' => $i->title,
                    'location' => $i->location,
                    'severity' => $i->severity,
                    'status' => $i->status,
                    'created_at' => $i->created_at->format('d M Y'),
                ];
            });

        // My swap requests
        $mySwapRequests = \App\Models\Volunteer\SwapRequest::where('requester_id', $user->id)
            ->where('status', 'pending')->get();

        return view('volunteer.user', compact(
            'totalHours', 'requiredHours', 'completedCount', 'thisMonthHours',
            'myAssignments', 'myVotes', 'myProposals', 'myMentorData', 'myIncidents', 'mySwapRequests'
        ));
    }

    public function dashboard(): View
    {
        $user = auth()->user();
        $ledger = $this->volunteerService->getServiceLedger($user->id);
        $totalHours = $ledger->total_hours ?? 0;
        $requiredHours = $ledger->required_hours ?? 10;

        // F23: Task data
        $tasks = \App\Models\Volunteer\Task::all()->map(function($t) {
            $score = $this->volunteerService->calculateTaskDifficultyScore([
                'category' => $t->category,
                'estimated_hours' => $t->estimated_hours ?? 1,
            ]);
            return [
                'id' => $t->id,
                'name' => $t->name,
                'category' => $t->category,
                'estimated_hours' => $t->estimated_hours ?? 1,
                'difficulty_score' => $score,
                'points' => $score * ($t->estimated_hours ?? 1),
            ];
        });
        $totalTasks = $tasks->count();
        $hardTasks = $tasks->where('category', 'heavy')->count();
        $mediumTasks = $tasks->where('category', 'medium')->count();
        $easyTasks = $tasks->where('category', 'light')->count();

        // F24: Load balancer data
        $shiftsWithLoad = \App\Models\Volunteer\Shift::with('assignments')->where('status', 'active')
            ->get()->map(function($s) {
                $heavy = $s->assignments->where('role', 'heavy')->whereNotIn('status', ['swapped'])->count();
                $light = $s->assignments->whereIn('role', ['light', 'admin'])->whereNotIn('status', ['swapped'])->count();
                return [
                    'id' => $s->id,
                    'start_date' => $s->start_date,
                    'heavy' => $heavy,
                    'light' => $light,
                    'heavy_cap' => 5,
                    'light_cap' => 10,
                ];
            });
        $totalHeavyAssigned = $shiftsWithLoad->sum('heavy');
        $totalLightAssigned = $shiftsWithLoad->sum('light');

        // F25: Service hours
        $completedAssignments = \App\Models\Volunteer\Assignment::where('member_id', $user->id)
            ->where('status', 'completed')->count();
        $thisMonthHours = \App\Models\Volunteer\Assignment::where('member_id', $user->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->startOfMonth())->sum('hours');

        $memberLevel = $totalHours >= 100 ? 'Gold' : ($totalHours >= 50 ? 'Silver' : ($totalHours >= 20 ? 'Bronze' : 'Seedling'));

        // F25: All members service ledger - fallback to members count if table doesn't exist
        try {
            $allLedgers = DB::table('service_ledger')->get();
            $metRequirement = $allLedgers->where('total_hours', '>=', 10)->count();
            $inProgress = $allLedgers->filter(fn($l) => $l->total_hours > 0 && $l->total_hours < 10)->count();
            $atRisk = $allLedgers->where('total_hours', '<', 3)->count();
        } catch (\Exception $e) {
            $metRequirement = 0;
            $inProgress = 0;
            $atRisk = 0;
        }

        // F26: My shifts and swaps
        $myAssignments = \App\Models\Volunteer\Assignment::with(['shift', 'task'])
            ->where('member_id', $user->id)
            ->where('status', 'assigned')
            ->get()->map(function($a) {
                return [
                    'id' => $a->id,
                    'date' => $a->shift?->start_date?->format('d M'),
                    'task' => $a->task?->name ?? 'Assigned task',
                    'role' => $a->role,
                ];
            });

        $openSwapRequests = \App\Models\Volunteer\SwapRequest::where('status', 'pending')
            ->where('requester_id', '!=', $user->id)->get()->map(function($s) {
                $requester = \App\Models\Member::find($s->requester_id);
                $assignment = \App\Models\Volunteer\Assignment::find($s->assignment_id);
                return [
                    'id' => $s->id,
                    'requester_name' => $requester->name ?? 'Unknown',
                    'assignment_task' => $assignment?->task?->name ?? 'Shift',
                    'created_at' => $s->created_at,
                ];
            });
        $mySwapCount = \App\Models\Volunteer\SwapRequest::where('requester_id', $user->id)
            ->where('status', 'pending')->count();

        // F27: Emergency alerts
        $activeAlerts = \App\Models\Volunteer\EmergencyAlert::where('is_active', true)->get()->map(function($a) {
            return [
                'id' => $a->id,
                'title' => $a->title,
                'message' => $a->message,
                'severity' => $a->severity,
                'created_at' => $a->created_at->format('d M Y'),
            ];
        });
        $alertCount = $activeAlerts->count();

        // F28: Fund proposals
        $proposals = \App\Models\Volunteer\FundProposal::with('votes')->get()->map(function($p) {
            $yes = $p->votes->where('vote', 'yes')->count();
            $no = $p->votes->where('vote', 'no')->count();
            $total = $p->votes->count();
            $pct = $total > 0 ? round(($yes / $total) * 100) : 0;
            return [
                'id' => $p->id,
                'title' => $p->title,
                'description' => $p->description,
                'estimated_cost' => $p->estimated_cost,
                'status' => $p->status,
                'voting_ends_at' => $p->voting_ends_at,
                'yes' => $yes,
                'no' => $no,
                'total' => $total,
                'percentage' => $pct,
            ];
        });
        $openProposals = $proposals->where('status', 'open');
        $totalVotesCast = $proposals->sum('total');
        $leadingProposal = $proposals->sortByDesc('percentage')->first();

        // F29: Access logs
        $recentAccess = \App\Models\Volunteer\SecurityAccessLog::latest()->take(10)->get()->map(function($l) {
            $member = \App\Models\Member::find($l->member_id);
            return [
                'time' => $l->accessed_at->format('H:i'),
                'member_name' => $member->name ?? 'Unknown',
                'gate' => $l->gate_location,
                'action' => $l->action,
            ];
        });
        $entriesToday = \App\Models\Volunteer\SecurityAccessLog::where('action', 'entry')
            ->whereDate('accessed_at', today())->count();
        $exitsToday = \App\Models\Volunteer\SecurityAccessLog::where('action', 'exit')
            ->whereDate('accessed_at', today())->count();
        $onSiteNow = $entriesToday - $exitsToday;

        // F30: Mentorship
        $myMentor = \App\Models\Volunteer\MentorshipPair::where('mentee_id', $user->id)
            ->where('status', 'active')->first();
        $myMentorData = null;
        if ($myMentor) {
            $mentor = \App\Models\Member::find($myMentor->mentor_id);
            $myMentorData = [
                'name' => $mentor->name ?? 'Unknown',
                'interests' => json_decode($myMentor->shared_interests ?? '[]'),
                'match_percentage' => 90,
            ];
        }
        $activePairs = \App\Models\Volunteer\MentorshipPair::where('status', 'active')->count();
        $awaitingPairs = \App\Models\Member::whereNull('gardening_interests')->count();

        // F31: Incidents
        $incidents = \App\Models\Volunteer\Incident::latest()->take(10)->get()->map(function($i) {
            $reporter = \App\Models\Member::find($i->reported_by);
            return [
                'id' => $i->id,
                'title' => $i->title,
                'location' => $i->location,
                'severity' => $i->severity,
                'status' => $i->status,
                'reporter' => $reporter->name ?? 'Unknown',
                'created_at' => $i->created_at,
            ];
        });
        $openIncidents = $incidents->where('status', 'open')->count();
        $inProgressIncidents = $incidents->where('status', 'in_progress')->count();
        $resolvedIncidents = $incidents->where('status', 'resolved')->count();
        $criticalIncidents = $incidents->where('severity', 'critical')->count();

        // F32: Weather
        $upcomingShifts = \App\Models\Volunteer\Shift::where('status', 'active')
            ->where('start_date', '>=', now())->where('start_date', '<=', now()->addDays(7))
            ->get()->map(function($s) {
                return [
                    'id' => $s->id,
                    'date' => $s->start_date->format('D d'),
                    'temp' => rand(18, 28),
                    'weather' => ['clear', 'cloud', 'rain'][rand(0, 2)],
                    'cancelled' => false,
                ];
            });

        $isAdmin = $user->roles->contains('name', 'admin');

        return view('volunteer.dashboard', compact(
            // General
            'totalHours', 'requiredHours', 'memberLevel', 'completedAssignments', 'isAdmin',
            // F23 - Task Weighting
            'tasks', 'totalTasks', 'hardTasks', 'mediumTasks', 'easyTasks',
            // F24 - Load Balancer
            'shiftsWithLoad', 'totalHeavyAssigned', 'totalLightAssigned',
            // F25 - Service Hours
            'thisMonthHours', 'metRequirement', 'inProgress', 'atRisk',
            // F26 - Swaps
            'myAssignments', 'openSwapRequests', 'mySwapCount',
            // F27 - Alerts
            'activeAlerts', 'alertCount',
            // F28 - Proposals
            'proposals', 'openProposals', 'totalVotesCast', 'leadingProposal',
            // F29 - Access
            'recentAccess', 'entriesToday', 'exitsToday', 'onSiteNow',
            // F30 - Mentorship
            'myMentorData', 'activePairs', 'awaitingPairs',
            // F31 - Incidents
            'incidents', 'openIncidents', 'inProgressIncidents', 'resolvedIncidents', 'criticalIncidents',
            // F32 - Weather
            'upcomingShifts',
        ))->with([
            'teamMembers' => \App\Models\Member::count(),
        ]);
    }

    public function assign(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_id'      => 'required|integer',
            'member_id'     => 'required|integer',
            'shift_task_id' => 'nullable|integer',
            'role'          => 'nullable|in:heavy,light',
        ]);

        $result = $this->volunteerService->assign($validated);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] ?? ($result['success'] ? 'Assigned successfully.' : 'Assignment failed.')
        );
    }

    public function complete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assignment_id' => 'required|integer',
            'hours'         => 'nullable|numeric|min:0',
        ]);

        $result = $this->volunteerService->complete($validated);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message'] ?? 'Shift completed.'
        );
    }

    public function createShift(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'start_date'    => 'required|date',
            'duration_days' => 'required|integer|min:1',
        ]);

        $this->volunteerService->createShift($validated);

        return back()->with('success', 'Shift created successfully.');
    }

    public function adminIndex(): View
    {
        return $this->adminDashboard();
    }

    public function adminDashboard(): View
    {
        $user = auth()->user();

        // Check if admin
        $isAdmin = $user->roles->contains('name', 'admin');
        if (!$isAdmin) {
            return redirect()->route('volunteer')->with('error', 'Access denied. Admin only.');
        }

        // Overview Stats
        $totalMembers = \App\Models\Member::count();
        $totalShifts = \App\Models\Volunteer\Shift::count();
        $activeShifts = \App\Models\Volunteer\Shift::where('status', 'active')->count();
        $totalAssignments = \App\Models\Volunteer\Assignment::count();

        // Service Hours Stats
        $allLedgers = DB::table('service_ledger')->get();
        $metRequirement = $allLedgers->where('total_hours', '>=', 10)->count();
        $atRisk = $allLedgers->where('total_hours', '<', 3)->count();

        // Emergency Alerts
        $activeAlerts = \App\Models\Volunteer\EmergencyAlert::where('is_active', true)->get()->map(function($a) {
            return [
                'id' => $a->id,
                'title' => $a->title,
                'message' => $a->message,
                'severity' => $a->severity,
                'created_at' => $a->created_at->format('d M Y'),
            ];
        });
        $alertCount = $activeAlerts->count();

        // Fund Proposals (all)
        $proposals = \App\Models\Volunteer\FundProposal::with('votes')->get()->map(function($p) {
            $yes = $p->votes->where('vote', 'yes')->count();
            $no = $p->votes->where('vote', 'no')->count();
            $total = $p->votes->count();
            $pct = $total > 0 ? round(($yes / $total) * 100) : 0;
            return [
                'id' => $p->id,
                'title' => $p->title,
                'description' => $p->description,
                'estimated_cost' => $p->estimated_cost,
                'status' => $p->status,
                'voting_ends_at' => $p->voting_ends_at,
                'yes' => $yes,
                'no' => $no,
                'total' => $total,
                'percentage' => $pct,
            ];
        });
        $openProposals = $proposals->where('status', 'open')->count();
        $totalVotesCast = $proposals->sum('total');

        // Access Logs
        $recentAccess = \App\Models\Volunteer\SecurityAccessLog::latest()->take(20)->get()->map(function($l) {
            $member = \App\Models\Member::find($l->member_id);
            return [
                'time' => $l->accessed_at->format('H:i'),
                'date' => $l->accessed_at->format('d M'),
                'member_name' => $member->name ?? 'Unknown',
                'gate' => $l->gate_location,
                'action' => $l->action,
            ];
        });
        $entriesToday = \App\Models\Volunteer\SecurityAccessLog::where('action', 'entry')
            ->whereDate('accessed_at', today())->count();
        $exitsToday = \App\Models\Volunteer\SecurityAccessLog::where('action', 'exit')
            ->whereDate('accessed_at', today())->count();

        // Incidents (all)
        $incidents = \App\Models\Volunteer\Incident::latest()->take(20)->get()->map(function($i) {
            $reporter = \App\Models\Member::find($i->reported_by);
            return [
                'id' => $i->id,
                'title' => $i->title,
                'location' => $i->location,
                'description' => $i->description,
                'severity' => $i->severity,
                'status' => $i->status,
                'reporter' => $reporter->name ?? 'Unknown',
                'created_at' => $i->created_at->format('d M Y'),
            ];
        });
        $openIncidents = $incidents->where('status', 'open')->count();
        $inProgressIncidents = $incidents->where('status', 'in_progress')->count();
        $criticalIncidents = $incidents->whereIn('severity', ['critical', 'high'])->count();

        // Shifts for management
        $shifts = \App\Models\Volunteer\Shift::with(['tasks', 'assignments'])->latest()->take(20)->get()->map(function($s) {
            $heavy = $s->assignments->where('role', 'heavy')->count();
            $light = $s->assignments->whereIn('role', ['light', 'admin'])->count();
            return [
                'id' => $s->id,
                'start_date' => $s->start_date->format('d M Y'),
                'end_date' => $s->end_date->format('d M Y'),
                'status' => $s->status,
                'tasks' => $s->tasks->pluck('name'),
                'heavy_count' => $heavy,
                'light_count' => $light,
            ];
        });

        return view('volunteer.admin', compact(
            'totalMembers', 'totalShifts', 'activeShifts', 'totalAssignments',
            'metRequirement', 'atRisk',
            'activeAlerts', 'alertCount',
            'proposals', 'openProposals', 'totalVotesCast',
            'recentAccess', 'entriesToday', 'exitsToday',
            'incidents', 'openIncidents', 'inProgressIncidents', 'criticalIncidents',
            'shifts'
        ));
    }

    // ── F23: Task Difficulty Score ───────────────────────────────────────────

    public function calculateDifficulty(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category'         => 'required|in:heavy,light',
            'estimated_hours'  => 'required|integer|min:1',
            'custom_modifier'  => 'nullable|integer',
        ]);

        $score = $this->volunteerService->calculateTaskDifficultyScore($validated);

        return response()->json(['difficulty_score' => $score]);
    }

    // ── F24: Shift Load-Balancer ─────────────────────────────────────────────

    public function shiftBalance(Request $request, int $shiftId): JsonResponse
    {
        $request->validate(['role' => 'required|in:heavy,light']);
        $balance = $this->volunteerService->checkShiftBalance($shiftId, $request->role);

        return response()->json($balance);
    }

    // ── F25: Service Hour Ledger ─────────────────────────────────────────────

    public function serviceLedger(): View
    {
        $user = auth()->user();
        $ledger = $this->volunteerService->getServiceLedger($user->id);
        $totalHours = $ledger->total_hours ?? 0;

        $assignments = \App\Models\Volunteer\Assignment::where('member_id', $user->id)->get();
        $shiftsDone = $assignments->where('status', 'completed')->count();
        $thisMonthHours = $assignments->where('status', 'completed')
            ->where('updated_at', '>=', now()->startOfMonth())->sum('hours');

        $hoursLog = \App\Models\Volunteer\Assignment::with('task')
            ->where('member_id', $user->id)
            ->where('status', 'completed')
            ->latest()->take(20)->get()
            ->map(fn($a) => [
                'date'     => $a->updated_at->format('d M Y'),
                'task'     => $a->task?->name ?? 'Completed task',
                'category' => $a->task?->category ?? 'light',
                'hours'    => $a->hours ?? 2,
            ]);

        $avgHoursPerShift = $shiftsDone > 0 ? round($totalHours / $shiftsDone, 1) : 0;

        return view('volunteer.service_hours', compact(
            'totalHours', 'shiftsDone', 'avgHoursPerShift', 'thisMonthHours', 'hoursLog'
        ));
    }

    public function logHours(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assignment_id' => 'required|integer',
            'hours'         => 'required|numeric|min:0.5|max:12',
        ]);

        $this->volunteerService->logServiceHours(
            auth()->id(),
            $validated['assignment_id'],
            (float) $validated['hours']
        );

        return back()->with('success', 'Hours logged successfully.');
    }

    // ── F26: Shift Swap ──────────────────────────────────────────────────────

    public function swapRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assignment_id' => 'required|integer',
            'target_id'     => 'nullable|integer',
            'reason'        => 'nullable|string|max:500',
        ]);

        $this->volunteerService->createSwapRequest(
            auth()->id(),
            $validated['assignment_id'],
            $validated['target_id'] ?? null,
            $validated['reason'] ?? ''
        );

        return back()->with('success', 'Swap request submitted.');
    }

    public function respondSwap(Request $request, int $swapId): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => 'required|in:accepted,rejected',
        ]);

        $this->volunteerService->respondToSwapRequest($swapId, auth()->id(), $validated['decision']);

        return back()->with('success', 'Swap request ' . $validated['decision'] . '.');
    }

    // ── F27: Emergency Alerts ────────────────────────────────────────────────

    public function broadcastAlert(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'message'  => 'required|string',
            'severity' => 'required|in:info,warning,critical',
        ]);

        $this->volunteerService->broadcastEmergencyAlert(
            auth()->id(),
            $validated['title'],
            $validated['message'],
            $validated['severity']
        );

        return back()->with('success', 'Emergency alert broadcast.');
    }

    public function resolveAlert(int $alertId): RedirectResponse
    {
        $this->volunteerService->resolveEmergencyAlert($alertId);
        return back()->with('success', 'Alert resolved.');
    }

    public function adminAlerts(): View
    {
        $alerts = \App\Models\Volunteer\EmergencyAlert::latest()->paginate(20);
        return view('volunteer.emergencies', compact('alerts'));
    }

    // ── F28: Fund Proposals & Voting ─────────────────────────────────────────

    public function fundProposals(): View
    {
        $proposals = FundProposal::with('votes')
            ->latest()
            ->get();

        return view('volunteer.fund_proposals', compact('proposals'));
    }

    public function createProposal(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'estimated_cost' => 'required|numeric|min:0',
            'voting_ends_at' => 'required|date|after:today',
        ]);

        $validated['proposed_by'] = auth()->id();
        $this->volunteerService->createFundProposal($validated);

        return back()->with('success', 'Proposal created. Voting is open!');
    }

    public function castVote(Request $request, int $proposalId): RedirectResponse
    {
        $validated = $request->validate([
            'vote'    => 'required|in:yes,no,abstain',
            'comment' => 'nullable|string|max:500',
        ]);

        $this->volunteerService->castFundVote(
            $proposalId,
            auth()->id(),
            $validated['vote'],
            $validated['comment'] ?? null
        );

        return back()->with('success', 'Vote cast!');
    }

    public function closeProposal(int $proposalId): RedirectResponse
    {
        $result = $this->volunteerService->closeFundProposal($proposalId);
        return back()->with('success', "Proposal {$result['result']}.");
    }

    // ── F29: Security Access Log ─────────────────────────────────────────────

    public function logAccess(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'gate_code'     => 'required|string|max:20',
            'action'        => 'required|in:entry,exit',
            'gate_location' => 'nullable|string|max:100',
        ]);

        $log = $this->volunteerService->logSecurityAccess(
            auth()->id(),
            $validated['gate_code'],
            $validated['action'],
            $validated['gate_location'] ?? 'main_gate'
        );

        return response()->json($log, 201);
    }

    public function accessLogs(Request $request): View
    {
        $logs = $this->volunteerService->getAccessLogs(
            $request->only(['member_id', 'from', 'to', 'action'])
        );
        $totalEntries = \App\Models\Volunteer\SecurityAccessLog::where('action', 'entry')
            ->where('created_at', '>=', now()->subDays(30))->count();
        $exitCount = \App\Models\Volunteer\SecurityAccessLog::where('action', 'exit')
            ->where('created_at', '>=', now()->subDays(30))->count();

        return view('volunteer.access_logs', compact('logs', 'totalEntries', 'exitCount'));
    }

    // ── F30: Mentorship Pairing ──────────────────────────────────────────────

    public function pairMentor(): RedirectResponse
    {
        $pair = $this->volunteerService->pairMentor(auth()->id());

        if (! ($pair['success'] ?? false)) {
            return back()->with('error', $pair['message'] ?? 'No available mentors at this time.');
        }

        return back()->with('success', 'You have been paired with a mentor!');
    }

    public function createPairManually(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mentor_id'        => 'required|integer',
            'mentee_id'        => 'required|integer|different:mentor_id',
            'shared_interests' => 'nullable|array',
        ]);

        $this->volunteerService->createMentorshipPair(
            $validated['mentor_id'],
            $validated['mentee_id'],
            $validated['shared_interests'] ?? []
        );

        return back()->with('success', 'Mentorship pair created.');
    }

    // ── F31: Incident Reporting ──────────────────────────────────────────────

    public function reportIncident(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'location'    => 'required|string|max:255',
            'severity'    => 'nullable|in:low,medium,high,critical',
        ]);

        $validated['reported_by'] = auth()->id();
        $this->volunteerService->reportIncident($validated);

        return back()->with('success', 'Incident reported. Thank you for keeping the garden safe!');
    }

    public function adminIncidents(): View
    {
        $incidents = \App\Models\Volunteer\Incident::latest()->paginate(20);
        $activeCount = \App\Models\Volunteer\Incident::where('status', 'open')->count();
        $investigationCount = \App\Models\Volunteer\Incident::where('status', 'in_progress')->count();
        $closedCount = \App\Models\Volunteer\Incident::where('status', 'resolved')->count();

        return view('volunteer.incidents', compact('incidents', 'activeCount', 'investigationCount', 'closedCount'));
    }

    public function updateIncident(Request $request, int $incidentId): RedirectResponse
    {
        $validated = $request->validate([
            'status'           => 'required|in:open,in_progress,resolved',
            'assigned_to'      => 'nullable|integer',
            'resolution_notes' => 'nullable|string',
        ]);

        $this->volunteerService->updateIncidentStatus(
            $incidentId,
            $validated['status'],
            $validated['assigned_to'] ?? null,
            $validated['resolution_notes'] ?? null
        );

        return back()->with('success', 'Incident updated.');
    }

    // ── F32: Weather-Driven Shift Cancellation ───────────────────────────────

    public function evaluateWeather(Request $request, int $shiftId): RedirectResponse
    {
        $request->validate([
            'weather' => 'required|string|in:clear,rain,heavy_rain,storm,extreme_heat,hail,unknown',
        ]);

        $result = $this->volunteerService->evaluateWeatherForShift($shiftId, $request->weather);

        $message = $result['cancelled']
            ? "Shift cancelled & rescheduled. {$result['reason']}"
            : "Shift proceeds as planned. {$result['reason']}";

        return back()->with('success', $message);
    }
}
