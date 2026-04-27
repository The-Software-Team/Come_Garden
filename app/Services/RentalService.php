<?php

namespace App\Services;

use App\Contracts\Rental\RentalServiceInterface;
use App\Events\Rental\RentalApplicationSubmitted;

use App\Models\Member;
use App\Models\Plot;
use App\Models\RentalApplication;

class RentalService extends BaseService implements RentalServiceInterface
{
    public function apply(array $data): array
    {
            ## 1. Explicit Validation
            $member = Member::findOrFail($data['member_id']);
            $plot   = Plot::findOrFail($data['plot_id']);

            ## 2. Business Rules
            # a. share of application must be either 1 or 0.5
            $share = $data['share'];
            if (!in_array($share, [1, 0.5], true)) {
                return $this->error("Share must be 0.5 or 1");
            }
            # b. member may provide auto-renew option
            $auto_renew = $data['auto_renew'] ?? false;
            
            # core function
            $application = RentalApplication::create([
                'member_id' => $member->id,
                'plot_id' => $plot->id,
                'share' => $share,
                'auto_renew' => $auto_renew,
                'status' => 'pending',
            ]);
        
            ## 3. Events & Returns 
            // event(new \App\Events\Rental\RentalApplicationSubmitted($member->id, $plot->id, $share));

            return $this->success(['application_id' => $application->id], "Rental application submitted successfully");
    }
    public function rentPlot(int $plotId, int $seasonId): array 
    {
        pass;
    }

    public function endRentals(int $plotId, int $newSeasonId): array 
    {
        pass;
    }
}


