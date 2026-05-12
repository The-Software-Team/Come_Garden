<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Services\SeedBankService;
use App\Contracts\Wallet\WalletServiceInterface;
use App\Models\Member;
use App\Models\SeedBatch;
use App\Models\InventoryItem;
use App\Events\SeedBank\SeedWithdrawn;

/**
 * php artisan test tests/Feature/Services/SeedBankServiceTest.php
 */
class SeedBankServiceTest extends TestCase
{
    use RefreshDatabase;

    private SeedBankService $service;
    private WalletServiceInterface $wallet;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wallet  = $this->createMock(WalletServiceInterface::class);
        $this->service = new SeedBankService($this->wallet);
    }

    /* ── helpers ───────────────────────────────────────────── */

    private function member(array $extra = []): Member
    {
        return Member::create(array_merge([
            'name'     => 'Test User',
            'email'    => 'u' . uniqid() . '@test.com',
            'password' => bcrypt('pass'),
        ], $extra));
    }

    private function seedWallet(Member $m, float $balance = 50): void
    {
        $m->wallets()->create(['type' => 'seedbank', 'balance' => $balance]);
    }

    private function marketBatch(string $type, int $qty, int $age = 2, int $viability = 85): SeedBatch
    {
        return SeedBatch::create([
            'owner_type' => 'market',
            'owner_id'   => 1,
            'seed_type'  => $type,
            'quantity'   => $qty,
            'viability'  => $viability,
            'age'        => $age,
            'origin'     => 'Test Farm',
            'status'     => 'accepted',
        ]);
    }

    private function depositData(Member $m, array $override = []): array
    {
        return array_merge([
            'owner_id'   => $m->id,
            'owner_type' => 'market',
            'seed_type'  => 'Tomato',
            'quantity'   => 10,
            'viability'  => 85,
            'age'        => 2,
            'origin'     => 'Local Farm',
        ], $override);
    }

    /* ══════════════════════════════════════════════════════════
       WHITE-BOX: deposit()
       Paths: member missing | inventory (no credits) |
              market low viability | market high viability
       ══════════════════════════════════════════════════════════ */

    // public function test_deposit_wb_member_not_found(): void
    // {
    //     $r = $this->service->deposit($this->depositData(
    //         (object)['id' => 99999], ['owner_id' => 99999]
    //     ));
    //     $this->assertFalse($r->success);
    // }

    public function test_deposit_wb_inventory_no_credits(): void
    {
        $m = $this->member();
        $this->wallet->expects($this->never())->method('credit');

        $r = $this->service->deposit($this->depositData($m, [
            'owner_type' => 'inventory',
            'viability'  => 90,
        ]));

        $this->assertTrue($r->success);
        $this->assertEquals(0, $r->data['credits_added']);
    }

    public function test_deposit_wb_market_low_viability_base_credits(): void
    {
        $m = $this->member();
        // quantity=10, viability=79 → credits = 10 (no doubling)
        $this->wallet->expects($this->once())->method('credit')
            ->with($this->anything(), 10, 'seed_deposit');

        $r = $this->service->deposit($this->depositData($m, ['viability' => 79]));

        $this->assertTrue($r->success);
        $this->assertEquals(10, $r->data['credits_added']);
    }

    public function test_deposit_wb_market_high_viability_doubles_credits(): void
    {
        $m = $this->member();
        // quantity=10, viability=80 → credits = 20
        $this->wallet->expects($this->once())->method('credit')
            ->with($this->anything(), 20, 'seed_deposit');

        $r = $this->service->deposit($this->depositData($m, ['viability' => 80]));

        $this->assertTrue($r->success);
        $this->assertEquals(20, $r->data['credits_added']);
    }

    /* ══════════════════════════════════════════════════════════
       WHITE-BOX: withdraw()
       Paths: member missing | low credits | no batches | success
       ══════════════════════════════════════════════════════════ */

    public function test_withdraw_wb_member_not_found(): void
    {
        $r = $this->service->withdraw(['member_id' => 99999, 'seed_type' => 'X', 'quantity' => 1]);
        $this->assertFalse($r->success);
    }

    public function test_withdraw_wb_insufficient_credits(): void
    {
        $m = $this->member();
        $this->seedWallet($m, 3);

        $r = $this->service->withdraw(['member_id' => $m->id, 'seed_type' => 'Tomato', 'quantity' => 10]);

        $this->assertFalse($r->success);
        $this->assertStringContainsStringIgnoringCase('insufficient', $r->message);
    }

    public function test_withdraw_wb_no_market_batches(): void
    {
        $m = $this->member();
        $this->seedWallet($m, 50);

        $r = $this->service->withdraw(['member_id' => $m->id, 'seed_type' => 'GhostSeed', 'quantity' => 5]);

        $this->assertFalse($r->success);
    }

    // public function test_withdraw_wb_success_fifo_event_fired(): void
    // {
    //     Event::fake([SeedWithdrawn::class]);
    //     $m = $this->member();
    //     $this->seedWallet($m, 50);
    //     $this->marketBatch('Tomato', 20);
    //     $this->wallet->method('debit');

    //     $r = $this->service->withdraw(['member_id' => $m->id, 'seed_type' => 'Tomato', 'quantity' => 5]);

    //     $this->assertTrue($r->success);
    //     $this->assertEquals(5, $r->data['taken']);
    //     Event::assertDispatched(SeedWithdrawn::class);
    // }

    /* ══════════════════════════════════════════════════════════
       BLACK-BOX: deposit() — viability boundary (79 / 80 / 100)
       ══════════════════════════════════════════════════════════ */

    public function test_deposit_bb_viability_79_no_doubling(): void
    {
        $m = $this->member();
        $this->wallet->method('credit');
        $r = $this->service->deposit($this->depositData($m, ['quantity' => 5, 'viability' => 79]));
        $this->assertEquals(5, $r->data['credits_added']);
    }

    public function test_deposit_bb_viability_80_doubles(): void
    {
        $m = $this->member();
        $this->wallet->method('credit');
        $r = $this->service->deposit($this->depositData($m, ['quantity' => 5, 'viability' => 80]));
        $this->assertEquals(10, $r->data['credits_added']);
    }

    public function test_deposit_bb_viability_100_upper_bound(): void
    {
        $m = $this->member();
        $this->wallet->method('credit');
        $r = $this->service->deposit($this->depositData($m, ['quantity' => 5, 'viability' => 100]));
        $this->assertTrue($r->success);
        $this->assertEquals(10, $r->data['credits_added']);
    }

    /* ══════════════════════════════════════════════════════════
       BLACK-BOX: withdraw() — quantity vs balance boundary
       ══════════════════════════════════════════════════════════ */

    public function test_withdraw_bb_exact_balance_succeeds(): void
    {
        Event::fake([SeedWithdrawn::class]);
        $m = $this->member();
        $this->seedWallet($m, 10);
        $this->marketBatch('Carrot', 20);
        $this->wallet->method('debit');

        $r = $this->service->withdraw(['member_id' => $m->id, 'seed_type' => 'Carrot', 'quantity' => 10]);

        $this->assertTrue($r->success);
    }

    public function test_withdraw_bb_one_over_balance_fails(): void
    {
        $m = $this->member();
        $this->seedWallet($m, 10);

        $r = $this->service->withdraw(['member_id' => $m->id, 'seed_type' => 'Carrot', 'quantity' => 11]);

        $this->assertFalse($r->success);
    }

    // public function test_withdraw_bb_spans_two_batches_fifo(): void
    // {
    //     Event::fake([SeedWithdrawn::class]);
    //     $m = $this->member();
    //     $this->seedWallet($m, 50);
    //     $this->marketBatch('Basil', 5, age: 3); // older → consumed first
    //     $this->marketBatch('Basil', 5, age: 1);
    //     $this->wallet->method('debit');

    //     $r = $this->service->withdraw(['member_id' => $m->id, 'seed_type' => 'Basil', 'quantity' => 8]);

    //     $this->assertTrue($r->success);
    //     $this->assertEquals(8, $r->data['taken']);
    //     // Older batch should now be at 0
    //     $this->assertDatabaseHas('seed_batches', ['age' => 3, 'seed_type' => 'Basil', 'quantity' => 0]);
    // }
}