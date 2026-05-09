<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\PlotController;
use App\Http\Controllers\RentalController;

use App\Http\Controllers\SeedBankController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\MarketController;

use App\Http\Controllers\Volunteer\VolunteerController;

use App\Http\Controllers\MemberSeedBankController;



// Route::middleware(['auth'])->group(function () {

//     // 1. Garden Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'index']);

//     // 2. Plot Management
//     Route::prefix('plots')->group(function () {
//         Route::get('/', [PlotController::class, 'index']); // map/viewer
//         Route::get('/{id}', [PlotController::class, 'show']); // details
//         Route::post('/report', [PlotController::class, 'reportInfection']);
//     });

//     // 3. Rentals
//     Route::prefix('rentals')->group(function () {
//         Route::get('/', [RentalController::class, 'index']); // dashboard
//         Route::post('/apply', [RentalController::class, 'apply']);
//     });

//     // Admin rental panel
//     Route::middleware('admin')->prefix('admin/rentals')->group(function () {
//         Route::get('/', [RentalController::class, 'adminIndex']);
//         Route::post('/approve/{id}', [RentalController::class, 'approve']);
//     });

//     // 4. Tools
//     Route::prefix('tools')->group(function () {
//         Route::get('/', [ToolController::class, 'catalog']);
//         Route::post('/book', [ToolController::class, 'book']);
//         Route::get('/bookings', [ToolController::class, 'myBookings']);
//     });

//     Route::middleware('admin')->prefix('admin/tools')->group(function () {
//         Route::get('/', [ToolController::class, 'adminIndex']);
//     });

//     // 5. Seed Bank
//     Route::prefix('seeds')->group(function () {
//         Route::get('/', [SeedBankController::class, 'index']);
//         Route::post('/deposit', [SeedBankController::class, 'deposit']);
//         Route::post('/withdraw', [SeedBankController::class, 'withdraw']);
//     });

//     Route::middleware('admin')->prefix('admin/seeds')->group(function () {
//         Route::get('/', [SeedBankController::class, 'adminIndex']);
//     });

//     // 6. Volunteer System
//     Route::prefix('volunteer')->group(function () {
//         Route::get('/tasks', [VolunteerController::class, 'memberTasks']);
//         Route::post('/swap', [VolunteerController::class, 'swapRequest']);
//     });

//     Route::middleware('admin')->prefix('admin/volunteer')->group(function () {
//         Route::post('/shift', [VolunteerController::class, 'createShift']);
//         Route::get('/assignments', [VolunteerController::class, 'assignments']);
//     });

//     // 7. Marketplace
//     Route::prefix('marketplace')->group(function () {
//         Route::get('/', [MarketController::class, 'index']);
//         Route::post('/listing', [MarketController::class, 'store']);
//         Route::get('/trades', [MarketController::class, 'trades']);
//         Route::get('/qa', [MarketController::class, 'qa']);
//         Route::post('/answer', [MarketController::class, 'answer']);
//     });
// });





use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'member'])->name('dashboard.member');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

# SeedBank
Route::prefix('seedbank')->name('seedbank.')->group(function () {
    Route::get('/', [SeedBankController::class, 'profile'])
        ->name('profile');

    Route::get('/market', [SeedBankController::class, 'market'])
        ->name('browse');

    Route::get('deposit', [SeedBankController::class, 'depositForm'])
        ->name('deposit');

    Route::post('deposit', [SeedBankController::class, 'store'])
        ->name('deposit.store');

    Route::post('withdraw', [SeedBankController::class, 'withdraw'])
        ->name('withdraw');
});


## Admin
Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard.admin');
    
    Route::get('/seedbank', [AdminController::class, 'admin_seedbank']);
    Route::post('/seedbank', [AdminController::class, 'admin_seedbank_store'])->name("admin_seedbank.store");


    Route::get('tools', [ToolController::class, 'admin_index'])->name('admin.tools');
    Route::post('tools', [ToolController::class, 'store'])->name('tools.store');

    Route::get('/volunteer' , [VolunteerController::class, 'adminIndex'])
        ->name('admin.volunteer');

    Route::post('/volunteer', [VolunteerController::class, 'createShift'])
        ->name('admin.volunteer.shift.create');

    Route::get('/volunteer/alerts', [VolunteerController::class, 'adminAlerts'])
        ->name('admin.volunteer.alerts');

    Route::get('/volunteer/incidents', [VolunteerController::class, 'adminIncidents'])
        ->name('admin.volunteer.incidents');

    Route::get('/volunteer/proposals', [VolunteerController::class, 'fundProposals'])
        ->name('admin.volunteer.proposals');
  
    Route::get('/market', [MarketController::class, 'index'])->name('admin.marketplace.index');

    Route::get('plots', [PlotController::class, 'admin_index']);
    Route::post('rentals', [RentalController::class, 'rent_plot']);
    
});

## Tool Library
Route::prefix('tools')->group(function () {
    Route::get('/', [ToolController::class, 'index'])->name('tools');
    Route::post('/', [ToolController::class, 'store']); // add_tool
    Route::post('/book', [ToolController::class, 'book'])->name('tools.book');
    Route::post('/return', [ToolController::class, 'return'])->name('tools.return');
    Route::post('/damage', [ToolController::class, 'reportDamage'])->name('tools.damage');

    Route::post('/maintain', [ToolController::class, 'maintainTool'])->name('tools.maintain');

    Route::get('/scan/{token}', [ToolController::class, 'scan'])
        ->name('tools.scan');

    Route::post('/scan', [ToolController::class, 'processScan'])
        ->name('tools.scan.process');

    Route::post('/waitlist/process', [ToolController::class, 'processWaitlist'])
        ->name('tools.waitlist.process');
});

