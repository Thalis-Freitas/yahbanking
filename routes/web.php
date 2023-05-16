<?php

use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
});

require __DIR__.'/auth.php';
