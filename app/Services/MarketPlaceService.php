<?php

namespace App\Services;

use App\Models\Market\Listing;
use App\Models\Market\Trade;
use App\Models\Market\Question;
use App\Models\Market\Answer;

use App\Contracts\Marketplace\MarketplaceServiceInterface as Market;

class MarketPlaceService implements Market {

   public function createListing(array $data) : array
   {
     Listing::create($data);
     return [
     'message' => 'Listing Created Successfully',
     'success' => True
     ];
  } 
    
   public function createTrade(array $data): array
    {
        Trade::create($data);
        return [
            'success' => false,
            'message' => 'Not implemented yet'
        ];
    }

    public function askQuestion(array $data): array
    {
        Question::create($data);
        return [
            'success' => false,
            'message' => 'Not implemented yet'
        ];
    }

    public function answerQuestion(array $data): array
    {
        Answer::create($data);
        return [
            'success' => false,
            'message' => 'Not implemented yet'
        ];
    }
}
