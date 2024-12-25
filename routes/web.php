<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
Route::get('/documents/track', [DocumentController::class, 'track'])->name('documents.track');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:user|admin', 
    'password.changed', // Add this middleware
    // Role-based middleware
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    // Manage Users Page
    Route::get('/manage-users', [UserController::class, 'index'])->name('manage.users.index');


    // Route to download the users import template
    Route::get('/manage-users/import/template', [UserController::class, 'downloadTemplate'])->name('manage.users.import.template');

    // Fetch Users Data for DataTables
    Route::get('/manage-users/data', [UserController::class, 'getUsers'])->name('manage.users.data');

    // Create User
    Route::get('/manage-users/create', [UserController::class, 'create'])->name('manage.users.create');
    Route::post('/manage-users', [UserController::class, 'store'])->name('manage.users.store');

    // Edit User
    Route::get('/manage-users/{user}/edit', [UserController::class, 'edit'])->name('manage.users.edit');
    Route::put('/manage-users/{user}', [UserController::class, 'update'])->name('manage.users.update');

    // Delete User
    Route::delete('/manage-users/{user}', [UserController::class, 'destroy'])->name('manage.users.destroy');

    // Import Users
    Route::get('/manage-users/import/form', [UserController::class, 'showImportForm'])->name('manage.users.import.form');
    Route::post('/manage-users/import', [UserController::class, 'import'])->name('manage.users.import');

    // Generate Password
    Route::post('/manage-users/{user}/generate-password', [UserController::class, 'generatePassword'])->name('manage.users.generatePassword');
});

Route::middleware(['auth', 'role:user|admin'])->group(function () {
    Route::get('password/change', [PasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('password/change', [PasswordController::class, 'change'])->name('password.change');
});
