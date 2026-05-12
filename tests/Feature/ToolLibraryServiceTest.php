<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Services\ToolLibraryService;
use App\Models\Member;
use App\Models\ToolLibrary\Tool;
use App\Models\ToolLibrary\Booking;
use App\Models\ToolLibrary\Penalty;
use App\Models\ToolLibrary\ToolWaitlist;
use App\Events\ToolLibrary\ToolBooked;
use App\Events\ToolLibrary\ToolReturned;
use App\Events\ToolLibrary\ToolMaintained;
use App\Events\ToolLibrary\ToolWaitlisted;

/**
 * php artisan test tests/Feature/Services/ToolLibraryServiceTest.php
 */
class ToolLibraryServiceTest extends TestCase
{
    use RefreshDatabase;

    private ToolLibraryService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ToolLibraryService();
    }

    /* ── helpers ───────────────────────────────────────────── */

    private function member(): Member
    {
        return Member::create([
            'name'     => 'Test Member',
            'email'    => 'm' . uniqid() . '@test.com',
            'password' => bcrypt('pass'),
        ]);
    }

    private function tool(string $name = 'Spade', string $status = 'available'): Tool
    {
        return Tool::create([
            'name'                        => $name,
            'status'                      => $status,
            'usage_status'                => 'low',
            'total_usage_hours'           => 0,
            'maintenance_threshold_hours' => 100,
        ]);
    }

    private function activeBooking(Tool $tool, Member $member, int $hoursAgo = 2): Booking
    {
        return Booking::create([
            'tool_id'    => $tool->id,
            'member_id'  => $member->id,
            'start_time' => now()->subHours($hoursAgo),
            'end_time'   => now()->addHours(2),
            'status'     => 'active',
            'qr_token'   => \Illuminate\Support\Str::uuid(),
        ]);
    }

    /* ══════════════════════════════════════════════════════════
       WHITE-BOX: book_tool()
       Paths: member missing | tool missing | in maintenance |
              available → booked | unavailable → waitlisted |
              unavailable + already waitlisted
       ══════════════════════════════════════════════════════════ */

    public function test_book_wb_member_not_found(): void
    {
        $this->tool('Drill');
        $r = $this->service->book_tool(['member_id' => 99999, 'tool_name' => 'Drill', 'duration_hours' => 2]);
        $this->assertFalse($r->success);
    }

    public function test_book_wb_tool_not_found(): void
    {
        $m = $this->member();
        $r = $this->service->book_tool(['member_id' => $m->id, 'tool_name' => 'GhostTool', 'duration_hours' => 2]);
        $this->assertFalse($r->success);
    }

    public function test_book_wb_tool_in_maintenance_blocked(): void
    {
        $m = $this->member();
        $this->tool('Mower', 'maintenance');

        $r = $this->service->book_tool(['member_id' => $m->id, 'tool_name' => 'Mower', 'duration_hours' => 2]);

        $this->assertFalse($r->success);
        $this->assertStringContainsStringIgnoringCase('maintenance', $r->message);
    }

    public function test_book_wb_available_tool_creates_booking(): void
    {
        Event::fake([ToolBooked::class]);
        $m = $this->member();
        $t = $this->tool('Spade');

        $r = $this->service->book_tool(['member_id' => $m->id, 'tool_name' => 'Spade', 'duration_hours' => 3]);

        $this->assertTrue($r->success);
        $this->assertDatabaseHas('bookings', ['tool_id' => $t->id, 'member_id' => $m->id, 'status' => 'active']);
        $this->assertDatabaseHas('tools', ['id' => $t->id, 'status' => 'in_use']);
        Event::assertDispatched(ToolBooked::class);
    }

    public function test_book_wb_unavailable_tool_adds_to_waitlist(): void
    {
        Event::fake([ToolWaitlisted::class]);
        $m1 = $this->member();
        $m2 = $this->member();
        $t  = $this->tool('Tiller');
        $this->activeBooking($t, $m1);
        $t->update(['status' => 'in_use']);

        $r = $this->service->book_tool(['member_id' => $m2->id, 'tool_name' => 'Tiller', 'duration_hours' => 2]);

        $this->assertFalse($r->success);
        $this->assertDatabaseHas('tool_waitlists', ['tool_id' => $t->id, 'member_id' => $m2->id]);
        Event::assertDispatched(ToolWaitlisted::class);
    }

    public function test_book_wb_already_waitlisted_rejected(): void
    {
        $m = $this->member();
        $t = $this->tool('Tiller', 'in_use');
        ToolWaitlist::create(['tool_id' => $t->id, 'member_id' => $m->id, 'status' => 'waiting', 'priority_score' => 100, 'duration_hours' => 2]);

        $r = $this->service->book_tool(['member_id' => $m->id, 'tool_name' => 'Tiller', 'duration_hours' => 2]);

        $this->assertFalse($r->success);
        $this->assertStringContainsStringIgnoringCase('waitlist', $r->message);
    }

    /* ══════════════════════════════════════════════════════════
       WHITE-BOX: return_tool()
       Paths: booking missing | tool missing | on time (no penalty) |
              late (overdue) | not cleaned (penalty) | triggers maintenance
       ══════════════════════════════════════════════════════════ */

    public function test_return_wb_booking_not_found(): void
    {
        $r = $this->service->return_tool(['booking_id' => 99999]);
        $this->assertFalse($r->success);
    }

    public function test_return_wb_on_time_no_overdue(): void
    {
        Event::fake([ToolReturned::class]);
        $m = $this->member();
        $t = $this->tool('Fork');
        $b = Booking::create([
            'tool_id'    => $t->id,
            'member_id'  => $m->id,
            'start_time' => now()->subHour(),
            'end_time'   => now()->addHour(), // end is in the future → on time
            'status'     => 'active',
            'qr_token'   => \Illuminate\Support\Str::uuid(),
            'cleaned_at' => now(), // already cleaned → no penalty
        ]);

        $r = $this->service->return_tool(['booking_id' => $b->id]);

        $this->assertTrue($r->success);
        $this->assertDatabaseHas('bookings', ['id' => $b->id, 'status' => 'completed']);
        Event::assertDispatched(ToolReturned::class);
    }

    public function test_return_wb_late_marks_overdue(): void
    {
        Event::fake([ToolReturned::class]);
        $m = $this->member();
        $t = $this->tool('Rake');
        $b = Booking::create([
            'tool_id'    => $t->id,
            'member_id'  => $m->id,
            'start_time' => now()->subHours(5),
            'end_time'   => now()->subHour(), // end already passed → late
            'status'     => 'active',
            'qr_token'   => \Illuminate\Support\Str::uuid(),
            'cleaned_at' => now(),
        ]);

        $r = $this->service->return_tool(['booking_id' => $b->id]);

        $this->assertTrue($r->success);
        $this->assertDatabaseHas('bookings', ['id' => $b->id, 'status' => 'overdue']);
    }

    public function test_return_wb_not_cleaned_creates_penalty(): void
    {
        Event::fake([ToolReturned::class]);
        $m = $this->member();
        $t = $this->tool('Hoe');
        $b = Booking::create([
            'tool_id'    => $t->id,
            'member_id'  => $m->id,
            'start_time' => now()->subHour(),
            'end_time'   => now()->addHour(),
            'status'     => 'active',
            'qr_token'   => \Illuminate\Support\Str::uuid(),
            'cleaned_at' => null, // not cleaned
        ]);

        $this->service->return_tool(['booking_id' => $b->id]);

        $this->assertDatabaseHas('penalties', ['booking_id' => $b->id, 'type' => 'service', 'amount' => 2]);
    }

    public function test_return_wb_triggers_maintenance_when_threshold_exceeded(): void
    {
        Event::fake([ToolReturned::class]);
        $m = $this->member();
        $t = Tool::create([
            'name'                        => 'HeavyMower',
            'status'                      => 'available',
            'usage_status'                => 'high',
            'total_usage_hours'           => 98, // 2 more hours → crosses 100
            'maintenance_threshold_hours' => 100,
        ]);
        $b = Booking::create([
            'tool_id'    => $t->id,
            'member_id'  => $m->id,
            'start_time' => now()->subHours(3),
            'end_time'   => now()->addHour(),
            'status'     => 'active',
            'qr_token'   => \Illuminate\Support\Str::uuid(),
            'cleaned_at' => now(),
        ]);

        $this->service->return_tool(['booking_id' => $b->id]);

        $this->assertDatabaseHas('tools', ['id' => $t->id, 'status' => 'maintenance']);
    }

    /* ══════════════════════════════════════════════════════════
       WHITE-BOX: reportDamage()
       Paths: booking missing | low (no penalty) |
              medium (service penalty) | high (fine + maintenance)
       ══════════════════════════════════════════════════════════ */

    public function test_damage_wb_booking_not_found(): void
    {
        $r = $this->service->reportDamage(['booking_id' => 99999, 'severity' => 'medium']);
        $this->assertFalse($r->success);
    }

    public function test_damage_wb_low_severity_no_penalty(): void
    {
        $m = $this->member();
        $t = $this->tool('Trowel');
        $b = $this->activeBooking($t, $m);

        $r = $this->service->reportDamage(['booking_id' => $b->id, 'severity' => 'low']);

        $this->assertTrue($r->success);
        $this->assertEquals(0, Penalty::where('booking_id', $b->id)->count());
    }

    public function test_damage_wb_medium_severity_service_penalty(): void
    {
        $m = $this->member();
        $t = $this->tool('Shears');
        $b = $this->activeBooking($t, $m);

        $this->service->reportDamage(['booking_id' => $b->id, 'severity' => 'medium', 'reason' => 'Handle cracked']);

        $this->assertDatabaseHas('penalties', ['booking_id' => $b->id, 'type' => 'service', 'amount' => 3]);
    }

    public function test_damage_wb_high_severity_fine_and_maintenance(): void
    {
        $m = $this->member();
        $t = $this->tool('Chainsaw');
        $b = $this->activeBooking($t, $m);

        $this->service->reportDamage(['booking_id' => $b->id, 'severity' => 'high', 'reason' => 'Blade broken']);

        $this->assertDatabaseHas('penalties', ['booking_id' => $b->id, 'type' => 'fine', 'amount' => 50]);
        $this->assertDatabaseHas('tools', ['id' => $t->id, 'status' => 'maintenance']);
    }

    /* ══════════════════════════════════════════════════════════
       BLACK-BOX: book_tool() — boundary inputs
       ══════════════════════════════════════════════════════════ */

    // Boundary: duration_hours = 1 (minimum)
    public function test_book_bb_minimum_duration(): void
    {
        Event::fake([ToolBooked::class]);
        $m = $this->member();
        $this->tool('Watering Can');

        $r = $this->service->book_tool(['member_id' => $m->id, 'tool_name' => 'Watering Can', 'duration_hours' => 1]);

        $this->assertTrue($r->success);
    }

    // Boundary: duration_hours = 24 (large but valid)
    public function test_book_bb_large_duration(): void
    {
        Event::fake([ToolBooked::class]);
        $m = $this->member();
        $this->tool('Wheelbarrow');

        $r = $this->service->book_tool(['member_id' => $m->id, 'tool_name' => 'Wheelbarrow', 'duration_hours' => 24]);

        $this->assertTrue($r->success);
    }

    /* ══════════════════════════════════════════════════════════
       BLACK-BOX: calculatePriority() — penalty score boundaries
       ══════════════════════════════════════════════════════════ */

    // No penalties → max score (100)
    public function test_priority_bb_no_penalties_max_score(): void
    {
        $m = $this->member();
        $score = $this->service->calculatePriority($m->id);
        $this->assertEquals(100, $score);
    }

    // Enough penalties to drop below 0 → clamped to 0
    public function test_priority_bb_many_penalties_clamped_to_zero(): void
    {
        $m = $this->member();
        $t = $this->tool('Ladder');
        $b = $this->activeBooking($t, $m);

        // 3 × fine (20pts each) = 60pts deducted → score = 40
        // 5 × fine = 100pts → score clamped to 0
        for ($i = 0; $i < 5; $i++) {
            Penalty::create([
                'member_id'  => $m->id,
                'booking_id' => $b->id,
                'type'       => 'fine',
                'amount'     => 50,
                'status'     => 'pending',
            ]);
        }

        $score = $this->service->calculatePriority($m->id);
        $this->assertEquals(0, $score);
    }
}