<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\Log;
use App\Services\SeedBankService;

class SeedBankServiceTest extends TestCase
{
    // use RefreshDatabase;

    public function test_member_can_deposit_seeds()
    {
        // ARRANGE
        $member = \App\Models\Member::findOrFail(1);

        $balance = $member->wallets()->first()->balance ?? 0;
        $depositCredit = 200;

        $service = app(SeedBankService::class);

        $result = $service->deposit([
            'member_id' => $member->id,
            'seed_type' => 'corn',
            'quantity'  => 100,
            'viability' => 90,
            'origin'    => 'local',
            'age'       => 10
        ]);

        // ASSERT
        Log::info('Deposit Result:', $result);
        $this->assertTrue($result['success']);
        $this->assertTrue($balance + $depositCredit == $member->wallets()->first()->balance); // viabilit >= 80, balance += 200 
    }
}