<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Models\Member;
use App\Models\Plot;
use App\Models\RentalApplication;


use App\Services\RentalService;
use App\Events\Rental\RentalApplicationSubmitted;

class RentalServiceTest extends TestCase
{
    public function test_member_can_apply_for_rental()
    {
        $member = Member::findOrFail(1);

        $plot = Plot::factory()->create();

        $service = app(RentalService::class);

        $result = $service->apply([
            'member_id' => $member->id,
            'plot_id' => $plot->id,
            'share' => 1,
            'auto_renew' => false,
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('application_id', $result['data']);

        $this->assertDatabaseHas('rental_applications', [
            'member_id' => $member->id,
            'plot_id' => $plot->id,
            'share' => 1,
            'auto_renew' => 0,
            'status' => 'pending',
        ]);
    }
 }