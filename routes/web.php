<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvestmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [InvestmentController::class, 'index'])->name('home');
    Route::resource('/investments', InvestmentController::class);
    Route::resource('/clients', ClientController::class);
});

require __DIR__.'/auth.php';
