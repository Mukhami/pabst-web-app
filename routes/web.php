<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('/users-data', [UserController::class, 'usersData'])->name('users.data');

    Route::resource('roles', RoleController::class)->only('index');
    Route::get('/roles-data', [RoleController::class, 'rolesData'])->name('roles.data');


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
