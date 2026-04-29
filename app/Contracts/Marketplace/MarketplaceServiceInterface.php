<?php

namespace App\Contracts\Marketplace;

interface MarketplaceServiceInterface
{
    public function createListing(array $data) : array;

    public function createTrade(array $data)   : array;

    public function askQuestion(array $data)   : array;

    public function answerQuestion(array $data) : array;
}