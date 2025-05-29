<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'can:view_dashboard'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // User Management Routes
    Route::prefix('users')->name('users.')->middleware(['can:view_user_management'])->group(function () {
        Route::get('/', [App\Http\Controllers\Users\UserController::class, 'index'])->name('index');
        Route::get('/data', [App\Http\Controllers\Users\UserController::class, 'getData'])->name('data');
        Route::get('/create', [App\Http\Controllers\Users\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Users\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\Users\UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\Users\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\Users\UserController::class, 'destroy'])->name('destroy');
    });

    // Role Management Routes
    Route::prefix('roles')->name('roles.')->middleware(['can:view_role_management'])->group(function () {
        Route::get('/', [App\Http\Controllers\Roles\RoleController::class, 'index'])->name('index');
        Route::get('/data', [App\Http\Controllers\Roles\RoleController::class, 'getData'])->name('data');
        Route::get('/create', [App\Http\Controllers\Roles\RoleController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Roles\RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [App\Http\Controllers\Roles\RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [App\Http\Controllers\Roles\RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [App\Http\Controllers\Roles\RoleController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
