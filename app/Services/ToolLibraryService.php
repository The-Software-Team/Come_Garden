<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;
use App\Models\Booking;
use App\Models\Tool;
use Illuminate\Routing\RedirectController;

class ToolLibraryService implements ToolLibraryServiceInterface {
    
    public function add_tool(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Business rule
            if (Tool::where('name', $data['name'])->exists()) {
                throw new \DomainException('Tool with this name already exists');
            }

            Tool::create([
                'name' => $data['name'],
                'usage_status' => $data['usage_status'] ?? 'low',
                'maintenance_threshold_hours' => $data['maintenance_threshold_hours'] ?? 100,
            ]);
            
            ## Later we'll add a ServiceResult class
            return [
                'success' => True,
                'message' => 'Tool Added Successfully'
            ];
        }); 
    }


    public function book_tool(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $tool = Tool::where('name', $data['tool_name'])->first();

            if (!$tool) {
                throw new \DomainException('Tool does not exist');
            }

            $user = auth()->user();
            
            // Check overlapping bookings
            $overlap = Booking::where('tool_id', $tool->id)
                ->whereIn('status', ['active', 'overdue'])
                ->where(function ($q) use ($data) {
                    $start = now();
                    $end = now()->addHours((int) $data['duration_hours']);

                    $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end]);
                })
                ->exists();

            if ($overlap || $tool->status !== 'available') {
                return [
                    'success' => false,
                    'waitlisted' => true,
                    'message' => 'Tool is not available right now'
                ];
            }

            $booking = Booking::create([
                'tool_id' => $tool->id,
                'member_id' => $user->id,
                'start_time' => now(),
                'end_time'  => now()->addHours((int) $data['duration_hours']),
                'status' => 'active'
            ]);

            $tool->update(['status' => 'in_use']);

            return [
                'success' => true,
                'booking_id' => $booking->id
            ];
        });
    }

    public function return_tool(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $booking = Booking::find($data['booking_id']);

            if (!$booking) {
                throw new \DomainException('Booking not found');
            }

            $tool = Tool::find($booking->tool_id);

            if (!$tool) {
                throw new \DomainException('Tool not found');
            }

            $now = now();
            $late = $now->greaterThan($booking->end_time);

            $booking->update([
                'status' => $late ? 'overdue' : 'completed',
                'actual_return_time' => $now
            ]);

            // Handle cleaning penalty
            if (isset($data['cleaned']) && !$data['cleaned']) {
                DB::table('penalties')->insert([
                    'user_id' => $booking->member_id,
                    'booking_id' => $booking->id,
                    'type' => 'service',
                    'amount' => 2,
                    'status' => 'pending',
                    'created_at' => now()
                ]);
            }

            // Calculate usage hours
            $hoursUsed = $booking->start_time->diffInHours($now);

            $tool->increment('total_usage_hours', $hoursUsed);

            $tool->update([
                'status' => 'available'
            ]);

            return [
                'success' => true,
                'late' => $late
            ];
        });
    }

    public function reportDamage(array $data): array
    {
        return DB::transaction(function () use ($data) {

            $booking = Booking::find($data['booking_id']);

            if (!$booking) {
                throw new \DomainException('Booking not found');
            }

            $tool = Tool::find($booking->tool_id);

            if (!$tool) {
                throw new \DomainException('Tool not found');
            }

            $severity = $data['severity'] ?? 'medium';

            if (!in_array($severity, ['low', 'medium', 'high'])) {
                throw new \DomainException('Invalid severity');
            }

            if ($severity === 'low') {
                return [
                    'success' => true,
                    'message' => 'No penalty applied'
                ];
            }

            if ($severity === 'medium') {
                DB::table('tool_penalties')->insert([
                    'member_id' => $booking->member_id,
                    'booking_id' => $booking->id,
                    'type' => 'service',
                    'amount' => 3,
                    'status' => 'pending',
                    'created_at' => now()
                ]);

                $tool->update(['status' => 'maintenance']);
            }

            if ($severity === 'high') {
                DB::table('tool_penalties')->insert([
                    'member_id' => $booking->member_id,
                    'booking_id' => $booking->id,
                    'type' => 'fine',
                    'amount' => 50,
                    'status' => 'pending',
                    'created_at' => now()
                ]);

                $tool->update(['status' => 'decommissioned']);
            }

            return [
                'success' => true,
                'severity' => $severity
            ];
        });
    }
}
    