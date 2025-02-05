<?php

namespace App\Http\Controllers\RegionalDirector;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Str;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // **Add this line**
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

        return view('documents.index', compact('documents', 'users'));
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
        
        return view('documents.user_documents', [
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

        $user = auth()->user(); // Authenticated user

        // Additional safety check
        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Security check - ensure user has permission to view this document
        if ($user->hasRole('admin') || $document->email === $user->email) {
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
        // Validate the incoming request data
        $validated = $request->validate([
            'tracking_number' => 'required|string|exists:documents,tracking_number',
            'user_id' => 'required|integer|exists:users,id',
        ]);
    
        try {
            // Retrieve the document
            $document = Document::where('tracking_number', $validated['tracking_number'])->firstOrFail();
    
            // Authorization Check: Ensure the user can assign tracking to this document
            $this->authorize('assignTracking', $document);
    
            // Retrieve the user to assign
            $user = User::findOrFail($validated['user_id']);
    
            // Assign the tracking to the selected user
            $document->email = $user->email;
            $document->full_name = $user->name;
    
            // Update status to 'Redirected'
            $document->status = 'Redirected';
            $document->status_details = json_encode([
                'message' => 'Document redirected to ' . $document->full_name . '.',
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);
    
            $document->save();
    
            return redirect()->back()->with('success', 'Tracking number assigned and status updated to Redirected successfully.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(403, 'You do not have permission to assign tracking to this document.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Assign Tracking Error: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'An error occurred while assigning the tracking number.');
        }
    }
    
}
