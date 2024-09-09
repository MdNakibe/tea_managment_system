<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProductPacketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeaPacketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Auth::routes();


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');

    Route::resource('invoices', InvoiceController::class);
    Route::resource('teapackets', TeaPacketController::class);
    Route::resource('production', ProductionController::class);
    Route::resource('productpackets', ProductPacketController::class);
    Route::get('product-history/{id}',[ReportController::class, 'productReport'])->name('product-history');
});
