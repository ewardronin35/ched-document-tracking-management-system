<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DocumentController as AdminDocumentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CavController;
use App\Http\Controllers\Records\DashboardController as RecordsDashboardController;
use App\Http\Controllers\Records\DocumentController as RecordsDocumentController;
use App\Http\Controllers\SoMasterListController;
use App\Http\Controllers\MajorsController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\OutgoingController;
use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\IncomingController;
use App\Http\Controllers\HEIController;
use App\Http\Controllers\GmailController;
use App\Http\Controllers\RecaptchaController;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth2callback', [GmailController::class, 'handleOAuthCallback'])->name('oauth.callback');

// Viewing Documents via Tracking Number (Unauthenticated Access) For Guests
Route::post('/verify-otp', [DocumentController::class, 'verifyOtp']);
Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
Route::get('/documents/track', [DocumentController::class, 'track'])->name('documents.track');
Route::get('/documents/view/tracking/{tracking_number}', [DocumentController::class, 'viewDocumentByTracking'])
    ->name('documents.view.tracking');

  
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->get('/dashboard', function () {
    $user = auth()->user();
    $roles = $user->getRoleNames();  // Assuming you're using Spatie's Role package

    // Get the first role (assuming the user has one)
    $role = $roles->isNotEmpty() ? strtolower($roles->first()) : null;

    // List of valid roles
    $validRoles = ['admin', 'user', 'records', 'hr', 'regionaldirector', 'technical', 'accounting', 'supervisor', 'unifast'];

    // Check if the role is valid
    if (!$role || !in_array($role, $validRoles)) {
        abort(403, 'Unauthorized');
    }

    Log::info("Redirecting user with role: {$role} to route: {$role}.dashboard");

    // Redirect to the role-based dashboard
    return redirect()->route("{$role}.dashboard");
})->name('dashboard');

// Role-based dashboard routes pointing to DashboardController@index
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/records/dashboard', [DashboardController::class, 'index'])->name('records.dashboard');
        Route::get('/hr/dashboard', [DashboardController::class, 'index'])->name('hr.dashboard');
        Route::get('/regionaldirector/dashboard', [DashboardController::class, 'index'])->name('regionaldirector.dashboard');
        Route::get('/technical/dashboard', [DashboardController::class, 'index'])->name('technical.dashboard');
        Route::get('/accounting/dashboard', [DashboardController::class, 'index'])->name('accounting.dashboard');
        Route::get('/supervisor/dashboard', [DashboardController::class, 'index'])->name('supervisor.dashboard');
        Route::get('/unifast/dashboard', [DashboardController::class, 'index'])->name('unifast.dashboard');

    });
    
    
