<?php
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('transaction.index');
});

Route::middleware(['auth'])->group(function () {
    // Location
    Route::resource('location', LocationController::class);

    // Vehicle Type
    Route::resource('vehicle-type', VehicleTypeController::class);

    // Transaction
    Route::get('/transaction', [TransactionController::class, 'index'])
        ->name('transaction.index');
    Route::post('/transaction/enter', [TransactionController::class, 'enterVehicle'])
        ->name('transaction.enter');
    Route::post('/transaction/exit', [TransactionController::class, 'exitVehicle'])
        ->name('transaction.exit');
    Route::get('/transaction/ticket/{no_tiket}', [TransactionController::class, 'viewTicket'])
        ->name('transaction.ticket');
    Route::get('/transaction/all', [TransactionController::class, 'allTransactions'])
        ->name('transaction.all');

    // Report
    Route::get('/report/location', [LocationController::class, 'report'])
        ->name('report.location');
    Route::get('/report/transaction', [TransactionController::class, 'report'])
        ->name('report.transaction');
});
require __DIR__.'/auth.php';