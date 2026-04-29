<?php

namespace App\Services;

use App\Models\Listing;
use App\Contracts\Marketplace\MarketplaceServiceInterface as Market;

class MarketPlaceService implements Market {

   function createListing(array $data) : array
   {
     Listing::create($data);
     return [
     'message' => 'Listing Created Successfully',
     'success' => True
     ];
  } 
 
   function createTrade(array $data) : array
   {
        return ['message' => "NO IMPLMENTATION YET"];
   }

   function askQuestion(array $data) : array
   {
        return ['message' => "NO IMPLMENTATION YET"];
   }

   function answerQuestion(array $data): array
   {
        return ['message' => "NO IMPLMENTATION YET"];
   }

}
