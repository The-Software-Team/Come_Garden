<?php

namespace App\Services;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Contracts\Wallet\WalletServiceInterface;

use App\Services\BaseService;

use App\Models\Member;
use App\Models\SeedBatch;
use App\Models\InventoryItem;

use App\Events\SeedBank\SeedWithdrawn;

use App\Support\ServiceResult;

class SeedBankService extends BaseService implements SeedBankServiceInterface
{
    private const HIGH_QUALITY_THRESHOLD = 80;

    public function __construct(
        private WalletServiceInterface $walletService
    ) {}

    public function deposit(array $data) : ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {

                $member = Member::find($data['owner_id']);
                if(!$member)
                    return ServiceResult::failure("Member Not Found");

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


                // ratio 2 for 1 for high quality seeds.
                $credits = 0;
                if ($data['owner_type'] === 'market') {
                    $credits = $data['quantity'];
                    if ($data['viability'] >= self::HIGH_QUALITY_THRESHOLD) {
                        $credits *= 2;
                    }

                    $this->walletService->credit(
                        $member,
                        $credits,
                        'seed_deposit'
                    );
                }

                return ServiceResult::success([
                    'batch_id' => $batch->id,
                    'credits_added' => $credits,
                ], 'Seed Deposited Successfully');
            });        }

    public function withdraw(array $data): ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {

            $member = Member::find($data['member_id']);
            if (!$member)
                return ServiceResult::failure("Member NOt Found");

            $seedType = $data['seed_type'];
            $quantity = $data['quantity'];
            $wallet = $member->wallets->where('type', 'seedbank')->first();
            $availableCredits = $wallet->balance;

            if ($availableCredits < $quantity) 
                return ServiceResult::failure("Insufficient Seed Credits");

            $consumeResult = $this->consumeMarketBatches($seedType, $quantity);
            if ($consumeResult instanceof ServiceResult) {
                return $consumeResult; // failure
            }

            $taken = $consumeResult['taken'];
            $result = $consumeResult['breakdown'];          
        
            $avg_age = round(collect($result)->avg('age'), 1);
            $avg_viability = round(collect($result)->avg('viability'), 1);
            $origins = collect($result)
                ->pluck('origin')
                ->unique()
                ->values();
            
            ## create the batch in the member's inventory
            SeedBatch::create([
                'owner_type' => 'inventory',
                'owner_id'   => $member->id,
                'seed_type' => $seedType,
                'quantity' => $taken,
                'age'     => $avg_age,
                'viability' => $avg_viability,
                'origin'   => $origins
            ]);

            $this->walletService->debit(
                $member,
                $taken,
                'seed_withdraw'
            );
            event(new SeedWithdrawn($member->id, $taken));

            return ServiceResult::success([
                'taken' => $result,
                'credits_used' => $quantity,
            ], 'Seeds Withdrawn Successfully');
        });
    }

    public function addInventoryItem(array $data): ServiceResult
    {
        return $this->handleTransaction(function () use ($data) {
            InventoryItem::create([
                'name' => $data['name'],
                'quantity' => $data['quantity'],
                'reorder_threshold' => $data['threshold'],
            ]);

            return ServiceResult::success([
                'item_name' => $data['name'],
                'quantity' => $data['quantity']
            ], 'Item Added Successfully');
        }); 
    }    

    public function checkseedhealth(): serviceresult
    {
        $alerts = seedbatch::all()->flatmap(function ($batch) {
            $batchalerts = [];
    
            if ($this->isexpired($batch)) {
                $batchalerts[] = [
                    'seed_type' => $batch->seed_type,
                    'status' => 'expired',
                    'batch_id' => $batch->id,
                ];
            }
    
            if ($this->needstesting($batch)) {
                $batchalerts[] = [
                    'seed_type' => $batch->seed_type,
                    'status' => 'test_required',
                    'batch_id' => $batch->id,
                ];
            }
    
            return $batchalerts;
        })->values();
    
        return serviceresult::success([
            'alerts' => $alerts
        ]);
    }

    public function checkinventoryalerts(): serviceresult
    {
        $alerts = inventoryitem::wherecolumn('quantity', '<=', 'reorder_threshold')
            ->get()
            ->map(fn ($item) => [
                'name' => $item->name,
                'status' => 'reorder_required',
                'quantity' => $item->quantity,
            ])
            ->values()
            ->toarray();

            return serviceresult::success([
                'alerts' => $alerts
            ]);
    }

    // helpers
    private function consumeMarketBatches(string $seedType, int $quantity): array|ServiceResult
    {
        // GET BATCHES (oldest first FIFO)
        $batches = SeedBatch::where('owner_type', 'market')
            ->where('seed_type', $seedType)
            ->where('quantity', '>', 0)
            ->orderBy('age', 'desc')
            ->lockForUpdate()
            ->get();

        if ($batches->isEmpty()) {
            return ServiceResult::failure("Market does not have {$seedType}");
        }

        $totalAvailable = $batches->sum('quantity');
        if ($totalAvailable < $quantity) {
            return ServiceResult::failure("Not enough {$seedType} in market");
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
                'origin' => $batch->origin,
            ];
        }

        if (!$taken) {
            return ServiceResult::failure("Something is wrong with SeedBatches");
        }

        return [
            'taken' => $taken,
            'breakdown' => $result,
        ];
    }

    private function isExpired(SeedBatch $batch): bool
    {
        return $batch->age > 5; // simple rule 5 years
    }
    
    private function needsTesting(SeedBatch $batch): bool
    {
        return $batch->viability < self::HIGH_QUALITY_THRESHOLD;
    }
}