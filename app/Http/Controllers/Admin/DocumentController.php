<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role; // Ensure Spatie roles are used (if applicable)
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetailedDocumentsExport;
class DocumentController extends Controller
{
    
    /**
     * Display a listing of the documents (For Admins/Users).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */


    public function index(Request $request)
    {
        // Optional: Implement search and filtering
        $query = Document::with('user'); // Eager load the user relationship

        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->input('document_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Add more filters as needed

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);

        // Fetch all users for the filter dropdown
        $users = User::orderBy('name')->get();

        return view('admin.documents.index', compact('documents', 'users'));
    }

    /**
     * Display documents for a specific user by email.
     *
     * @param  string  $email
     * @return \Illuminate\View\View
     */
    public function userDocuments($email)
    {
        // Get the documents for the email
        $documents = Document::where('email', $email)
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        
        // Get the first document to get user details
        $firstDocument = $documents->first();
        
        // Fetch all users for the modal dropdown
        $users = User::orderBy('name')->get();
        
        return view('admin.documents.user_documents', [
            'documents' => $documents,
            'userEmail' => $email,
            'userName' => $firstDocument ? $firstDocument->full_name : 'Unknown User',
            'users' => $users, // Pass users to the view
        ]);
    }
    
    /**
     * Handle the document upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    
public function getReportStatistics()
{
    // Force JSON output regardless of what happens
    header('Content-Type: application/json');
    
    try {
        $currentYear = Carbon::now()->year;

        // Quarterly Document Statistics
        $quarterlyDocuments = Document::selectRaw('
            QUARTER(created_at) as quarter, 
            COUNT(*) as total_documents,
            SUM(CASE WHEN approval_status = "Accepted" THEN 1 ELSE 0 END) as accepted_documents,
            SUM(CASE WHEN approval_status = "Rejected" THEN 1 ELSE 0 END) as rejected_documents,
            SUM(CASE WHEN status = "Archived" THEN 1 ELSE 0 END) as archived_documents
        ')
        ->whereYear('created_at', $currentYear)
        ->groupBy('quarter')
        ->orderBy('quarter')
        ->get();

        // Document Type Distribution
        $documentTypeDistribution = Document::selectRaw('
            document_type, 
            COUNT(*) as total_count,
            SUM(CASE WHEN approval_status = "Accepted" THEN 1 ELSE 0 END) as accepted_count
        ')
        ->groupBy('document_type')
        ->get();

        // Force JSON direct output, bypass Laravel's response system
        echo json_encode([
            'quarterlyDocuments' => $quarterlyDocuments->toArray(),
            'documentTypeDistribution' => $documentTypeDistribution->toArray(),
            'currentYear' => $currentYear
        ]);
        exit; // Terminate execution to prevent any further HTML output
        
    } catch (\Exception $e) {
        Log::error('Report Statistics Error: ' . $e->getMessage());
        
        // Return error as direct JSON
        echo json_encode([
            'error' => true,
            'message' => 'An error occurred while fetching report statistics',
            'details' => $e->getMessage()
        ]);
        exit;
    }
}
public function generateEnhancedReport(Request $request)
{
    $validated = $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'nullable|string',
        'document_type' => 'nullable|string',
        'export_type' => 'required|in:pdf,excel',
        'quarter' => 'nullable|integer|between:1,4'
    ]);

    $query = Document::query();

    // Apply date range filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            Carbon::parse($validated['start_date'])->startOfDay(), 
            Carbon::parse($validated['end_date'])->endOfDay()
        ]);
    }

    // Apply quarter filter
    if ($request->filled('quarter')) {
        $query->whereRaw('QUARTER(created_at) = ?', [$validated['quarter']]);
    }

    // Apply status filter
    if ($request->filled('status')) {
        $query->where('status', $validated['status']);
    }

    // Apply document type filter
    if ($request->filled('document_type')) {
        $query->where('document_type', $validated['document_type']);
    }

    $documents = $query->get();

    // Calculate additional statistics for the report
    $statistics = [
        'total_documents' => $documents->count(),
        'accepted_documents' => $documents->where('approval_status', 'Accepted')->count(),
        'rejected_documents' => $documents->where('approval_status', 'Rejected')->count(),
        'document_types' => $documents->groupBy('document_type')->map->count()->toArray()
    ];

    // Generate export based on type
    if ($validated['export_type'] === 'pdf') {
        return $this->exportPDF($documents, $statistics);
    } else {
        return $this->exportExcel($documents, $statistics);
    }
}

protected function exportPDF($documents, $statistics)
{
    $pdf = PDF::loadView('exports.documents_detailed_pdf', [
        'documents' => $documents,
        'statistics' => $statistics,
        'reportTitle' => 'Detailed Document Management Report',
        'generatedAt' => Carbon::now()->format('Y-m-d H:i:s')
    ]);

    return $pdf->download('document_detailed_report_' . Carbon::now()->format('YmdHis') . '.pdf');
}

protected function exportExcel($documents, $statistics)
{
    // You might want to create a more detailed ExportClass
    return Excel::download(new DetailedDocumentsExport($documents, $statistics), 'document_detailed_report_' . Carbon::now()->format('YmdHis') . '.xlsx');
}

    public function upload(Request $request)
    {
        // Validate the incoming request data
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'full_name' => 'required|string|max:255',
                'document_type' => 'required|string|in:CAV,SO,IP,GR,COPC',
                'document.*' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120', // 5MB max per file
                'agree_terms' => 'accepted',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            $uploadedFiles = $request->file('document');
            $documents = [];

            foreach ($uploadedFiles as $file) {
                // Generate a unique Document ID
                $documentId = strtoupper(Str::random(10));

                // Generate a unique Tracking Number
                $trackingNumber = strtoupper(Str::random(12));

                // Ensure the tracking number is unique
                while (Document::where('tracking_number', $trackingNumber)->exists()) {
                    $trackingNumber = strtoupper(Str::random(12));
                }

                // Store the file in the 'public/documents/{document_id}' directory
                $path = $file->storeAs('documents/' . $documentId, $file->getClientOriginalName(), 'public');

                // Create a new Document record in the database
                $document = Document::create([
                    'document_id' => $documentId,
                    'tracking_number' => $trackingNumber,
                    'email' => $validated['email'],
                    'full_name' => $validated['full_name'],
                    'document_type' => $validated['document_type'],
                    'file_path' => $path,
                    'status' => 'Submitted',
                    'status_details' => json_encode([
                        'message' => 'Your document has been submitted.',
                        'timestamp' => Carbon::now()->toDateTimeString(),
                    ]),
                ]);

                $documents[] = [
                    'document_id' => $documentId,
                    'tracking_number' => $trackingNumber,
                ];
            }

            return response()->json([
                'success' => true,
                'documents' => $documents,
                'message' => 'Documents uploaded successfully.',
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Document Upload Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading your documents.',
            ], 500);
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
            $details = json_decode($document->status_details, true);
            $trackingNumber = $document->tracking_number;
            $fileNames = [$document->file_path]; // Modify as per your actual implementation

            return response()->json([
                'success' => true,
                'status' => $status,
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

        // Check if the file exists
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File does not exist.');
        }

        $user = Auth::user();

        // Additional safety check
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Security check - ensure user has permission to view this document
        if ($user->role === 'admin' || $document->email === $user->email) {
            return response()->file(storage_path('app/public/' . $document->file_path));
        }

        abort(403, 'You do not have permission to view this document.');
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
        // Retrieve the document and user
        $document = Document::where('tracking_number', $validated['tracking_number'])->firstOrFail();
        $user = User::findOrFail($validated['user_id']);

        // Update document details
        $document->email = $user->email;
        $document->full_name = $user->name;
        $document->status = 'Redirected';
        $document->status_details = json_encode([
            'message'   => 'Document redirected to ' . $user->name . '.',
            'timestamp' => Carbon::now()->toDateTimeString(),
        ]);
        $document->save();

        // Dispatch the notification to the user:
        $user->notify(new \App\Notifications\DocumentStatusNotification($document));

        return redirect()->back()->with('success', 'Tracking number assigned and status updated to Redirected successfully.');
    } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
        abort(403, 'You do not have permission to assign tracking to this document.');
    } catch (\Exception $e) {
        Log::error('Assign Tracking Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while assigning the tracking number.');
    }
}

    
}
