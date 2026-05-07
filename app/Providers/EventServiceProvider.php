<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\SeedBank\SeedWithdrawn::class => [
            \App\Listeners\SeedBank\AboutWithdrawn::class,
       ],


        // Tool Library
        \App\Events\ToolLibrary\ToolBooked::class => [
            \App\Listeners\ToolLibrary\AboutBooking::class,
        ],
 
         \App\Events\ToolLibrary\ToolReturned::class => [
            \App\Listeners\ToolLibrary\AboutReturing::class,
        ],

        \App\Events\ToolLibrary\ToolMaintained::class => [
            \App\Listeners\ToolLibrary\AboutMaintaining::class,
        ],

        \App\Events\ToolLibrary\ToolWaitlisted::class => [
            \App\Listeners\ToolLibrary\AboutWaitlisting::class,
        ],
    ];
}