<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
Route::get('/documents/track', [DocumentController::class, 'track'])->name('documents.track');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/manage-users', [UserController::class, 'index'])->name('manage.users.index');
    Route::get('/manage-users/create', [UserController::class, 'create'])->name('manage.users.create');
    Route::post('/manage-users', [UserController::class, 'store'])->name('manage.users.store');
    Route::get('/manage-users/{user}/edit', [UserController::class, 'edit'])->name('manage.users.edit');
    Route::put('/manage-users/{user}', [UserController::class, 'update'])->name('manage.users.update');
    Route::delete('/manage-users/{user}', [UserController::class, 'destroy'])->name('manage.users.destroy');

    // CSV Import Routes
    Route::get('/manage-users/import', [UserController::class, 'showImportForm'])->name('manage.users.import.form');
    Route::post('/manage-users/import', [UserController::class, 'import'])->name('manage.users.import');

    // Password Generation Route
    Route::post('/manage-users/{user}/generate-password', [UserController::class, 'generatePassword'])->name('manage.users.generatePassword');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Welcome to the Admin Dashboard';
    })->name('admin.dashboard');
});