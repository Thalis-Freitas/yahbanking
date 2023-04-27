<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvestimentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [InvestimentController::class, 'index'])->name('home');
    Route::resource('/investiments', InvestimentController::class)->names([
        'create' => 'investiments.create',
        'store' => 'investiments.store',
        'show' => 'investiments.show',
        'edit' => 'investiments.edit',
        'update' => 'investiments.update',
        'destroy' => 'investiments.destroy',
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
    Route::post('clients/{id}/investiment', [ClientController::class, 'investiment'])->name('clients.investiment');
    Route::post('clients/{id}/investiment/apply', [ClientController::class, 'apply'])->name('clients.investiment.apply');
    Route::post('clients/{id}/investiment/redeem', [ClientController::class, 'redeem'])->name('clients.investiment.redeem');
});

require __DIR__.'/auth.php';
