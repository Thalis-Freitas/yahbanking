<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvestimentController;

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
});

require __DIR__.'/auth.php';
