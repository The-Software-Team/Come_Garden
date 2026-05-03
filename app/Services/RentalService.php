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
        return ['message' => "NO IMPLMENTATION YET"];
    }

    public function endRentals(int $plotId, int $newSeasonId): array 
    {
        return ['message' => "NO IMPLMENTATION YET"];
    }
}