// Add this route within the appropriate middleware group
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/password/change', [PasswordController::class, 'change'])->name('password.change');
    Route::get('/logout', [GmailController::class, 'logout'])->name('logout');
    Route::get('/admin/gmail/oauth/callback', [GmailController::class, 'handleOAuthCallback'])->name('admin.gmail.oauthCallback');

});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::post('gmail/send', [GmailController::class, 'sendEmail'])->name('sendEmail');
    Route::get('gmail/emails', [GmailController::class, 'listEmails'])->name('gmail.emails');
    Route::get('/outgoings/report', [OutgoingController::class, 'generateReport'])->name('outgoings.report');

    Route::post('heis/import', [HEIController::class, 'import'])->name('heis.import');
    Route::get('heis/import', [HEIController::class, 'showImportForm'])->name('heis.import.form');
    Route::get('/emails', [GmailController::class, 'listEmails'])->name('gmail.emails');
    Route::get('/cav/local', [CavController::class, 'getLocalCavRecords'])->name('cav.local');
    Route::get('/cav/abroad', [CavController::class, 'getAbroadCavRecords'])->name('cav.abroad');
    Route::resource('heis', HEIController::class);
    Route::get('/gmail/contacts', [GmailController::class, 'getContacts'])->name('gmail.getContacts');
    Route::get('/gmail/attachment/{emailId}/{attachmentId}/{filename}', [GmailController::class, 'downloadAttachment'])->name('gmail.downloadAttachment');

    Route::post('/cavs/import-csv', [CavController::class, 'importCsv'])->name('cavs.import-csv');
    Route::get('gmail/sent', [GmailController::class, 'listSentEmails'])->name('gmail.sent');
    Route::get('gmail/drafts', [GmailController::class, 'listDraftEmails'])->name('gmail.drafts');
    Route::get('gmail/spam', [GmailController::class, 'listSpamEmails'])->name('gmail.spam');
    Route::get('gmail/trash', [GmailController::class, 'listTrashEmails'])->name('gmail.trash');
    Route::get('gmail/view/{id}', [GmailController::class, 'viewEmail'])->name('gmail.view');
    Route::get('gmail/viewjson/{id}', [GmailController::class, 'viewEmailJson'])->name('gmail.viewjson');
    Route::get('documents/{id}/details', [DocumentController::class, 'details'])->name('documents.details');
    Route::get('/so_master_lists/data', [SoMasterListController::class, 'getData'])->name('so_master_lists.data');
    Route::resource('majors', MajorsController::class);
    Route::resource('outgoings', OutgoingController::class);
    Route::post('gmail/import', [GmailController::class, 'import'])->name('gmail.import');
    Route::post('gmail/suggestions', [GmailController::class, 'suggestions'])->name('gmail.suggestions');

    Route::get('/programs/data', [ProgramsController::class, 'indexData'])->name('programs.data');
    Route::put('/programs/{program}', [ProgramsController::class, 'update'])->name('programs.update');
    Route::post('/programs/import', [ProgramsController::class, 'import'])->name('programs.import');
    Route::get('programs/data', [ProgramsController::class, 'indexData'])->name('programs.data');

    Route::get('/documents/edit-modal/{id}', [DocumentController::class, 'editModal'])->name('documents.editModal');

    Route::resource('programs', ProgramsController::class);

    // Majors Routes
    Route::get('/majors/data', [MajorsController::class, 'indexData'])->name('majors.data');
    Route::put('/majors/{major}', [MajorsController::class, 'update'])->name('majors.update');
    Route::post('records/store', [RecordController::class, 'store'])->name('record.store');

    Route::get('records/data', [RecordController::class, 'data'])->name('record.data');
    Route::resource('records', RecordController::class);
    Route::get('cav/all', [CavController::class, 'getAllCavs'])->name('cav.all');
    Route::get('cav/get', [CavController::class, 'getCavs'])->name('cav.get');
    Route::get('/cav/import/form', [CavController::class, 'showImportForm'])->name('cav.import.form');

    Route::get('outgoings/data', [OutgoingController::class, 'data'])->name('outgoings.data');
    Route::get('incomings/data', [IncomingController::class, 'data'])->name('incomings.data');
    Route::post('incomings/import', [IncomingController::class, 'import'])->name('incomings.import');
    Route::get('/documents/details-modal/{id}', [DocumentController::class, 'details'])->name('documents.detailsModal');

    Route::resource('cav', CavController::class);

    Route::put('/so_master_lists/{soMasterList}/inline', [SoMasterListController::class, 'updateInline'])->name('so_master_lists.updateInline');
    Route::post('/so_master_lists/import', [SoMasterListController::class, 'importCsv'])->name('so_master_lists.import');
    Route::post('/programs/import', [SoMasterListController::class, 'importPrograms'])->name('programs.import');
    Route::post('/majors/import', [SoMasterListController::class, 'importMajors'])->name('majors.import');
    Route::post('/outgoings/import', [OutgoingController::class, 'import'])->name('outgoings.import');
    Route::resource('so_master_lists', SoMasterListController::class);
    Route::get('/getEmails', [GmailController::class, 'getEmails'])->name('gmail.getEmails');
    Route::get('/getEmailDetails', [GmailController::class, 'getEmailDetails'])->name('gmail.getEmailDetails');
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
    Route::post('/documents/approve-single/{id}', [DocumentController::class, 'approveSingle'])->name('documents.approveSingle');
    Route::post('/documents/bulk-approval', [DocumentController::class, 'bulkApproval'])->name('documents.bulkApproval');
    Route::get('/documents/getDocuments', [DocumentController::class, 'getDocuments'])->name('documents.getDocuments');
    Route::put('/incomings/{incoming}/release', [IncomingController::class, 'release'])->name('incomings.release');
    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
    
    Route::resource('incomings', IncomingController::class);




    // Fetch Users Data for DataTables
    Route::get('/manage-users/data', [AdminUserController::class, 'getUsers'])->name('manage.users.data');
    Route::get('/manage-users/{user}/permissions', [AdminUserController::class, 'getPermissions'])->name('manage.users.permissions');
    Route::post('/manage-users/{user}/permissions', [AdminUserController::class, 'updatePermissions'])->name('manage.users.updatePermissions');

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
    Route::resource('cav', CavController::class);

    // Password Change Routes
    Route::get('/password/change', [RecordsDocumentController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/password/change', [RecordsDocumentController::class, 'change'])->name('password.change');
});

