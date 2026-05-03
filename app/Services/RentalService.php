<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Contracts\Rental\RentalServiceInterface;
use App\Models\Member;
use App\Models\Plot\Plot;
use App\Models\RentalApplication;

class RentalService implements RentalServiceInterface
{
    public function apply(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $member = Member::findOrFail($data['member_id']);
            $plot   = Plot::findOrFail($data['plot_id']);

            $share = $data['share'];
            if (!in_array($share, ['1', '0.5'], true)) {
                throw new \DomainException('Share is not valid');
            }
            $auto_renew = $data['auto_renew'] ?? false;

            $application = RentalApplication::create([
                'member_id' => $member->id,
                'plot_id' => $plot->id,
                'share' => $share,
                'auto_renew' => $auto_renew,
                'status' => 'pending',
            ]);

            // event(new \App\Events\Rental\RentalApplicationSubmitted($member->id, $plot->id, $share));
            return [
                'success' => True,
                'message' => 'Application Accepted'
            ];
        });

    }

    public function rentPlot(int $plotId, int $seasonId): array
    {
        return DB::transaction(function () use ($plotId, $seasonId) {

            $plot = Plot::with([
                'rentalApplications.member',
                'rentals.participants'
            ])->findOrFail($plotId);

            $rental = $plot->rentals()
                ->where('season_id', $seasonId)
                ->where('status', 'active')
                ->first();


            if (!$rental) {
                $rental = $plot->rentals()->create([
                    'season_id' => $seasonId,
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addMonths(3), // adjust per season logic
                ]);
            }

            $currentShare = $rental->participants()->sum('share');
            $remainingShare = 1.0 - $currentShare;
            
            if ($remainingShare <= 0) {
                return [
                    'success' => true,
                    'message' => 'Plot already fully rented',
                ];
            }
            $applications = RentalApplication::where('plot_id', $plotId)
                ->where('status', 'pending')
                ->orderByDesc('created_at') // later: score
                ->get();

            $approved = 0;
            $waitlisted = 0;
            $rejected = 0;

            foreach ($applications as $app) {

                if ($remainingShare <= 0) break;

                $member = $app->member;
                $wallet = $member->getWallet('main');

                if ($app->share > $remainingShare) {
                    $app->update(['status' => 'waitlisted']);
                    $waitlisted++;
                    continue;
                }

                $alreadyParticipant = $rental->participants()
                    ->where('member_id', $member->id)
                    ->exists();

                if ($alreadyParticipant) {
                    $app->update(['status' => 'rejected']);
                    $rejected++;
                    continue;
                }

                $cost = $this->calculateRent($plot, $member);

                if ($wallet->balance < $cost) {
                    $app->update(['status' => 'rejected']);
                    $rejected++;
                    continue;
                }

                $wallet->decrement('balance', $cost);

                $rental->participants()->create([
                    'member_id' => $member->id,
                    'share' => $app->share,
                    'cost' => $cost,
                    'auto_renew' => $app->auto_renew,
                    'status' => 'active',
                    'start_date' => $rental->start_date,
                    'end_date' => $rental->end_date,
                ]);

                $remainingShare -= $app->share;
                $approved++;

                $app->update(['status' => 'approved']);
            }

            return [
                'success' => true,
                'message' => 'Rental processed successfully',
                'data' => [
                    'approved' => $approved,
                    'waitlisted' => $waitlisted,
                    'rejected' => $rejected,
                    'remaining_share' => $remainingShare,
                ]
            ];
        });
    }

    public function endRentals(int $plotId, int $newSeasonId): array 
    {
        return ['message' => "NO IMPLMENTATION YET"];
    }


    ## helpers
    private function calculateRent(Plot $plot, Member $member): float
    {
        $base = config('rental.pricing')[$plot->size] ?? 100;

        $soilFactor = config('rental.soil_modifier')[$plot->soil_quality] ?? 1;

        $discount = match ($member->type) {
            'premium' => 0.9,
            'vip' => 0.8,
            default => 1,
        };

        $tierFactor = $this->getMemberTierFactor($member);

        return $base * $soilFactor * $discount * $tierFactor;
    }

    private function getMemberTierFactor(Member $member): float
    {
        $count = $member->rentalParticipations()->count();

        return match (true) {
            $count < 2 => 1.0,
            $count < 5 => 0.9,
            default => 0.8,
        };
    }
}


