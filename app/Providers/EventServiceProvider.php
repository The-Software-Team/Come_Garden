<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\SeedDeposited::class => [
            \App\Listeners\LogSeedDeposit::class,
        ],

        \App\Events\SeedWithdrawn::class => [
            \App\Listeners\LogSeedWithdraw::class,
        ],
    ];
}