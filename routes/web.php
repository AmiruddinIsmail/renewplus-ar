<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', 'login');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('customers')->group(function () {
        Route::get('', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    });

    Route::prefix('invoices')->group(function () {
        Route::get('', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices.index');
    });

    // Route::prefix('orders')->group(function(){
    //     Route::get('', [App\Http\Controllers\OrderController::class, 'index'])->name('orders');
    // });

    Route::prefix('users')->group(function () {
        Route::get('', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
        Route::get('create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('test', [App\Http\Controllers\OrderController::class, 'export']);

require __DIR__.'/auth.php';
