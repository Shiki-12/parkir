<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('transactions.index');
});

Route::resource('locations', LocationController::class);
Route::resource('vehicle-types', VehicleTypeController::class);

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions/enter', [TransactionController::class, 'enter'])->name('transactions.enter');
Route::post('/transactions/exit', [TransactionController::class, 'exit'])->name('transactions.exit');
Route::get('/transactions/all', [TransactionController::class, 'allTransactions'])->name('transactions.all');
Route::get('/tickets/{noTiket}', [TransactionController::class, 'downloadTicket'])->name('tickets.download');
