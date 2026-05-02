<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\PlotController;
use App\Http\Controllers\RentalController;

use App\Http\Controllers\SeedBankController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\VolunteerController;





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





#########################################################################



Route::middleware(['auth'])->group(function () {


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

## Plot

// Plot viewer
Route::get('/plots', [PlotController::class, 'market']);
Route::get('/plots/{plot}', [PlotController::class, 'show'])->name('plots.show');
Route::get('/my-plots/{plot}', [PlotController::class, 'ownerView']);

Route::get('/admin/plots', [PlotController::class, 'index'])->middleware('admin');

// // Actions
// Route::post('/plots/{plot}/plant', [PlotController::class, 'plant']);
// Route::post('/plots/{plot}/fertilize', [PlotController::class, 'fertilize']);
// Route::post('/plots/{plot}/infect', [PlotController::class, 'reportInfection']);

## Seed Bank
Route::prefix('seedbank')->group(function () {
    Route::view('/', 'seedbank');
    Route::get('deposit',   [SeedBankController::class, 'create'])->name('seedbank.create');
    Route::post('deposit',  [SeedBankController::class, 'store'])->name('seedbank.store');
});


## Tool Library
Route::prefix('toollib')->group(function () {
    Route::view('addTool', 'toollibrary.addTool')->middleware('admin')->name('tools.create'); 
    Route::post('addTool', [ToolController::class, 'store'])->middleware('admin')->name('tools.store');
});

## Markte Place
Route::prefix('market')->group(function () {
    Route::view('createListing', 'market.createListing')->name('market.create');
    Route::post('createListing', [MarketController::class, 'store'])->name('market.store');
});

## Rentals
Route::prefix('rental')->group(function () {
    Route::get('apply', [RentalController::class, 'create'])->name('rental.create');
    Route::post('apply', [RentalController::class, 'store'])->name('rental.store');
});
});

require __DIR__.'/auth.php';
