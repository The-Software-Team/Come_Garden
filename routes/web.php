<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\SeedBankController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ToolController;

use Illuminate\Support\Facades\Log;
## Seed Bank
Route::prefix('seedbank')->group(function () {
    Route::view('/', 'seedbank');
    Route::view('deposit', 'seedbank.deposit');
    Route::view('withdraw', 'seedbank.withdraw');

    Route::post('deposit',  [SeedBankController::class, 'store'])->name('seedbank.deposit');
    Route::post('withdraw', [SeedBankController::class, 'withdraw'])->name('seedbank.withdraw');
});


## Tool Library
Route::prefix('toollib')->group(function () {
    // Route::view('/', 'toollibrary');
    Route::view('addTool', 'toollibrary.addTool')->name('tools.create'); 

    Route::post('addTool', [ToolController::class, 'store'])->name('tools.store');
});

## Markte Place
Route::prefix('market')->group(function () {
    Route::get('createListing', [MarketController::class, 'create'])->name('market.create');
    Route::post('createListing', [MarketController::class, 'store'])->name('market.store');
});




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::view('/login', 'auth.login');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





// Route::resource('samble', MySampleResourceController::class);
// Route::apiResource('tasks', TaskController::class);
