<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Records\DashboardController as RecordsDashboardController;
use App\Http\Controllers\Records\DocumentController as RecordsDocumentController;
use App\Http\Controllers\SoMasterListController;
use App\Http\Controllers\MajorsController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\OutgoingController;


Route::get('/', function () {
    return view('welcome');
});

// Viewing Documents via Tracking Number (Unauthenticated Access) 
Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
Route::get('/documents/track', [DocumentController::class, 'track'])->name('documents.track');
Route::get('/documents/view/tracking/{tracking_number}', [DocumentController::class, 'viewDocumentByTracking'])
    ->name('documents.view.tracking');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:user|admin', 
    // Role-based middleware
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Add this route within the appropriate middleware group
Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/password/change', [PasswordController::class, 'change'])->name('password.change');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/so_master_lists/data', [SoMasterListController::class, 'getData'])->name('so_master_lists.data');
    Route::resource('programs', ProgramsController::class);
    Route::resource('majors', MajorsController::class);
    Route::resource('outgoings', OutgoingController::class);

    Route::put('/so_master_lists/{soMasterList}/inline', [SoMasterListController::class, 'updateInline'])->name('so_master_lists.updateInline');
    Route::post('/so_master_lists/import', [SoMasterListController::class, 'importCsv'])->name('so_master_lists.import');
    Route::post('/so_master_lists/{soMasterList}/upload_govt_permit', [SoMasterListController::class, 'uploadGovtPermit'])->name('so_master_lists.uploadGovtPermit');
    Route::post('/programs/import', [SoMasterListController::class, 'importPrograms'])->name('programs.import');
    Route::post('/majors/import', [SoMasterListController::class, 'importMajors'])->name('majors.import');
    Route::post('/outgoings/import', [OutgoingController::class, 'import'])->name('outgoings.import');
    Route::resource('so_master_lists', SoMasterListController::class);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/documents/track/assign', [AdminDashboardController::class, 'assignTracking'])->name('documents.track.assign');
    Route::get('password/change', [PasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('password/change', [PasswordController::class, 'change'])->name('password.change');
    Route::get('/manage-users', [AdminUserController::class, 'index'])->name('manage.users.index');
    Route::get('/documents/view/{document}', [AdminDocumentController::class, 'viewDocument'])->name('documents.view');
    Route::post('/documents/upload', [AdminDocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/track', [AdminDocumentController::class, 'track'])->name('documents.track');
    Route::get('/documents/user/{email}', [AdminDocumentController::class, 'userDocuments'])->name('documents.userDocuments');    // Route to download the users import template
    Route::get('/manage-users/import/template', [AdminUserController::class, 'downloadTemplate'])->name('manage.users.import.template');
    Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/view/tracking/{tracking_number}', [AdminDocumentController::class, 'viewDocumentByTracking'])->name('documents.view.tracking');
    // Fetch Users Data for DataTables
    Route::get('/manage-users/data', [AdminUserController::class, 'getUsers'])->name('manage.users.data');

    // Create User
    Route::get('/manage-users/create', [AdminUserController::class, 'create'])->name('manage.users.create');
    Route::post('/manage-users', [AdminUserController::class, 'store'])->name('manage.users.store');

    // Edit User
    Route::get('/manage-users/{user}/edit', [AdminUserController::class, 'edit'])->name('manage.users.edit');
    Route::put('/manage-users/{user}', [AdminUserController::class, 'update'])->name('manage.users.update');

    // Delete User
    Route::delete('/manage-users/{user}', [AdminUserController::class, 'destroy'])->name('manage.users.destroy');

    // Import Users
    Route::get('/manage-users/import/form', [AdminUserController::class, 'showImportForm'])->name('manage.users.import.form');
    Route::post('/manage-users/import', [AdminUserController::class, 'import'])->name('manage.users.import');

    // Generate Password
    Route::post('/manage-users/{user}/generate-password', [AdminUserController::class, 'generatePassword'])->name('manage.users.generatePassword');
});

Route::prefix('records')->name('records.')->middleware(['auth', 'role:Records'])->group(function () {

    // SoMasterList Management Routes for Records
    Route::resource('so_master_lists', SoMasterListController::class);
    Route::resource('programs', ProgramsController::class);
    Route::resource('majors', MajorsController::class);
    Route::resource('outgoings', OutgoingController::class);

    Route::put('/so_master_lists/{soMasterList}/inline', [SoMasterListController::class, 'updateInline'])->name('so_master_lists.updateInline');
    Route::post('/so_master_lists/import', [SoMasterListController::class, 'importCsv'])->name('so_master_lists.import');
    Route::post('/programs/import', [SoMasterListController::class, 'importPrograms'])->name('programs.import');
    Route::post('/majors/import', [SoMasterListController::class, 'importMajors'])->name('majors.import');
    Route::post('/outgoings/import', [OutgoingController::class, 'importCsv'])->name('outgoings.import');

    // Document Management Routes for Records
    Route::get('/documents', [RecordsDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/view/{document}', [RecordsDocumentController::class, 'viewDocument'])->name('documents.view');
    Route::post('/documents/upload', [RecordsDocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/track', [RecordsDocumentController::class, 'track'])->name('documents.track');
    Route::post('/documents/track/assign', [RecordsDocumentController::class, 'assignTracking'])->name('documents.track.assign');
    Route::get('/documents/user/{email}', [RecordsDocumentController::class, 'userDocuments'])->name('documents.userDocuments');
    Route::get('/documents/view/tracking/{tracking_number}', [RecordsDocumentController::class, 'viewDocumentByTracking'])->name('documents.view.tracking');

    // Password Change Routes
    Route::get('/password/change', [RecordsDocumentController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/password/change', [RecordsDocumentController::class, 'change'])->name('password.change');
});
