<?php

namespace App\Contracts\Marketplace;

interface MarketplaceServiceInterface
{
    public function createListing(array $data);

    public function createTrade(array $data);

    public function askQuestion(array $data);

    public function answer(array $data);
}