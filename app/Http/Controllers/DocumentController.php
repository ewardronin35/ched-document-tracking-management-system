<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentStatusMail;
use App\Models\GuestNotifiable;
use App\Notifications\DocumentSubmitted;
use Yajra\DataTables\Facades\DataTables; // Import DataTables
use Illuminate\Support\Facades\Http;
use Vonage\Client as VonageClient;
use Vonage\Client\Credentials\Basic as VonageCredentials;
use Vonage\SMS\Message\SMS as VonageSMS;
use App\Models\Incoming; // Add at the top of the file if not present
use App\Notifications\DocumentStatusNotification;
use Spatie\Permission\Models\Role; // Ensure Spatie roles are used (if applicable)

class DocumentController extends Controller
{
    
    /**
     * Display a listing of the documents (For Admins/Users).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    private function sendOtpSms($phoneNumber, $otp)
    {
        try {
            $basicCredentials = new VonageCredentials(
                config('services.vonage.api_key'),
                config('services.vonage.api_secret')
            );
    
            $vonage = new VonageClient($basicCredentials);
    
            // Create a new SMS message
            $response = $vonage->sms()->send(
                new VonageSMS($phoneNumber, config('services.vonage.from'), "Your OTP for document verification is: $otp")
            );
    
            // Retrieve the first message from the response
            $message = $response->current();
            if ($message->getStatus() !== 0) {
                throw new \Exception("Failed to send OTP. Status: " . $message->getStatus());
            }
        } catch (\Exception $e) {
            Log::error('Vonage SMS Error: ' . $e->getMessage());
            throw new \Exception('Unable to send OTP via SMS.');
        }
    }
    public function index(Request $request)
    {
        // Auto‑archive: update documents older than 30 days (if not already archived)
        Document::where('status', '!=', 'Archived')
            ->whereDate('created_at', '<', Carbon::now()->subDays(30))
            ->update(['status' => 'Archived']);
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Start query with eager loading
        $query = Document::with(['user', 'routedUser']);
    
        // For admins, Records, and Regional Directors see all documents;
        // others see only documents routed to them or created by them.
        if (!$user->hasRole(['admin', 'Records', 'RegionalDirector'])) {
            $query->where(function ($q) use ($user) {
                $q->where('routed_to', $user->id)
                  ->orWhereHas('user', function ($subQuery) use ($user) {
                      $subQuery->where('id', $user->id);
                  });
            });
        }
    
        // Apply filters if provided
        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->input('document_type'));
        }
        if ($request->filled('status')) {
            if ($request->input('status') === 'today') {
                $query->whereDate('created_at', Carbon::today());
            } elseif ($request->input('status') === 'Archived') {
                // Return only archived documents (updated above)
                $query->where('status', 'Archived');
            } else {
                $query->where('status', $request->input('status'));
            }
        }
    
        // Additional "3‑7‑20 Day Rule" logic (optional)
        // For example, if your Document model has a field 'transaction_complexity' with possible values:
        // 'Simple', 'Complex', 'Highly Technical', then you can flag overdue items:
        $query->get()->each(function($doc) {
            $daysOpen = Carbon::parse($doc->created_at)->diffInDays(Carbon::now());
            if ($doc->transaction_complexity === 'Simple' && $daysOpen > 3) {
                // You might set a flag or update a field, e.g.,
                $doc->overdue = true;
            } elseif ($doc->transaction_complexity === 'Complex' && $daysOpen > 7) {
                $doc->overdue = true;
            } elseif ($doc->transaction_complexity === 'Highly Technical' && $daysOpen > 20) {
                $doc->overdue = true;
            }
        });
    
        // Order by a custom sort order (for example, Pending first, then Accepted, then Rejected)
        $query->orderByRaw("CASE 
                                WHEN approval_status = 'Pending' THEN 0 
                                WHEN approval_status = 'Accepted' THEN 1 
                                WHEN approval_status = 'Rejected' THEN 2 
                                ELSE 3 END")
              ->orderBy('created_at', 'desc');
    
        // Retrieve documents (with pagination or as needed)
        $documents = $query->paginate(50);
        $users = User::orderBy('name')->get();
    
        // ✅ Ensure correct views are returned based on role
        if ($user->hasRole('admin')) {
            return view('admin.documents.index', compact('documents', 'users'));
        } elseif ($user->hasRole('Records')) {
            return view('records.documents.index', compact('documents', 'users'));
        } elseif ($user->hasRole('RegionalDirector')) {
            return view('regional_director.documents.index', compact('documents', 'users'));
        } elseif ($user->hasRole('Supervisor')) {
            return view('supervisor.documents.index', compact('documents', 'users'));
        } elseif ($user->hasRole('HR')) {
            return view('hr.documents.index', compact('documents', 'users'));
        } elseif ($user->hasRole('UNIFAST')) {
            return view('unifast.documents.index', compact('documents', 'users'));
        } else {
            return view('user.documents.index', compact('documents', 'users'));
        }
    }
    
    

    /**
     * Display documents for a specific user by email.
     *
     * @param  string  $email
     * @return \Illuminate\View\View
     */
    public function userDocuments($email)
    {
        // Get documents for the specified email
        $documents = Document::where('email', $email)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        $firstDocument = $documents->first();
        $users = User::orderBy('name')->get();
        $user = auth()->user();
    
        // Prepare common data for the view
        $data = [
            'documents' => $documents,
            'users'     => $users,
            'userEmail' => $email,
            'userName'  => $firstDocument ? $firstDocument->full_name : 'Unknown User'
        ];
    
        if ($user->hasRole('admin')) {
            return view('admin.documents.user_documents', $data);
        } elseif ($user->hasRole('Records')) {
            return view('records.documents.user_documents', $data);
        } elseif ($user->hasRole('RegionalDirector')) {
            return view('regional_director.documents.user_documents', $data);
        } elseif ($user->hasRole('Supervisor')) {
            return view('supervisor.documents.user_documents', $data);
        } elseif ($user->hasRole('HR')) {
            return view('hr.documents.user_documents', $data);
        } elseif ($user->hasRole('Technical')) {
            return view('technical.documents.user_documents', $data);
        } elseif ($user->hasRole('Unifast')) {
            return view('unifast.documents.user_documents', $data);
        } elseif ($user->hasRole('Accounting')) {
            return view('accounting.documents.user_documents', $data);
        } else {
            return view('user.documents.user_documents', $data);
        }
    }
    
    
    /**
     * Handle the document upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|integer',
        ]);
    
        // Retrieve session data
        $sessionOtp         = session('otp');
        $tempDocumentData   = session('temp_document_data');
        $tempFilePaths      = session('temp_file_paths');
    
        if (!$sessionOtp || !$tempDocumentData || !$tempFilePaths) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please upload the document again.',
            ], 400);
        }
    
        if ($request->otp != $sessionOtp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 401);
        }
    
        try {
            $documents = [];
            foreach ($tempFilePaths as $tempPath) {
                $documentId = strtoupper(Str::random(10));
                $trackingNumber = strtoupper(Str::random(12));
                while (Document::where('tracking_number', $trackingNumber)->exists()) {
                    $trackingNumber = strtoupper(Str::random(12));
                }
    
                // Move file from temporary to permanent location
                $filename = basename($tempPath);
                $newPath = 'documents/' . $documentId . '/' . $filename;
                Storage::disk('public')->move($tempPath, $newPath);
    
                $document = Document::create([
                    'tracking_number' => $trackingNumber,
                    'email'           => $tempDocumentData['email'],
                    'phone_number'    => $tempDocumentData['phone_number'],
                    'full_name'       => $tempDocumentData['full_name'],
                    'document_type'   => $tempDocumentData['document_type'],
                    'file_path'       => $newPath,
                    'status'          => 'Under Review',  // Default
                    'approval_status' => 'Pending',       // Default
                    'status_details'  => json_encode([
                        'message'   => 'Your document is currently under review. Please wait for further updates.',
                        'timestamp' => now()->toDateTimeString(),
                    ]),
                ]);
    
                $documents[] = [
                    'tracking_number' => $trackingNumber,
                ];
            }
    
            // Clear session data
            session()->forget(['otp', 'temp_document_data', 'temp_file_paths']);
    
            return response()->json([
                'success' => true,
                'documents' => $documents,
                'message' => 'Documents successfully uploaded after verification.',
            ]);
        } catch (\Exception $e) {
            Log::error('Document Store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while storing your document.',
            ], 500);
        }
    }
    
    public function upload(Request $request)
    {
        // Validate request inputs, including reCAPTCHA
        $validated = $request->validate([
            'email'               => 'required|email',
            'full_name'           => 'required|string|max:255',
            'document_type'       => 'required|string',
            'phone_number'        => 'required|string|regex:/^\+?[0-9\s\-]{7,15}$/',
            'details'             => 'required|string',
            'purpose'             => 'required|string',
            'document.*'          => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
            'agree_terms'         => 'accepted',
            'g-recaptcha-response'=> 'required|string',
        ]);
        $validated['email'] = filter_var($validated['email'], FILTER_SANITIZE_EMAIL);
        $validated['full_name'] = strip_tags($validated['full_name']);
        $validated['document_type'] = strip_tags($validated['document_type']);
        $validated['phone_number'] = strip_tags($validated['phone_number']);
        $validated['details'] = strip_tags($validated['details']);
        $validated['purpose'] = strip_tags($validated['purpose']);
        // Verify reCAPTCHA v3
        $recaptchaResponse = Http::timeout(50) // set timeout to 10 seconds
        ->asForm()
        ->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.v3.secret_key'),
            'response' => $validated['g-recaptcha-response'],
            'remoteip' => $request->ip(),
        ]);
        $recaptchaData = $recaptchaResponse->json();
        if (!$recaptchaData['success'] || $recaptchaData['score'] < 0.5) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA verification failed. Please try again.',
            ], 422);
        }
    
        try {
            // Temporarily store uploaded files
            $uploadedFiles = $request->file('document');
            $tempPaths = [];
            foreach ($uploadedFiles as $file) {
                $path = $file->store('temporary_uploads', 'public');
                $tempPaths[] = $path;
            }
    
            // Generate an OTP for phone verification
            $otp = random_int(100000, 999999);

            $documentData = $validated;
            unset($documentData['document']); // Remove file objects
    
            // Store temporary data and OTP in session
            session([
                'temp_document_data' => $documentData,
                'temp_file_paths'    => $tempPaths,
                'otp'                => $otp,
            ]);
    
            // Send OTP via Vonage SMS
            $this->sendOtpSms($validated['phone_number'], $otp);
    
            return response()->json([
                'success' => true,
                'documents' => [],  // <= Add this key so 'documents' always exists

                'message' => 'OTP sent to your phone for verification.',
            ]);
        } catch (\Exception $e) {
            Log::error('Upload Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during the upload process.',
            ], 500);
        }
    }
    
     
    
    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'approval_status' => 'required|string|in:Accepted,Rejected',
            'remarks' => 'nullable|string|max:255',
        ]);
    
        try {
            // Retrieve the document
            $document = Document::findOrFail($id);
    
            // Authorization check
            $this->authorize('approve', $document);
    
            // Update approval status and optional remarks
            $document->approval_status = $validated['approval_status'];
            $document->status_details = json_encode([
                'message' => 'Document has been ' . strtolower($validated['approval_status']) . '.',
                'remarks' => isset($validated['remarks']) && !empty($validated['remarks']) ? $validated['remarks'] : 'No additional remarks.',
                'timestamp' => Carbon::now()->toDateTimeString(),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
    
            $document->save();
    
            return redirect()->back()->with('success', 'Document status updated to ' . $validated['approval_status'] . '.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403, 'You do not have permission to approve this document.');
        } catch (\Exception $e) {
            Log::error('Approval Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the document status.');
        }
    }
    
    /**
     * Handle tracking of a document.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function track(Request $request)
    {
        // Validate the incoming request data
        try {
            $validated = $request->validate([
                'tracking_number' => 'required|string|exists:documents,tracking_number',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            $document = Document::where('tracking_number', $validated['tracking_number'])->first();

            // Retrieve current status and details
            $status = $document->status;
            $approvalStatus = $document->approval_status;
            $details = json_decode($document->status_details, true);
            $trackingNumber = $document->tracking_number;
            $fileNames = [$document->file_path]; // Modify as per your actual implementation
            Log::info('Approval Status from DB: '.$document->approval_status);

            return response()->json([
                'success' => true,
                'status' => $status,
                'approval_status' => $approvalStatus, 
                'tracking_number' => $trackingNumber,
                'details' => $details,
                'file_names' => $fileNames,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Document Tracking Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while tracking your document.',
            ], 500);
        }
    }

    /**
     * View document by Document ID (Authenticated Users).
     *
     * @param  \App\Models\Document  $document
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function viewDocument(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File does not exist.');
        }
    
        // Temporarily bypass user permission check:
        return response()->file(storage_path('app/public/' . $document->file_path));
    }
    

    

    /**
     * View document by Tracking Number (Unauthenticated Users).
     *
     * @param  string  $tracking_number
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function viewDocumentByTracking($tracking_number)
    {
        // Retrieve the document using the tracking number
        $document = Document::where('tracking_number', $tracking_number)->first();

        if (!$document) {
            abort(404, 'Document not found.');
        }

        // Check if the file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File does not exist.');
        }

        // Optionally, you can log the access or perform other checks here

        return response()->file(storage_path('app/public/' . $document->file_path));
    }
    public function assignTracking(Request $request)
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|exists:documents,tracking_number',
            'user_id' => 'required|integer|exists:users,id',
        ]);
    
        try {
            $document = Document::where('tracking_number', $validated['tracking_number'])->firstOrFail();
            $user = User::findOrFail($validated['user_id']);
    
            // Update document details…
            $document->routed_to = $user->id;
            $document->status = 'Redirected';
            $document->status_details = json_encode([
                'message'   => 'Document redirected to ' . $user->name . '.',
                'timestamp' => now()->toDateTimeString(),
            ]);
            $document->save();
    
            // Dispatch the notification
            $user->notify(new DocumentStatusNotification($document));
            Log::info("DocumentStatusNotification sent to user {$user->id}");

            return redirect()->back()->with('success', 'Tracking number assigned and notification sent.');
        } catch (\Exception $e) {
            Log::error('Error assigning tracking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }
    
    public function approveSingle(Request $request, $id)
    {
        $validated = $request->validate([
            'approval_status' => 'required|string|in:Accepted,Rejected',
        ]);
    
        try {
            $document = Document::findOrFail($id);
    
            $document->approval_status = $validated['approval_status'];
            $document->status = ($validated['approval_status'] === 'Accepted') ? 'Approved' : 'Rejected';
            $document->status_details = json_encode([
                'message' => 'Document has been ' . strtolower($validated['approval_status']),
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);
            $document->save();
    
            // If accepted, create an Incoming record
            if($document->approval_status === 'Accepted'){
                $this->createIncomingFromDocument($document);
            }
    
            $this->sendApprovalEmail($document);
    
            return redirect()->back()->with('success', "Document #{$document->id} has been {$validated['approval_status']}.");
        } catch (\Exception $e) {
            Log::error('Single Approval Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error approving/rejecting document.');
        }
    }
    public function details($id)
    {
        $document = Document::findOrFail($id);
        $user = auth()->user();
    
        if ($user->hasRole('admin')) {
            return view('admin.documents._details', compact('document'));
        } elseif ($user->hasRole('Records')) {
            return view('records.documents._details', compact('document'));
        } elseif ($user->hasRole('RegionalDirector')) {
            return view('regional_director.documents._details', compact('document'));
        } elseif ($user->hasRole('Supervisor')) {
            return view('supervisor.documents._details', compact('document'));
        } elseif ($user->hasRole('HR')) {
            return view('hr.documents._details', compact('document'));
        } elseif ($user->hasRole('Technical')) {
            return view('technical.documents._details', compact('document'));
        } elseif ($user->hasRole('Unifast')) {
            return view('unifast.documents._details', compact('document'));
        } elseif ($user->hasRole('Accounting')) {
            return view('accounting.documents._details', compact('document'));
        } else {
            return view('user.documents._details', compact('document'));
        }
    }
    

public function bulkApproval(Request $request)
{
    $validated = $request->validate([
        'doc_ids' => 'required|array',
        'doc_ids.*' => 'integer|exists:documents,id',
        'approval_status' => 'required|string|in:Accepted,Rejected',
    ]);

    try {
        $documents = Document::whereIn('id', $validated['doc_ids'])->get();
        foreach ($documents as $doc) {
            $doc->approval_status = $validated['approval_status'];
            $doc->status = ($validated['approval_status'] === 'Accepted') ? 'Approved' : 'Rejected';
            $doc->status_details = json_encode([
                'message' => 'Document has been ' . strtolower($validated['approval_status']),
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);
            $doc->save();
        
            // If accepted, create an Incoming record
            if($doc->approval_status === 'Accepted'){
                $this->createIncomingFromDocument($doc);
            }
        
            $this->sendApprovalEmail($doc);
        }

        return redirect()->back()->with('success', 'Bulk approval/rejection completed successfully.');
    } catch (\Exception $e) {
        Log::error('Bulk Approval Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error during bulk approval/rejection.');
    }
}
private function sendApprovalEmail(Document $document)
{
    // For production, verify that $document->email is valid
    if (!$document->email) {
        return; 
    }

    try {
        Mail::to($document->email)->send(new DocumentStatusMail($document));
    } catch (\Exception $e) {
        Log::error('Email Sending Error: ' . $e->getMessage());
        // Not critical enough to fail the entire request
    }
}
private function createIncomingFromDocument(Document $document)
{
    // Avoid duplicate Incoming records based on the document's tracking_number.
    if (Incoming::where('reference_number', $document->tracking_number)->exists()) {
        return;
    }

    // For example, assume the record is released now:
    $dateReleased = Carbon::now();

    // Calculate the quarter:
    $month = $dateReleased->month;
    // For January-March: quarter 1, April-June: quarter 2, etc.
    $quarter = intdiv($month - 1, 3) + 1; // Results in 1, 2, 3, or 4

    // Generate your sequential "no" (if applicable)
    $lastIncoming = Incoming::orderBy('id', 'desc')->first();
    $lastNo = ($lastIncoming && $lastIncoming->no) ? intval($lastIncoming->no) : 0;
    $newNo = sprintf('%04d', $lastNo + 1);

    Incoming::create([
        'reference_number' => $document->tracking_number,
        'date_received'    => Carbon::now(),
        'time_emailed'     => Carbon::now()->toTimeString(),
        'sender_name'      => $document->full_name,
        'sender_email'     => $document->email,
        'subject'          => $document->document_type,
        'remarks'          => $document->status_details,
        'year'             => Carbon::now()->year,
        'chedrix_2025'     => 'CHEDRIX-2025', // Fixed value field
        'no'               => $newNo,          // Sequential, zero-padded number
        'quarter'          => $quarter,        // Persisted quarter (1, 2, 3, or 4)
        // ... (any other fields)
    ]);
}
public function getDocuments(Request $request)
{
    $user = auth()->user();
    $query = Document::with(['user', 'routedUser']);

    if (!$user->hasRole(['admin', 'Records', 'RegionalDirector'])) {
        $query->where('routed_to', $user->id);
    }

    if ($request->filled('status') && $request->input('status') !== '') {
        if ($request->input('status') === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($request->input('status') === 'Archived') {
            $query->where('status', 'Archived');
        } else {
            $query->where('status', $request->input('status'));
        }
    }

    if ($request->filled('role') && $request->input('role') !== '') {
        $query->whereHas('user', function ($q) use ($request) {
            $q->where('role', $request->input('role'));
        });
    }

    return DataTables::of($query)
        ->addColumn('checkbox', fn($doc) => '<input type="checkbox" name="doc_ids[]" value="' . $doc->id . '" />')
        ->addColumn('routed_to', fn($doc) => $doc->routedUser ? $doc->routedUser->name : 'Not Assigned')
        ->addColumn('remarks', function($doc) {
            $details = json_decode($doc->status_details, true);
            return $details['remarks'] ?? 'No remarks';
        })
        ->addColumn('created_at', fn($doc) => Carbon::parse($doc->created_at)->format('M d, Y h:i A'))
        ->addColumn('actions', function ($doc) use ($user) {
            $editBtn = !$user->hasRole('Records')
                ? '<button type="button" class="btn btn-sm btn-warning edit-document" data-id="' . $doc->id . '" title="Edit Document">
                        <i class="fa fa-pencil-alt"></i>
                   </button>'
                : '';
            $viewBtn = '<button type="button" class="btn btn-sm btn-info view-document" data-id="' . $doc->id . '" title="View Document">
                            <i class="fa fa-eye"></i>
                        </button>';
            return $editBtn . ' ' . $viewBtn;
        })
        ->editColumn('status_details', function ($doc) {
            $details = json_decode($doc->status_details, true);
            return is_array($details) ? "Message: " . ($details['message'] ?? 'No message') : $doc->status_details;
        })
        ->rawColumns(['checkbox', 'actions', 'routed_to'])
        ->make(true);
}

public function update(Request $request, $id)
{
    $document = Document::findOrFail($id);

    $validated = $request->validate([
        'approval_status' => 'nullable|string|in:Accepted,Rejected',
        'remarks'         => 'nullable|string',
        'routed_to'       => 'nullable|exists:users,id',
    ]);

    // Check if the update includes an approval decision
    if ($request->has('approval_status')) {
        // If the document is being approved or rejected, update accordingly.
        if (in_array($validated['approval_status'], ['Accepted', 'Rejected'])) {
            $document->approval_status = $validated['approval_status'];
            $document->status = ($validated['approval_status'] === 'Accepted') ? 'Approved' : 'Rejected';
        } else {
            // If approval_status is not valid or is missing, you can mark it as "Redirected"
            $document->approval_status = null;
            $document->status = 'Redirected';
        }
    }

    // Update status details for logging and tracking
    $document->status_details = json_encode([
        'message'   => 'Document has been ' . strtolower($document->status),
        'remarks'   => $validated['remarks'] ?? '',
        'timestamp' => now()->toDateTimeString(),
    ]);

    // If a routed user is provided, update the routing fields and mark as "Redirected"
    if (!empty($validated['routed_to'])) {
        $user = User::findOrFail($validated['routed_to']);
        $document->routed_to = $user->id;
        $document->email = $user->email;
        $document->full_name = $user->name;
        $document->status = 'Redirected';
    }

    $document->save();

    // Check if the new status requires sending an email notification.
    // You can adjust this array as needed (for example, if you want to notify on "Released" too).
    if (in_array($document->status, ['Approved', 'Rejected', 'Redirected', 'Released'])) {
        $this->sendApprovalEmail($document);
    }

    return response()->json([
        'success' => true,
        'message' => 'Document updated successfully.',
    ]);
}



public function editModal($id)
{
    $document = Document::findOrFail($id);
    $users = User::orderBy('name')->get();
    $user = auth()->user();
    $recordsUser = User::role('Records')->first();

    if (!$recordsUser) {
        throw new \Exception('No default Records user found.');
    }

    if ($user->hasRole('admin')) {
        return view('admin.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('Records')) {
        return view('records.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('RegionalDirector')) {
        return view('regional_director.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('Supervisor')) {
        return view('supervisor.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('HR')) {
        return view('hr.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('Technical')) {
        return view('technical.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('Unifast')) {
        return view('unifast.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } elseif ($user->hasRole('Accounting')) {
        return view('accounting.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    } else {
        return view('user.documents.edit_modal', compact('document', 'users', 'recordsUser'));
    }
}
public function release($id)
{
    $document = Document::findOrFail($id);

    // Optionally, add authorization logic here

    // Update the document's status to "Released"
    $document->status = 'Released';
    // You might want to update the approval_status as well if needed
    $document->approval_status = 'Released';
    $document->status_details = json_encode([
        'message'   => 'Document released to Records by supervisor.',
        'timestamp' => now()->toDateTimeString(),
    ]);
    $document->save();

    return response()->json(['message' => 'Document successfully released.']);
}


}