Route::prefix('supervisor')->name('supervisor.')->middleware(['auth', 'role:Supervisor'])->group(function () {

    Route::post('gmail/send', [GmailController::class, 'sendEmail'])->name('sendEmail');
    Route::get('gmail/emails', [GmailController::class, 'listEmails'])->name('gmail.emails');
    Route::get('/emails', [GmailController::class, 'listEmails'])->name('gmail.emails');
    Route::get('gmail/sent', [GmailController::class, 'listSentEmails'])->name('gmail.sent');
    Route::get('gmail/drafts', [GmailController::class, 'listDraftEmails'])->name('gmail.drafts');
    Route::get('gmail/spam', [GmailController::class, 'listSpamEmails'])->name('gmail.spam');
    Route::get('gmail/trash', [GmailController::class, 'listTrashEmails'])->name('gmail.trash');
    Route::get('gmail/view/{id}', [GmailController::class, 'viewEmail'])->name('gmail.view');
    Route::get('gmail/viewjson/{id}', [GmailController::class, 'viewEmailJson'])->name('gmail.viewjson');
    Route::get('documents/{id}/details', [DocumentController::class, 'details'])->name('documents.details');
    Route::get('/documents/edit-modal/{id}', [DocumentController::class, 'editModal'])->name('documents.editModal');
    Route::get('/documents/details-modal/{id}', [DocumentController::class, 'details'])->name('documents.detailsModal');
    Route::get('/getEmails', [GmailController::class, 'getEmails'])->name('gmail.getEmails');
    Route::get('/getEmailDetails', [GmailController::class, 'getEmailDetails'])->name('gmail.getEmailDetails');
    Route::post('/documents/track/assign', [AdminDashboardController::class, 'assignTracking'])->name('documents.track.assign');
    Route::get('password/change', [PasswordController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('password/change', [PasswordController::class, 'change'])->name('password.change');
   Route::get('/documents/view/{document}', [AdminDocumentController::class, 'viewDocument'])->name('documents.view');
    Route::post('/documents/upload', [AdminDocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/track', [AdminDocumentController::class, 'track'])->name('documents.track');
    Route::get('/documents/user/{email}', [AdminDocumentController::class, 'userDocuments'])->name('documents.userDocuments');    // Route to download the users import template
   Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/view/tracking/{tracking_number}', [AdminDocumentController::class, 'viewDocumentByTracking'])->name('documents.view.tracking');
    Route::post('/documents/approve-single/{id}', [DocumentController::class, 'approveSingle'])->name('documents.approveSingle');
    Route::post('/documents/bulk-approval', [DocumentController::class, 'bulkApproval'])->name('documents.bulkApproval');
    Route::get('/documents/getDocuments', [DocumentController::class, 'getDocuments'])->name('documents.getDocuments');
    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
    Route::post('/documents/{id}/release', [DocumentController::class, 'release'])->name('documents.release');

    // Password Change Routes
    Route::get('/password/change', [RecordsDocumentController::class, 'showChangeForm'])->name('password.change.form');
    Route::post('/password/change', [RecordsDocumentController::class, 'change'])->name('password.change');
});
Route::post('/verify-recaptcha', [RecaptchaController::class, 'verify']);
Route::post('/debug-broadcast-auth', function () {
    return response()->json([
        'socket_id' => request('socket_id'),
        'auth'      => 'dummy.auth.token'
    ]);
});
Route::post('/broadcasting/auth', function (Request $request) {
    // Retrieve the socket ID and channel name from the request
    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    // Use your Pusher app credentials (ensure these are set in your .env file)
    $key = env('PUSHER_APP_KEY');       // e.g., "c0884451d9d4dd93fd38"
    $secret = env('PUSHER_APP_SECRET'); // e.g., "f4894327c4d62e72635b"

    // Calculate the signature using HMAC SHA256.
    // The string to sign is typically "{socket_id}:{channel_name}"
    $signature = hash_hmac('sha256', "{$socketId}:{$channelName}", $secret);

    // Return a JSON response in the correct format.
    return response()->json([
        'socket_id' => $socketId,
        'auth'      => "{$key}:{$signature}",
    ]);
})->middleware('web');
Route::post('/test-broadcasting-auth', function (Request $request) {
    // This uses Laravel's default authentication logic for broadcasting.
    return Broadcast::auth($request);
});





Route::get('/test-auth', function () {
    return view('test-auth');
});
