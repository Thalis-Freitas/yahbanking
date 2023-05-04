<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvestmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [InvestmentController::class, 'index'])->name('home');
    Route::resource('/investments', InvestmentController::class)->names([
        'create' => 'investments.create',
        'store' => 'investments.store',
        'show' => 'investments.show',
        'edit' => 'investments.edit',
        'update' => 'investments.update',
        'destroy' => 'investments.destroy',
    ]);

    Route::resource('/clients', ClientController::class)->names([
        'index' => 'clients.index',
        'create' => 'clients.create',
        'store' => 'clients.store',
        'show' => 'clients.show',
        'edit' => 'clients.edit',
        'update' => 'clients.update',
        'destroy' => 'clients.destroy',
    ]);

    Route::patch('clients/{id}/deposit', [ClientController::class, 'deposit'])->name('clients.deposit');
    Route::post('clients/{id}/investment', [ClientController::class, 'investment'])->name('clients.investment');
    Route::post('clients/{id}/investment/apply', [ClientController::class, 'apply'])->name('clients.investment.apply');
    Route::post('clients/{id}/investment/redeem', [ClientController::class, 'redeem'])->name('clients.investment.redeem');
});

require __DIR__.'/auth.php';