## Volunteer System
Route::prefix('volunteer')->group(function () {
    Route::get('/', [VolunteerController::class, 'index'])->name('volunteer');

    // F23: Task Difficulty
    Route::post('/difficulty', [VolunteerController::class, 'calculateDifficulty'])
        ->name('volunteer.difficulty');

    // F24: Shift Balance
    Route::get('/shift/{shift}/balance', [VolunteerController::class, 'shiftBalance'])
        ->name('volunteer.shift.balance');

    // F25: Service Hours
    Route::get('/hours', [VolunteerController::class, 'serviceLedger'])
        ->name('volunteer.hours');
    Route::post('/hours/log', [VolunteerController::class, 'logHours'])
        ->name('volunteer.hours.log');

    // F26: Shift Assignment & Swaps
    Route::post('/shift/assign', [VolunteerController::class, 'assign'])
        ->name('volunteer.shift.assign');
    Route::post('/shift/complete', [VolunteerController::class, 'complete'])
        ->name('volunteer.shift.complete');
    Route::post('/swap/request', [VolunteerController::class, 'swapRequest'])
        ->name('volunteer.swap.request');
    Route::post('/swap/{swap}/respond', [VolunteerController::class, 'respondSwap'])
        ->name('volunteer.swap.respond');

    // F29: Security Access
    Route::post('/access/log', [VolunteerController::class, 'logAccess'])
        ->name('volunteer.access.log');
    Route::get('/access/logs', [VolunteerController::class, 'accessLogs'])
        ->name('volunteer.access.logs');

    // F30: Mentorship
    Route::post('/mentor/pair', [VolunteerController::class, 'pairMentor'])
        ->name('volunteer.mentor.pair');
    Route::post('/mentor/pair-manual', [VolunteerController::class, 'createPairManually'])
        ->name('volunteer.mentor.pair-manual');

    // F31: Incident Reporting
    Route::post('/incident/report', [VolunteerController::class, 'reportIncident'])
        ->name('volunteer.incident.report');
    Route::post('/incident/{incident}/update', [VolunteerController::class, 'updateIncident'])
        ->name('volunteer.incident.update');

    // F32: Weather
    Route::post('/shift/{shift}/weather', [VolunteerController::class, 'evaluateWeather'])
        ->name('volunteer.shift.weather');

    // F28: Fund Proposals
    Route::get('/proposals', [VolunteerController::class, 'fundProposals'])
        ->name('volunteer.proposals');
    Route::post('/proposals/create', [VolunteerController::class, 'createProposal'])
        ->name('volunteer.proposals.create');
    Route::post('/proposals/{proposal}/vote', [VolunteerController::class, 'castVote'])
        ->name('volunteer.proposals.vote');
    Route::post('/proposals/{proposal}/close', [VolunteerController::class, 'closeProposal'])
        ->name('volunteer.proposals.close');

    // F27: Emergency Alerts
    Route::post('/alert/broadcast', [VolunteerController::class, 'broadcastAlert'])
        ->name('volunteer.alert.broadcast');
    Route::post('/alert/{alert}/resolve', [VolunteerController::class, 'resolveAlert'])
        ->name('volunteer.alert.resolve');
});


//    MARKETPLACE ROUTES
Route::prefix('marketplace')->name('marketplace.')->group(function () {

    // Pages
    Route::get('/market',  [MarketController::class, 'market'])->name('market');
    Route::get('/profile', [MarketController::class, 'profile'])->name('profile');

    // Listings
    Route::post('/listings', [MarketController::class, 'storeListing'])->name('listings.store');

    // Trades
    Route::post('/trades',       [MarketController::class, 'storeTrade'])->name('trades.store');
    Route::post('/flash/claim',  [MarketController::class, 'claimFlash'])->name('flash.claim');

    // Q&A
    Route::post('/questions', [MarketController::class, 'storeQuestion'])->name('questions.store');
    Route::post('/answers',   [MarketController::class, 'storeAnswer'])->name('answers.store');

    // Quality ratings
    Route::post('/ratings', [MarketController::class, 'storeRating'])->name('ratings.store');

    // Canning
    Route::post('/canning',      [MarketController::class, 'storeCanningSession'])->name('canning.store');
    Route::post('/canning/join', [MarketController::class, 'joinCanningSession'])->name('canning.join');

    // Allergen profile
    Route::post('/allergens', [MarketController::class, 'updateAllergens'])->name('allergens.update');
});



## Plot
Route::get('/plots', [PlotController::class, 'market']);
Route::get('/plots/{plot}', [PlotController::class, 'ownerView'])->name('plots.show');
// Route::get('/my-plots/{plot}', [PlotController::class, 'ownerView']);


Route::prefix('plots/{plot}')->group(function () {

    Route::post('/plant', [PlotController::class, 'plant'])
        ->name('plots.plant');

    Route::post('/infection', [PlotController::class, 'reportInfection'])
        ->name('plots.reportInfection');

    Route::post('/fertilize', [PlotController::class, 'fertilize'])
        ->name('plots.fertilize');
});

#

## Rentals
Route::prefix('rental')->group(function () {
    Route::get('apply', [RentalController::class, 'create'])->name('rental.create');
    Route::post('apply', [RentalController::class, 'store'])->name('rental.store');

    Route::post('rent', [RentalController::class, 'rent'])->name('rental.rent');
    Route::post('rent/run', [RentalController::class, 'run'])->name('rental.run');
});
});

require __DIR__.'/auth.php';
