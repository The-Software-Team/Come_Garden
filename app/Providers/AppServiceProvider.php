<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

use App\Contracts\Rental\RentalServiceInterface;
use App\Services\RentalService;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Services\SeedBankService;

use App\Contracts\Wallet\WalletServiceInterface;
use App\Services\WalletService;
// ...



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RentalServiceInterface::class, RentalService::class);
        $this->app->bind(SeedBankServiceInterface::class, SeedBankService::class);
        $this->app->bind(WalletServiceInterface::class, WalletService::class);
        // ...
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
