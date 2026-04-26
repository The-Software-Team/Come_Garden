<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Member;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeedBankDepositTest extends TestCase
{
    # use RefreshDatabase;

    // public function test_seed_deposit_increases_wallet_balance()
    // {
    //     # choose a member by id 
    //     $member = Member::findOrFail(1);

    //     # the member's wallet
    //     $wallet = $member->wallets()
    //         ->where('type', 'seedbank')
    //         ->firstOrFail();

    //     $response = $this->postJson('seedbank/deposit', [
    //         'member_id' => $member->id,
    //         'seed_type' => 'wheat',
    //         'quantity' => 10,
    //         'viability' => 85,
    //         'origin' => 'local',
    //         'age' => 2
    //     ]);

    //     $response->assertStatus(200);


    //     $this->assertDatabaseHas('wallets', [
    //         'id' => $wallet->id,
    //         'balance' => 20 // 10 * 2 (viability >= 80)
    //     ]);

    //     $this->assertDatabaseHas('seed_batches', [
    //         'seed_type' => 'wheat',
    //         'quantity' => 10
    //     ]);

    //     $this->assertDatabaseHas('transactions', [
    //         'wallet_id' => $wallet->id,
    //         'amount' => 20,
    //         'type' => 'credit'
    //     ]);
    // }
}