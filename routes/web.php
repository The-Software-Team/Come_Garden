<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\SeedBankController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\RentalController;

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

## Seed Bank
Route::prefix('seedbank')->group(function () {
    Route::get('deposit',   [SeedBankController::class, 'create'])->name('seedbank.create');
    Route::post('deposit',  [SeedBankController::class, 'store'])->name('seedbank.store');
});


## Tool Library
Route::prefix('toollib')->group(function () {
    Route::view('addTool', 'toollibrary.addTool')->middleware(['auth', 'admin'])->name('tools.create'); 
    Route::post('addTool', [ToolController::class, 'store'])->middleware(['auth', 'admin'])->name('tools.store');
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


require __DIR__.'/auth.php';
