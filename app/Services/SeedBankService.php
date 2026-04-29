<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

use App\Models\Member;
use App\Models\SeedBatch;
use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Contracts\Wallet\WalletServiceInterface;

class SeedBankService extends BaseService implements SeedBankServiceInterface
{
    public function __construct(
        private WalletServiceInterface $walletService
    ) {}

    public function deposit(array $data) : array
    {
        try {
            $result = $this->transaction(function () use ($data) {
    
                $member = Member::findOrFail($data['member_id']);
                
                $batch = SeedBatch::create([
                    'member_id' => $member->id,
                    'seed_type' => $data['seed_type'],
                    'quantity'  => $data['quantity'],
                    'viability' => $data['viability'],
                    'origin'    => $data['origin'] ?? null,
                    'age'       => $data['age'] ?? null,
                    'status'    => 'accepted',
                ]);
    
                $credits = $data['quantity'];
                if ($data['viability'] >= 80) {
                    $credits *= 2;
                }
    
                $this->walletService->credit(
                    $member,
                    $credits,
                    'seed_deposit'
                );
                
                # EVENT

                // TODO: This event shows inconsistency, if transaction failed. 
                // event(new \App\Events\SeedBank\SeedDeposited(
                //     $member->id,
                //     $batch->id,
                //     $credits
                // ));
                // do this intead
                // SeedDeposited::dispatch(...)->afterCommit();
    
                return [
                    'batch_id' => $batch->id,
                    'credits_added' => $credits
                ];
            });
            
            Log::info("Deposit Service is Fine");
            return $this->success($result, 'Seeds deposited successfully');
    
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
}
    public function withdraw(array $data) : array
    {
         return $this->error("NO IMPLMENTATION YET"); 
    }
}