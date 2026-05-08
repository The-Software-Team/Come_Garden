<?php

namespace App\Services;

use Illuminate\Support\Carbon;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;
use App\Events\ToolLibrary\ToolBooked;
use App\Events\ToolLibrary\ToolMaintained;
use App\Events\ToolLibrary\ToolReturned;
use App\Events\ToolLibrary\ToolWaitlisted;
use App\Models\Member;

use App\Models\ToolLibrary\Booking;
use App\Models\ToolLibrary\Tool;
use App\Models\ToolLibrary\Penalty;
use App\Models\ToolLibrary\ToolWaitlist;

use Illuminate\Support\Str;

use App\Support\ServiceResult;

class ToolLibraryService extends BaseService implements ToolLibraryServiceInterface {
    
    public function add_tool(array $data): ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {  
            
            $member = Member::find($data['member_id']);
            if (!$member)
                return ServiceResult::failure("Member Not Found");

            $tool = Tool::where('name', $data['name'])->exists();
            if ($tool) 
                return ServiceResult::failure("Tool Already exists");

            Tool::create([
                'name' => $data['name'],
                'usage_status' => $data['usage_status'] ?? 'low',
                'maintenance_threshold_hours' => $data['maintenance_threshold_hours'] ?? 100,
            ]);
           
            return ServiceResult::success([],'Tool Added Successfully');
        }); 
    } 


    public function book_tool(array $data): ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {

            $member = Member::find($data['member_id']);
            if (!$member)
                return ServiceResult::failure("Member Not Found");

             
            $tool = Tool::where('name', $data['tool_name'])->first();
            if (!$tool) 
                return ServiceResult::failure('Tool Not Found');
           
            if ($tool->status == "maintenance")
                return ServiceResult::failure("Tool is in maintenance");

            $durationHours = (int) $data['duration_hours'];
            
            $start = now();
            $end = now()->addHours($durationHours);

            if ($this->isToolAvailable($tool, $start, $end)) {
                $booking = Booking::create([
                    'tool_id' => $tool->id,
                    'member_id' => $member->id,
                    'start_time' => $start, 
                    'end_time'  => $end,
                    'status' => 'active',
                    'qr_token' => Str::uuid(),

                ]);

            $tool->update(['status' => 'in_use']);

            event(new ToolBooked($tool, $booking, $member));

            return ServiceResult::success([
                'booking' => $booking
            ], 'Tool Booked Successfully');
        }

            $alreadyWaitlisted = ToolWaitlist::where('member_id', $member->id)
                ->where('tool_id' , $tool->id)
                ->exists();

            if ($alreadyWaitlisted)
                return ServiceResult::failure("Already in the waitlist");
            
            $score = $this->calculatePriority($member->id);

            $waitlist = ToolWaitlist::create([
                'tool_id' => $tool->id,
                'member_id' => $member->id,
                'priority_score' => $score,
                'duration_hours' => $durationHours,
                'status' => 'waiting',
            ]);

            event(new ToolWaitlisted($tool, $member));

            return ServiceResult::failure(
                'Tool unavailable. Added to waitlist.',
                [
                    'waitlist_id' => $waitlist->id
                ]
            );
        });
    }

  public function return_tool(array $data): ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {
        
        $booking = Booking::find($data['booking_id']);

        if (!$booking) {
            return ServiceResult::failure('Booking not found');
        }

        $tool = Tool::find($booking->tool_id);

        if (!$tool) {
            return ServiceResult::failure('Tool not found');
        }

        $now = now();
        $late = $now->greaterThan($booking->end_time);

        $booking->update([
            'status' => $late ? 'overdue' : 'completed',
            'actual_return_time' => $now,
            'returned_scanned_at' => $now ]);

        if (is_null($booking->cleaned_at)) {
            Penalty::create([
                'member_id' => $booking->member_id,
                'booking_id' => $booking->id,
                'type' => 'service',
                'amount' => 2,
                'status' => 'pending',
                'created_at' => now(),
            ]);
        }
    

        $hoursUsed = $booking->start_time->diffInHours($now);
        
        $tool->increment('total_usage_hours', $hoursUsed);

        $alreadyInMaintenance = ($tool->status == "maintenance");
        if (!$alreadyInMaintenance) {
            $needsMaintenance =
                $tool->total_usage_hours >= $tool->maintenance_threshold_hours;
    
            if ($needsMaintenance) {
                $tool->status = 'maintenance';
            } else {
                $tool->status = 'available';
            }

            $tool->save();
        }

        event(new ToolReturned($tool, $booking));
        
        return ServiceResult::success([], 'Tool returned successfully');
    });
}

    public function reportDamage(array $data): ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {

            $booking = Booking::find($data['booking_id']);
            if (!$booking) 
                return ServiceResult::failure("Booking not found");

            $tool = Tool::find($booking->tool_id);
            if (!$tool) 
                return ServiceResult::failure("Tool not found");

            $severity = $data['severity'] ?? 'medium';
            $reason = $data['reason'] ?? null;

            if (!in_array($severity, ['low', 'medium', 'high'])) 
                return ServiceResult::failure("Invalid Severity");

            if ($severity === 'low') 
                return ServiceResult::success([], "Report Applied Successfully (no penalities)");

            if ($severity === 'medium') {
                Penalty::create([
                    'member_id' => $booking->member_id,
                    'booking_id' => $booking->id,
                    'type' => 'service',
                    'amount' => 3,
                    'status' => 'pending',
                    'created_at' => now(),
                    'reason' => $reason,
                ]);
            }

            if ($severity === 'high') {
                Penalty::create([
                    'member_id' => $booking->member_id,
                    'booking_id' => $booking->id,
                    'type' => 'fine',
                    'amount' => 50,
                    'status' => 'pending',
                    'created_at' => now(),
                    'reason' => $reason,
                ]);

                $tool->update(['status' => 'maintenance']);
            }

            return ServiceResult::success(
            [
                'severity' => $severity
            ],
            "Report Applied Successfully");
       });
    }

    public function processWaitlist(int $toolId): ServiceResult
    {
        return $this->handleTransaction(function () use ($toolId) {

            $tool = Tool::find($toolId);

            if (!$tool) {
                return ServiceResult::failure('Tool not found');
            }

            $waitlist = ToolWaitlist::where('tool_id', $toolId)
                ->where('status', 'waiting')
                ->orderByDesc('priority_score')
                ->orderBy('created_at')
                ->get();

            if ($waitlist->isEmpty()) {
                return ServiceResult::failure('No waitlist for this tool');
            }

            foreach ($waitlist as $entry) {

                $start = now();
                $end = now()->addHours($entry->duration_hours);
                
                if (!$this->isToolAvailable($tool, $start, $end)) {
                    continue;
                }

                // create booking
                $booking = Booking::create([
                    'tool_id' => $tool->id,
                    'member_id' => $entry->member_id,
                    'start_time' => $start,
                    'end_time' => $end,
                    'status' => 'active',
                    'qr_token' => Str::uuid(),
                ]);

                // mark waitlist entry as processed
                $entry->update([
                    'status' => 'processed'
                ]);

                // update tool status ONLY after successful booking
                $tool->update([
                    'status' => 'in_use'
                ]);

                event(new ToolBooked($tool, $booking, $entry->member));

                return ServiceResult::success([
                    'booking' => $booking
                ], 'Waitlist processed successfully');
            }

            return ServiceResult::failure('No eligible waitlist entries for this tool');
        });
    }

    public function maintainTool(int $toolId) : void
    {
        $tool = Tool::findOrFail($toolId);
    
        $tool->update([
            'status' => 'available',
            'total_usage_hours' => 0
        ]);
    
        event(new ToolMaintained($tool));
    }

    public function processScan(string $token): ServiceResult
    {
        return $this->handleTransaction(function () use ($token) {

            $booking = Booking::where('qr_token', $token)->first();

            if (!$booking) {
                return ServiceResult::failure('Invalid QR token');
            }

            $tool = Tool::find($booking->tool_id);

            if (!$tool) {
                return ServiceResult::failure('Tool not found');
            }

            $now = now();

            // 1. PICKUP (ONLY ONCE)
            if (is_null($booking->picked_up_at)) {

                $booking->update([
                    'picked_up_at' => $now,
                    'status' => 'active'
                ]);

                event(new ToolBooked($tool, $booking, $booking->member));

                return ServiceResult::success([], 'Tool picked up successfully');
            }

            // 2. CLEANING 
            if (
                !is_null($booking->picked_up_at) &&
                is_null($booking->cleaned_at)
            ) {

                $booking->update([
                    'cleaned_at' => $now
                ]);

                $tool->update([
                    'status' => 'available'
                ]);

                event(new ToolMaintained($tool));

                return ServiceResult::success([], 'Tool cleaned successfully');
            }

            return ServiceResult::success([], 'No action required');
        });
    }

    // helpers
    public function isToolAvailable(
        Tool $tool,
        Carbon $requestedStart,
        Carbon $requestedEnd
    ): bool {

        if ($tool->status !== 'available') {
            return false;
        }
    
        $overlap = Booking::query()
            ->where('tool_id', $tool->id)
            ->whereIn('status', ['active', 'overdue'])
            ->where(function ($query) use (
                $requestedStart,
                $requestedEnd
            ) {
    
                $query
                    ->where('start_time', '<', $requestedEnd)
                    ->where('end_time', '>', $requestedStart);
            })
            ->exists();
    
        return !$overlap;
    }

    public function calculatePriority(int $userId): int
    {
        $baseScore = 100;
        
        $penalties = Penalty::where('member_id', $userId)->get();

        $weights = [
            'service' => 5,
            'fine' => 20,
        ];

        $totalPenaltyScore = 0;

        foreach ($penalties as $penalty) {

            $weight = $weights[$penalty->type] ?? 10;

            $daysAgo = $penalty->created_at
                ? $penalty->created_at->diffInDays(now())
                : 0;

            if ($daysAgo > 30) {
                $weight *= 0.5;
            }

            $totalPenaltyScore += $weight;
        }

        return max($baseScore - $totalPenaltyScore, 0);
    }

}

    