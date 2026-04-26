<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\RentalApplication;

use App\Contracts\Rental\RentalServiceInterface;


class RentalService implements RentalServiceInterface {
    public function  apply(array $data) {
      return DB::transaction(function () use ($data) {

        # 1. Validation
        $member = Member::findorFail($data['member_id']);

        # 2. business rules

        # 3. Logic
        $application = RentalApplication::create([
            'member_id' => $data['member_id'],
            'plot_id'   => $data['plot_id'],
            'share'     => $data['share'] ?? 0,
            'auto_renew' => $data['auto_renew'] ?? false,
            'status'    => 'pending',
        ]);

        # 4. Event
        // event(new SeedDeposited(
        //     memberId: $member->id,
        //     batchId: $batch->id,
        //     credits: $credits
        // ));

        return [
            'plot_id' => $data['plot_id'],
            'share' => "full" ? $data['share'] == 1 : $data['share'],
            'status' => $application->status 
        ];
    });

}
    public function approve(int $applicationId) {
        pass;
    }

    public function processWaitlist(int $plotId) {
        pass;
    }
}