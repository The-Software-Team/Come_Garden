<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Member;

use App\Models\SeedBatch;
use App\Models\InventoryItem;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Contracts\Wallet\WalletServiceInterface;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

class SeedBankService implements SeedBankServiceInterface
{
    private const HIGH_QUALITY_THRESHOLD = 80;

    public function __construct(
        private WalletServiceInterface $walletService
    ) {}

    public function deposit(array $data) : array
    {
        return DB::transaction(function () use ($data) {
            $member = Member::findOrFail($data['owner_id']);
            
            $batch = SeedBatch::create([
                'owner_id' => $member->id,
                'owner_type' => $data['owner_type'],
                'seed_type' => $data['seed_type'],
                'quantity'  => $data['quantity'],
                'viability' => $data['viability'],
                'origin'    => $data['origin'] ?? null,
                'age'       => $data['age'] ?? null,
                'status'    => 'accepted'
            ]);

            $credits = 0;
            if($data['owner_type'] == "market") {
                $credits = $data['quantity'];
                if ($data['viability'] >= 80) {
                    $credits *= 2;
                }
        
                $this->walletService->credit(
                    $member,
                    $credits,
                    'seed_deposit'
                );
           };
           
            # EVENT
            // do this intead when you implmenet events
            // SeedDeposited::dispatch(...)->afterCommit();

            # Later we'll add a ServiceResult class
            return [
                'batch_id' => $batch->id,
                'credits_added' => $credits,
                'message' => 'Seed Depoited Successfully',
                'success' => True
                ];

        });
        }

    public function withdraw(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $member = Member::findOrFail($data['member_id']);
            $seedType = $data['seed_type'];
            $quantity = $data['quantity'];

            $wallet = $member->wallets->where('type', 'seedbank')->first();
            $availableCredits = $wallet->balance;

            if ($availableCredits < $quantity) {
                throw new \Exception("Insufficient seed credits.");
            }

            // GET BATCHES (oldest first)
            $batches = SeedBatch::where('owner_type', 'market')
                ->where('seed_type', $seedType)
                ->where('quantity', '>', 0)
                ->orderBy('age', 'desc')
                ->lockForUpdate()
                ->get();

            if ($batches->isEmpty()) {
                throw new \Exception("No seed batches available.");
            }

            $taken = 0;
            $result = [];

            foreach ($batches as $batch) {

                if ($taken >= $quantity) break;

                $available = $batch->quantity;
                $take = min($available, $quantity - $taken);

                $batch->quantity -= $take;
                $batch->save();

                $taken += $take;
                
                $result[] = [
                    'seed_type' => $seedType,
                    'quantity' => $take,
                    'age' => $batch->age,
                    'viability' => $batch->viability,
                    'origin'    => $batch->origin,
                ];
            }

            # remove 0 quantity market batches
            SeedBatch::where('owner_type', 'market')
                ->where('quantity', 0)
                ->delete();

            
            if ($taken > 0) {
                $avg_age = round(collect($result)->avg('age'), 1);
                $avg_viability = round(collect($result)->avg('viability'), 1);
                $origins = collect($result)
                    ->pluck('origin')
                    ->unique()
                    ->values();
                
                # create inventory batch
                $batch = SeedBatch::create([
                    'owner_type' => 'inventory',
                    'owner_id'   => $member->id,
                    'seed_type' => $seedType,
                    'quantity' => $taken,
                    'age'     => $avg_age,
                'viability' => $avg_viability,
                    'origin'   => $origins
                ]);
            }
            // DEBIT WALLET
            $this->walletService->debit(
                $member,
                $quantity,
                'seed_withdraw'
            );

            return [
                'success' => true,
                'taken' => $result,
                'credits_used' => $quantity,
                'message' => 'Seeds withdrawn successfully 🌾',
            ];
        });
    }

    public function addInventoryItem(array $data): void
    {
        InventoryItem::create([
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'reorder_threshold' => $data['threshold'],
        ]);
    }

    public function checkSeedHealth(): array
    {
        $alerts = [];

        $batches = SeedBatch::all();

        foreach ($batches as $batch) {

            if ($this->isExpired($batch)) {
                $alerts[] = [
                    'seed_type' => $batch->seed_type,
                    'status' => 'EXPIRED',
                    'batch_id' => $batch->id,
                ];
            }

            if ($this->needsTesting($batch)) {
                $alerts[] = [
                    'seed_type' => $batch->seed_type,
                    'status' => 'TEST_REQUIRED',
                    'batch_id' => $batch->id,
                ];
            }
        }
        return $alerts;
    }

    public function checkInventoryAlerts(): array
    {
        return InventoryItem::whereColumn('quantity', '<=', 'reorder_threshold')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'status' => 'REORDER_REQUIRED',
                'quantity' => $item->quantity,
            ])
            ->toArray();
    }

    // helpers
    private function isExpired(SeedBatch $batch): bool
    {
        return $batch->age > 365; // simple rule
    }
    
    private function needsTesting(SeedBatch $batch): bool
    {
        return $batch->viability < self::HIGH_QUALITY_THRESHOLD;
    }
}