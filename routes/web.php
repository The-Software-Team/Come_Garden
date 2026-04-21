<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeedBankController;
use Illuminate\Support\Facades\Log;

Route::prefix('seedbank')->group(function () {
    Route::get('/', [SeedBankController::class, 'create']);
    Route::post('/deposit', [SeedBankController::class, 'store'])->name('seedbank.deposit');
});

