<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PurchaseOrderController;

Route::prefix('/')->name('purchase_orders.')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
    Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
    Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
    Route::get('/{order}/status', [PurchaseOrderController::class, 'updateStatus'])->name('updateStatus');

});
