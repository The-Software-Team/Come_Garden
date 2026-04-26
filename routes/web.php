<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\MySampleResourceController;
use App\Http\Controllers\SeedBankController;

Route::prefix('seedbank')->group(function () {
    Route::view('/', 'seedbank');
    Route::view('deposit', 'seedbank.deposit');
    Route::view('withdraw', 'seedbank.withdraw');

    Route::post('deposit',  [SeedBankController::class, 'store'])->name('seedbank.deposit');
    Route::post('withdraw', [SeedBankController::class, 'withdraw'])->name('seedbank.withdraw');
});


// Route::resource('samble', MySampleResourceController::class);
// Route::apiResource('tasks', TaskController::class);
