<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatterSubTypeController;
use App\Http\Controllers\MatterTypeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MatterRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('/users-data', [UserController::class, 'usersData'])->name('users.data');

    Route::resource('roles', RoleController::class)->only('index');
    Route::get('/roles-data', [RoleController::class, 'rolesData'])->name('roles.data');

    Route::resource('matter-types', MatterTypeController::class);
    Route::get('/matter-types-data', [MatterTypeController::class, 'matterTypesData'])->name('matter-types.data');

    Route::resource('matter-sub-types', MatterSubTypeController::class)->except(['index', 'create', 'show']);
    Route::get('/matter-subtypes-data/{matterType}', [MatterSubTypeController::class, 'matterSubTypesData'])->name('matter-subtypes.data');

    Route::resource('matter-requests', MatterRequestController::class);
    Route::post('/matter-request-post-approval', [MatterRequestController::class, 'postApproval'])->name('matter-requests.post-approval');
    Route::get('/download-matter-requests-pdf/{matterRequest}', [MatterRequestController::class, 'downloadPDF'])->name('matter-requests.downloadPDF');
    Route::get('/view-matter-requests-pdf/{matterRequest}', [MatterRequestController::class, 'viewPDF'])->name('matter-requests.viewPDF');
    Route::get('/matter-requests-data', [MatterRequestController::class, 'matterRequestsData'])->name('matter-requests.data');
    Route::get('/matter-requests-pending-approval-data', [MatterRequestController::class, 'usersPendingMatterRequestsData'])->name('matter-requests.users-pending-approval.data');
    Route::get('/matter-request-approvals-data/{matterRequest}', [MatterRequestController::class, 'matterRequestApprovalsData'])->name('matter-request-approvals.data');


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
