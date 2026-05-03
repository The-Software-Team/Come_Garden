<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\Rental\RentalServiceInterface;
use App\Services\RentalService;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Services\SeedBankService;

use App\Contracts\Wallet\WalletServiceInterface;
use App\Services\WalletService;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;
use App\Services\ToolLibraryService;

use App\Contracts\Marketplace\MarketplaceServiceInterface;
use App\Services\MarketPlaceService;

use App\Contracts\Plot\PlotServiceInterface;
use App\Services\PlotService;

// ...



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SeedBankServiceInterface::class, SeedBankService::class);
        $this->app->bind(WalletServiceInterface::class, WalletService::class);
        
        $this->app->bind(PlotServiceInterface::class, PlotService::class);
        $this->app->bind(RentalServiceInterface::class, RentalService::class);
        $this->app->bind(ToolLibraryServiceInterface::class, ToolLibraryService::class);
        $this->app->bind(MarketplaceServiceInterface::class, MarketPlaceService::class);
    }

    public function boot(): void
    {
        //
    }
}
