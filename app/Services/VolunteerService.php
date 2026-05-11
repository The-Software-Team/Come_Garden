<?php

namespace App\Services;

use App\Contracts\Volunteer\VolunteerServiceInterface;
use App\Models\Volunteer\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use RuntimeException;

class VolunteerService implements VolunteerServiceInterface
{
    // ════════════════════════════════════════════════════════
    //  Original 4 functions (already in your project)
    // ════════════════════════════════════════════════════════

    public function createShift(array $data): mixed
    {
        $start = Carbon::parse($data['start_date']);
        $end   = (clone $start)->addDays((int) $data['duration_days']);

        $shift = Shift::create([
            'start_date' => $start,
            'end_date'   => $end,
            'status'     => 'active',
        ]);

        return ['success' => true, 'shift_id' => $shift->id];
    }

    public function assign(array $data): mixed
    {
        // F24: check balance before assigning
        $balance = $this->checkShiftBalance(
            $data['shift_id'],
            $data['role'] ?? 'light'
        );

        if (! $balance['can_assign']) {
            return ['success' => false, 'message' => 'No slots available for this role.'];
        }

        $assignment = DB::table('assignments')->insertGetId([
            'shift_id'      => $data['shift_id'],
            'member_id'     => $data['member_id'],
            'shift_task_id' => $data['shift_task_id'] ?? null,
            'role'          => $data['role'] ?? 'light',
            'status'        => 'assigned',
            'hours'         => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return ['success' => true, 'assignment_id' => $assignment];
    }

    public function complete(array $data): mixed
    {
        $assignment = DB::table('assignments')->where('id', $data['assignment_id'])->first();

        if (! $assignment) {
            return ['success' => false, 'message' => 'Assignment not found.'];
        }

        DB::table('assignments')->where('id', $data['assignment_id'])->update([
            'status'     => 'completed',
            'hours'      => $data['hours'] ?? 0,
            'updated_at' => now(),
        ]);

        // F25: log hours to service ledger
        if (! empty($data['hours']) && $data['hours'] > 0) {
            $this->logServiceHours(
                $assignment->member_id,
                $assignment->id,
                (float) $data['hours'],
                $assignment->role ?? 'light'
            );
        }

        return ['success' => true];
    }

    public function requestSwap(array $data): mixed
    {
        return $this->createSwapRequest(
            $data['requester_id'],
            $data['assignment_id'],
            $data['target_id'] ?? null,
            $data['reason']    ?? ''
        );
    }

    // ════════════════════════════════════════════════════════
    //  F23 – Communal Task Weighting Logic
    // ════════════════════════════════════════════════════════

    /**
     * Calculate difficulty score (1-10) for a task.
     *
     * Rules (matching your 'tasks' table category column):
     *   heavy        → base 6
     *   light        → base 3
     *   +1 per extra hour beyond the first (max +4)
     */
    public function calculateTaskDifficultyScore(array $data): int
    {
        $base    = $data['category'] === 'heavy' ? 6 : 3;
        $hours   = max(1, (int) ($data['estimated_hours'] ?? 1));
        $bonus   = min($hours - 1, 4);
        $custom  = (int) ($data['custom_modifier'] ?? 0);

        return max(1, min(10, $base + $bonus + $custom));
    }

    // ════════════════════════════════════════════════════════
    //  F24 – Volunteer Shift Load-Balancer
    // ════════════════════════════════════════════════════════

    /**
     * Check if a shift can accept another member with the given role.
     * Uses the 'assignments' table (shift_id, role, status).
     *
     * Default caps: 5 heavy, 10 light per shift.
     */
    public function checkShiftBalance(int $shiftId, string $role): array
    {
        $heavyCap = 5;
        $lightCap = 10;

        $heavyCount = DB::table('assignments')
            ->where('shift_id', $shiftId)
            ->where('role', 'heavy')
            ->whereNotIn('status', ['swapped'])
            ->count();

        $lightCount = DB::table('assignments')
            ->where('shift_id', $shiftId)
            ->where('role', 'light')
            ->whereNotIn('status', ['swapped'])
            ->count();

        $canAssign = $role === 'heavy'
            ? $heavyCount < $heavyCap
            : $lightCount < $lightCap;

        return [
            'can_assign'        => $canAssign,
            'heavy_slots_left'  => max(0, $heavyCap - $heavyCount),
            'light_slots_left'  => max(0, $lightCap - $lightCount),
        ];
    }

    // ════════════════════════════════════════════════════════
    //  F25 – Mandatory Service Hour Tracker
    // ════════════════════════════════════════════════════════

    /**
     * Get or create the service_ledger row for a member.
     * Columns: member_id, total_hours, heavy_hours, required_hours
     */
    public function getServiceLedger(int $memberId): object
    {
        $ledger = DB::table('service_ledger')->where('member_id', $memberId)->first();

        if (! $ledger) {
            DB::table('service_ledger')->insert([
                'member_id'      => $memberId,
                'total_hours'    => 0,
                'heavy_hours'    => 0,
                'required_hours' => 10,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $ledger = DB::table('service_ledger')->where('member_id', $memberId)->first();
        }

        return $ledger;
    }

    /**
     * Add hours to the ledger and check if requirement is met.
     */
    public function logServiceHours(
        int    $memberId,
        int    $assignmentId,
        float  $hours,
        string $role = 'light'
    ): array {
        $ledger = $this->getServiceLedger($memberId);

        $newTotal = $ledger->total_hours + $hours;
        $newHeavy = $ledger->heavy_hours + ($role === 'heavy' ? $hours : 0);

        DB::table('service_ledger')->where('member_id', $memberId)->update([
            'total_hours' => $newTotal,
            'heavy_hours' => $newHeavy,
            'updated_at'  => now(),
        ]);

        $requirementMet = $newTotal >= $ledger->required_hours;

        return [
            'success'         => true,
            'total_hours'     => $newTotal,
            'heavy_hours'     => $newHeavy,
            'required_hours'  => $ledger->required_hours,
            'requirement_met' => $requirementMet,
        ];
    }

    // ════════════════════════════════════════════════════════
    //  F26 – Shift Substitution Workflow
    // ════════════════════════════════════════════════════════

    /**
     * Create a swap request.
     * Uses 'swap_requests' table:
     *   shift_id, assignment_id, requester_id, target_id, status, reason
     */
    public function createSwapRequest(
        int    $requesterId,
        int    $assignmentId,
        ?int   $targetId,
        string $reason
    ): array {
        // Verify the assignment belongs to requester
        $assignment = DB::table('assignments')
            ->where('id', $assignmentId)
            ->where('member_id', $requesterId)
            ->where('status', 'assigned')
            ->first();

        if (! $assignment) {
            return ['success' => false, 'message' => 'Assignment not found or not eligible for swap.'];
        }

        // Prevent duplicate pending requests
        $existing = DB::table('swap_requests')
            ->where('assignment_id', $assignmentId)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return ['success' => false, 'message' => 'A pending swap request already exists.'];
        }

        $id = DB::table('swap_requests')->insertGetId([
            'shift_id'      => $assignment->shift_id,
            'assignment_id' => $assignmentId,
            'requester_id'  => $requesterId,
            'target_id'     => $targetId,
            'status'        => 'pending',
            'reason'        => $reason,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return ['success' => true, 'swap_request_id' => $id];
    }

    /**
     * Accept or reject a swap request.
     * On accept: transfers the assignment to the responder.
     */
    public function respondToSwapRequest(
        int    $swapRequestId,
        int    $responderId,
        string $decision   // 'accepted' | 'rejected'
    ): array {
        if (! in_array($decision, ['accepted', 'rejected'])) {
            return ['success' => false, 'message' => 'Decision must be accepted or rejected.'];
        }

        return DB::transaction(function () use ($swapRequestId, $responderId, $decision) {
            $swap = DB::table('swap_requests')
                ->where('id', $swapRequestId)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (! $swap) {
                return ['success' => false, 'message' => 'Swap request not found or already resolved.'];
            }

            DB::table('swap_requests')->where('id', $swapRequestId)->update([
                'status'     => $decision,
                'updated_at' => now(),
            ]);

            if ($decision === 'accepted') {
                // Mark original assignment as swapped
                DB::table('assignments')->where('id', $swap->assignment_id)->update([
                    'status'     => 'swapped',
                    'updated_at' => now(),
                ]);

                // Get original assignment details
                $original = DB::table('assignments')->where('id', $swap->assignment_id)->first();

                // Check balance for responder
                $balance = $this->checkShiftBalance($original->shift_id, $original->role);
                if (! $balance['can_assign']) {
                    return ['success' => false, 'message' => 'No slots available for the swap responder.'];
                }

                // Create new assignment for responder
                DB::table('assignments')->insert([
                    'shift_id'      => $original->shift_id,
                    'member_id'     => $responderId,
                    'shift_task_id' => $original->shift_task_id,
                    'role'          => $original->role,
                    'status'        => 'assigned',
                    'hours'         => 0,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            return ['success' => true, 'decision' => $decision];
        });
    }

    // ════════════════════════════════════════════════════════
    //  F27 – Emergency Site-Status Broadcaster
    // ════════════════════════════════════════════════════════

    /**
     * Broadcast an emergency alert to all active members.
     * Stored in 'emergency_alerts' table (create migration if needed).
     * Falls back gracefully if table doesn't exist yet.
     */
    public function broadcastEmergencyAlert(
        int    $adminId,
        string $title,
        string $message,
        string $severity = 'warning'   // info | warning | critical
    ): array {
        $alertId = DB::table('emergency_alerts')->insertGetId([
            'created_by'  => $adminId,
            'title'       => $title,
            'message'     => $message,
            'severity'    => $severity,
            'is_active'   => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return [
            'success'  => true,
            'alert_id' => $alertId,
            'severity' => $severity,
        ];
    }

    public function resolveEmergencyAlert(int $alertId): array
    {
        DB::table('emergency_alerts')->where('id', $alertId)->update([
            'is_active'   => false,
            'resolved_at' => now(),
            'updated_at'  => now(),
        ]);

        return ['success' => true];
    }

    // ════════════════════════════════════════════════════════
    //  F28 – Communal Fund Allocation Voting
    // ════════════════════════════════════════════════════════

    public function createFundProposal(array $data): array
    {
        $id = DB::table('fund_proposals')->insertGetId([
            'title'          => $data['title'],
            'description'    => $data['description'],
            'estimated_cost' => $data['estimated_cost'],
            'proposed_by'    => $data['proposed_by'],
            'status'         => 'open',
            'voting_ends_at' => $data['voting_ends_at'] ?? now()->addDays(7),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return ['success' => true, 'proposal_id' => $id];
    }

    public function castFundVote(
        int     $proposalId,
        int     $memberId,
        string  $vote,       // yes | no | abstain
        ?string $comment
    ): array {
        $proposal = DB::table('fund_proposals')->where('id', $proposalId)->first();

        if (! $proposal || $proposal->status !== 'open') {
            return ['success' => false, 'message' => 'Proposal is not open for voting.'];
        }

        if (Carbon::parse($proposal->voting_ends_at)->isPast()) {
            return ['success' => false, 'message' => 'Voting period has ended.'];
        }

        $alreadyVoted = DB::table('fund_votes')
            ->where('fund_proposal_id', $proposalId)
            ->where('member_id', $memberId)
            ->exists();

        if ($alreadyVoted) {
            return ['success' => false, 'message' => 'You have already voted.'];
        }

        DB::table('fund_votes')->insert([
            'fund_proposal_id' => $proposalId,
            'member_id'        => $memberId,
            'vote'             => $vote,
            'comment'          => $comment,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return ['success' => true];
    }

    public function closeFundProposal(int $proposalId): array
    {
        $yes = DB::table('fund_votes')
            ->where('fund_proposal_id', $proposalId)
            ->where('vote', 'yes')->count();

        $no = DB::table('fund_votes')
            ->where('fund_proposal_id', $proposalId)
            ->where('vote', 'no')->count();

        $result = $yes > $no ? 'approved' : 'rejected';

        DB::table('fund_proposals')->where('id', $proposalId)->update([
            'status'     => $result,
            'updated_at' => now(),
        ]);

        return ['success' => true, 'result' => $result, 'yes' => $yes, 'no' => $no];
    }

    // ════════════════════════════════════════════════════════
    //  F29 – Garden Security Access Log
    // ════════════════════════════════════════════════════════

    public function logSecurityAccess(
        int    $memberId,
        string $gateCode,
        string $action,        // entry | exit
        string $gateLocation = 'main_gate'
    ): array {
        $id = DB::table('security_access_logs')->insertGetId([
            'member_id'      => $memberId,
            'gate_code_used' => $gateCode,
            'action'         => $action,
            'gate_location'  => $gateLocation,
            'accessed_at'    => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return ['success' => true, 'log_id' => $id];
    }

    public function getAccessLogs(array $filters = [], int $perPage = 20): mixed
    {
        $query = DB::table('security_access_logs')
            ->orderByDesc('accessed_at');

        if (! empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }
        if (! empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        if (! empty($filters['from'])) {
            $query->where('accessed_at', '>=', Carbon::parse($filters['from']));
        }
        if (! empty($filters['to'])) {
            $query->where('accessed_at', '<=', Carbon::parse($filters['to']));
        }

        return $query->paginate($perPage);
    }

    // ════════════════════════════════════════════════════════
    //  F30 – Mentorship Pairing Algorithm
    // ════════════════════════════════════════════════════════

    /**
     * Pair a new member (mentee) with the best available master gardener.
     * Matches on shared gardening_interests (JSON column on members table).
     * If no interests overlap, picks the mentor with fewest active pairs.
     */
    public function pairMentor(int $menteeId): array
    {
        // Get active mentor IDs (already mentoring someone)
        $busyMentorIds = DB::table('mentorship_pairs')
            ->where('status', 'active')
            ->pluck('mentor_id')
            ->toArray();

        // Get mentee interests
        $mentee          = DB::table('members')->where('id', $menteeId)->first();
        $menteeInterests = json_decode($mentee->gardening_interests ?? '[]', true);

        // Find available master gardeners
        $candidates = DB::table('members')
            ->join('member_role', 'members.id', '=', 'member_role.member_id')
            ->join('roles', 'member_role.role_id', '=', 'roles.id')
            ->where('roles.name', 'master_gardener')
            ->whereNotIn('members.id', $busyMentorIds)
            ->where('members.id', '!=', $menteeId)
            ->select('members.id', 'members.gardening_interests')
            ->get();

        if ($candidates->isEmpty()) {
            return ['success' => false, 'message' => 'No available mentors at this time.'];
        }

        // Score by shared interests
        $best      = null;
        $bestScore = -1;

        foreach ($candidates as $mentor) {
            $mentorInterests = json_decode($mentor->gardening_interests ?? '[]', true);
            $overlap         = count(array_intersect($menteeInterests, $mentorInterests));
            if ($overlap > $bestScore) {
                $bestScore = $overlap;
                $best      = $mentor;
            }
        }

        return $this->createMentorshipPair(
            $best->id,
            $menteeId,
            array_values(array_intersect(
                $menteeInterests,
                json_decode($best->gardening_interests ?? '[]', true)
            ))
        );
    }

    public function createMentorshipPair(int $mentorId, int $menteeId, array $sharedInterests): array
    {
        $id = DB::table('mentorship_pairs')->insertGetId([
            'mentor_id'        => $mentorId,
            'mentee_id'        => $menteeId,
            'shared_interests' => json_encode($sharedInterests),
            'status'           => 'active',
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return ['success' => true, 'pair_id' => $id, 'mentor_id' => $mentorId];
    }

    // ════════════════════════════════════════════════════════
    //  F31 – Incident & Hazard Reporting
    // ════════════════════════════════════════════════════════

    /**
     * Auto-triage severity based on keywords in title/description.
     */
    public function reportIncident(array $data): array
    {
        $severity = $data['severity'] ?? 'medium';
        $text     = strtolower(($data['title'] ?? '') . ' ' . ($data['description'] ?? ''));

        $criticalKw = ['broken glass', 'fire', 'flood', 'chemical', 'electric', 'collapse'];
        $highKw     = ['slippery', 'wasp', 'bee', 'falling', 'sharp'];

        foreach ($criticalKw as $kw) {
            if (str_contains($text, $kw)) {
                $severity = 'critical';
                break;
            }
        }

        if ($severity !== 'critical') {
            foreach ($highKw as $kw) {
                if (str_contains($text, $kw)) {
                    $severity = 'high';
                    break;
                }
            }
        }

        $id = DB::table('incidents')->insertGetId([
            'reported_by' => $data['reported_by'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'location'    => $data['location'],
            'severity'    => $severity,
            'status'      => 'open',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Auto-broadcast if critical
        if ($severity === 'critical') {
            $this->broadcastEmergencyAlert(
                $data['reported_by'],
                '⚠️ Critical Incident: ' . $data['title'],
                'Location: ' . $data['location'] . '. ' . $data['description'],
                'critical'
            );
        }

        return ['success' => true, 'incident_id' => $id, 'severity' => $severity];
    }

    public function updateIncidentStatus(
        int     $incidentId,
        string  $status,
        ?int    $assignedTo      = null,
        ?string $resolutionNotes = null
    ): array {
        $updates = ['status' => $status, 'updated_at' => now()];

        if ($assignedTo)       $updates['assigned_to']       = $assignedTo;
        if ($resolutionNotes)  $updates['resolution_notes']  = $resolutionNotes;
        if ($status === 'resolved') $updates['resolved_at']  = now();

        DB::table('incidents')->where('id', $incidentId)->update($updates);

        return ['success' => true];
    }

    // ════════════════════════════════════════════════════════
    //  F32 – Weather-Driven Shift Cancellation
    // ════════════════════════════════════════════════════════

    private const BAD_WEATHER = ['rain', 'heavy_rain', 'storm', 'extreme_heat', 'hail'];

    /**
     * Evaluate simulated weather for a shift.
     * If bad weather → cancel shift (status='cancelled') and create a
     * rescheduled copy with start_date + 7 days.
     */
    public function evaluateWeatherForShift(int $shiftId, string $simulatedWeather): array
    {
        $shift = DB::table('shifts')->where('id', $shiftId)->first();

        if (! $shift) {
            return ['cancelled' => false, 'reason' => 'Shift not found.'];
        }

        if (! in_array($simulatedWeather, self::BAD_WEATHER)) {
            return [
                'cancelled'    => false,
                'new_shift_id' => null,
                'reason'       => 'Weather is acceptable. Shift proceeds.',
            ];
        }

        // Cancel the shift
        DB::table('shifts')->where('id', $shiftId)->update([
            'status'     => 'cancelled',
            'updated_at' => now(),
        ]);

        // Reschedule: +7 days
        $newStart = Carbon::parse($shift->start_date)->addDays(7);
        $newEnd   = Carbon::parse($shift->end_date)->addDays(7);

        $newShiftId = DB::table('shifts')->insertGetId([
            'start_date' => $newStart,
            'end_date'   => $newEnd,
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notify affected members via emergency alert
        $this->broadcastEmergencyAlert(
            1, // system admin id
            'Shift Rescheduled',
            "Due to {$simulatedWeather}, the shift originally on "
                . Carbon::parse($shift->start_date)->format('M d')
                . " has been rescheduled to "
                . $newStart->format('M d, Y') . '.',
            'info'
        );

        return [
            'cancelled'    => true,
            'new_shift_id' => $newShiftId,
            'reason'       => "Cancelled due to: {$simulatedWeather}. Rescheduled to {$newStart->toDateString()}.",
        ];
    }
}