<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // **Add this line**

class DocumentController extends Controller
{
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
}
