<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToolLibrary\Tool;
use App\Models\ToolLibrary\Booking;
use App\Models\ToolLibrary\ToolWaitlist;
use App\Models\ToolLibrary\Penalty;
use App\Models\Member;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        // SPECIAL MEMBER
        $saged = Member::firstOrCreate(
            ['email' => 'saged@example.com'],
            ['name' => 'Saged Nader', 'password' => bcrypt('password')]
        );

        // TOOLS
        $tools = Tool::factory()
            ->count(8)
            ->create();

        foreach ($tools as $tool) {

            // 1. Random active booking (some tools only)
            if (fake()->boolean(60)) {

                $booking = Booking::create([
                    'tool_id' => $tool->id,
                    'member_id' => $saged->id,
                    'start_time' => now()->subHours(2),
                    'end_time' => now()->addHours(2),
                    'status' => 'active',
                    'qr_token' => fake()->uuid(),
                    'picked_up_at' => now()->subHours(2),
                ]);

                // enforce consistency
                $tool->update(['status' => 'in_use']);
            }

            // 3. Penalties linked to bookings
            if (isset($booking) && fake()->boolean(40)) {

                Penalty::create([
                    'member_id' => $saged->id,
                    'booking_id' => $booking->id,
                    'type' => 'service',
                    'severity' => 'medium',
                    'amount' => 5,
                    'status' => 'pending',
                    'reason' => 'Seed generated penalty',
                ]);
            }

            unset($booking);
        }
    }
}