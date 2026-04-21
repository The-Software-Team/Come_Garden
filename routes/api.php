<?php
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\{
//     MemberController,
//     PlotController,
//     RentalController,
//     SeedBankController,
//     ToolController,
//     MarketplaceController,
//     VolunteerController,
//     WalletController
// };

//     // Members
// Route::prefix('members')->group(function () {
//     Route::post('/', [MemberController::class, 'store']);
//     Route::get('/{id}', [MemberController::class, 'show']);
// });

// // Plots
// Route::prefix('plots')->group(function () {
//     Route::post('/generate', [PlotController::class, 'generate']);
//     Route::get('/', [PlotController::class, 'index']);
//     Route::post('/infection', [PlotController::class, 'reportInfection']);
// });

// // Rentals
// Route::prefix('rentals')->group(function () {
//     Route::post('/apply', [RentalController::class, 'apply']);
// });

// // Seed Bank
// Route::prefix('seeds')->group(function () {
//     Route::post('/deposit', [SeedBankController::class, 'deposit'])->name('seeds.deposit');
//     Route::post('/withdraw', [SeedBankController::class, 'withdraw']);
// });

// // Tools
// Route::prefix('tools')->group(function () {
//     Route::post('/book', [ToolController::class, 'book']);
//     Route::post('/return', [ToolController::class, 'return']);
//     Route::post('/damage', [ToolController::class, 'reportDamage']);
// });

// // Marketplace
// Route::prefix('marketplace')->group(function () {
//     Route::post('/listings', [MarketplaceController::class, 'createListing']);
//     Route::post('/trades', [MarketplaceController::class, 'createTrade']);
//     Route::post('/questions', [MarketplaceController::class, 'askQuestion']);
//     Route::post('/answers', [MarketplaceController::class, 'answer']);
// });

// // Volunteer
// Route::prefix('volunteer')->group(function () {
//     Route::post('/shifts', [VolunteerController::class, 'createShift']);
//     Route::post('/assign', [VolunteerController::class, 'assign']);
//     Route::post('/complete', [VolunteerController::class, 'complete']);
//     Route::post('/swap', [VolunteerController::class, 'requestSwap']);
// });

// // Wallet
// Route::prefix('wallet')->group(function () {
//     Route::get('/{member}', [WalletController::class, 'show']);
// });