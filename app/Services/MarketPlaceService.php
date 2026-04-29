<?php

namespace App\Services;

use App\Models\Listing;
use App\Contracts\Marketplace\MarketplaceServiceInterface as Market;

class MarketPlaceService extends BaseService implements Market {

   function createListing(array $data) : array
   {
     try {
        Listing::create($data);
        return $this->success([], "Listing added"); 
     }
     catch (\Throwable $e) {
          return $this->error($e->getMessage());
     }
  } 
 
   function createTrade(array $data) : array
   {
        return $this->success([], "NO IMPLMENTATION YET");
   }

   function askQuestion(array $data) : array
   {
        return $this->success([], "NO IMPLMENTATION YET");
   }

   function answerQuestion(array $data): array
   {
        return $this->success([], "NO IMPLMENTATION YET");
   }

}
