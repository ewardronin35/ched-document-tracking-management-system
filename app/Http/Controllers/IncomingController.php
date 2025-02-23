<?php

namespace App\Http\Controllers;

use App\Models\Incoming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\IncomingImport;
use App\Models\Outgoing;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
class IncomingController extends Controller
{
    /**
     * Display a listing of the incoming communications.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        $incoming = Incoming::select('incoming.*');
    
        return DataTables::of($incoming)
            ->addColumn('actions', function ($incoming) {
                return view('partials.actions', [
                    'model' => $incoming,
                    'route' => 'incoming',
                ])->render();
            })
            ->rawColumns(['actions']) // Ensure actions column is treated as raw HTML
            ->make(true);
    }
    protected function getCurrentPrefix()
    {
        $routeName = Route::currentRouteName(); // e.g., 'admin.documents.outgoings.index'
        $parts = explode('.', $routeName);
        return $parts[0] ?? null; // 'admin' or 'records'
    }
    public function index()
    {
        // Retrieve all incoming records, possibly with pagination
        $incoming = Incoming::orderBy('date_received', 'desc')->paginate(15);

        // Return a view or JSON response
        // Example for JSON response:
        return response()->json($incoming);
    }

    /**
     * Show the form for creating a new incoming communication.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // If you're using Blade templates, return the create view
        // Example:
        // return view('incoming.create');

        // For API-based applications, you might not need this method
    }

    /**
     * Store a newly created incoming communication in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{   
    // Validate the incoming request.
    // We assume the client auto-fills these fields.
    $validated = $request->validate([
        'reference_number' => 'nullable', // now optional
        'date_received'    => 'nullable|date',
        'time_emailed'     => 'nullable',
        'sender_name'      => 'nullable|string|max:255',
        'sender_email'     => 'nullable|email|max:255',
        'subject'          => 'nullable|string|max:255',
        'remarks'          => 'nullable|string',
        'date_time_routed' => 'nullable|date',
        'routed_to'        => 'nullable|string|max:255',
        'No'               => 'nullable|string', // auto-filled
        'year'             => 'nullable|integer|digits:4',
        'location'         => 'nullable|string|max:255',
        'chedrix_2025'     => 'nullable|string',
        'quarter'          => 'required|integer|min:1|max:4',
    ]);
    
    // Use client-supplied defaults if available; otherwise, set fallback defaults.
    if (!isset($validated['chedrix_2025']) || empty($validated['chedrix_2025'])) {
        $validated['chedrix_2025'] = 'CHEDRIX-2025';
    }
    if (!isset($validated['quarter'])) {
        // Fallback: compute quarter from date_received.
        $dateReceived = Carbon::parse($validated['date_received']);
        $validated['quarter'] = intdiv($dateReceived->month - 1, 3) + 1;
    }
    if (!isset($validated['No']) || empty($validated['No'])) {
        // Fallback: generate a sequential number.
        $lastIncoming = Incoming::orderBy('id', 'desc')->first();
        $lastNo = ($lastIncoming && $lastIncoming->no) ? intval($lastIncoming->no) : 0;
        $validated['No'] = sprintf('%04d', $lastNo + 1);
    }

    // Create the incoming record using the validated data.
    $incoming = Incoming::create($validated);

    return response()->json([
        'message' => 'Incoming communication created successfully.',
        'data'    => $incoming
    ], 201);
}

    
    /**
     * Display the specified incoming communication.
     *
     * @param  \App\Models\Incoming  $incoming
     * @return \Illuminate\Http\Response
     */
    public function show(Incoming $incoming)
    {
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.incomings.show', compact('incoming'));
        } elseif ($prefix === 'records') {
            return view('records.incomings.show', compact('incoming'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for editing the specified incoming communication.
     *
     * @param  \App\Models\Incoming  $incoming
     * @return \Illuminate\Http\Response
     */
    public function edit(Incoming $incoming)
    {
        // If you're using Blade templates, return the edit view
        // Example:
        // return view('incoming.edit', compact('incoming'));

        // For API-based applications, you might not need this method
    }

    /**
     * Update the specified incoming communication in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Incoming  $incoming
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info('Incoming Update Request:', ['Incoming ID' => $id, 'Request Data' => $request->all()]);
    
        // Retrieve the existing record instead of relying on route model binding
        $incoming = Incoming::find($id);
    
        if (!$incoming) {
            return response()->json([
                'message' => 'Incoming record not found.',
            ], 404);
        }
    
        $validated = $request->validate([
            'reference_number' => 'nullable|string',
            'chedrix_2025'     => 'nullable|string',
            'location'         => 'nullable|string',
            'No'               => 'required|string',
            'date_received'    => 'required|date',
            'time_emailed'     => 'nullable|string',
            'sender_name'      => 'required|string|max:255',
            'sender_email'     => 'required|email|max:255',
            'subject'          => 'required|string|max:255',
            'remarks'          => 'nullable|string',
            'date_time_routed' => 'nullable|date',
            'routed_to'        => 'nullable|string|max:255',
            'date_acted_by_es' => 'nullable|date',
            'outgoing_details' => 'nullable|string',
            'year'             => 'required|integer|digits:4',
            'outgoing_id'      => 'nullable|integer',
            'date_released'    => 'nullable|date',
        ]);
    
        // Ensure `quarter` is included
        if ($request->has('quarter')) {
            $validated['quarter'] = $request->input('quarter');
        } else {
            // Auto-calculate the quarter from `date_received`
            $dateReceived = Carbon::parse($validated['date_received']);
            $validated['quarter'] = intdiv($dateReceived->month - 1, 3) + 1;
        }
    
        // Assign new values
        $incoming->update($validated);
    
        Log::info('Incoming successfully updated:', $incoming->toArray());
    
        return response()->json([
            'message' => 'Incoming updated successfully.',
            'data'    => $incoming
        ]);
    }
    
    
    
    
    
    /**
     * Remove the specified incoming communication from storage.
     *
     * @param  \App\Models\Incoming  $incoming
     * @return \Illuminate\Http\Response
     */
    public function destroy(Incoming $incoming)
    {
        // Delete the Incoming record
        $incoming->delete();

        // Return a response
        return response()->json([
            'message' => 'Incoming communication deleted successfully.'
        ]);
    }

    public function import(Request $request)
    {
        Log::info('Import request started.');

        // Check if any file was sent
        if (!$request->hasFile('incoming_filepond')) {
            Log::error('No file was uploaded.');
            return response()->json(['error' => 'No file was uploaded.'], 422);
        }
        
        $files = $request->file('incoming_filepond');
        if (!is_array($files)) {
            $files = [$files];
        }
        
        $errors = [];
        foreach ($files as $file) {
            Log::info('Processing file: ' . $file->getClientOriginalName());
            $extension = strtolower($file->getClientOriginalExtension());
            if (!$extension) {
                $errors[] = 'One of the files does not have a valid extension.';
                Log::error('File has no valid extension.');
                continue;
            }
        
            switch ($extension) {
                case 'csv':
                    $readerType = \Maatwebsite\Excel\Excel::CSV;
                    break;
                case 'xls':
                    $readerType = \Maatwebsite\Excel\Excel::XLS;
                    break;
                case 'xlsx':
                    $readerType = \Maatwebsite\Excel\Excel::XLSX;
                    break;
                case 'txt':
                    $readerType = \Maatwebsite\Excel\Excel::CSV;
                    break;
                default:
                    $errors[] = 'Unsupported file type: ' . $extension;
                    Log::error('Unsupported file type: ' . $extension);
                    continue 2;
            }
        
            try {
                Log::info('Importing file: ' . $file->getClientOriginalName() . ' using reader type: ' . $readerType);
                Excel::import(new IncomingImport, $file->getRealPath(), null, $readerType);
                Log::info('File imported: ' . $file->getClientOriginalName());
            } catch (\Exception $e) {
                Log::error("Error importing file: " . $e->getMessage());
                $errors[] = 'Error importing one of the files: ' . $e->getMessage();
            }
        }
        
        if (count($errors)) {
            Log::error('Import completed with errors: ' . implode(', ', $errors));
            return response()->json(['error' => implode(', ', $errors)], 500);
        }
        
        Log::info('Import completed successfully.');
        return response()->json(['success' => 'File(s) imported successfully.'], 200);
    }
    
    
protected function mapSubjectToCategory($subject)
    {
        // Check if the subject exists in the mapping
        if (array_key_exists($subject, $this->subjectCategoryMap)) {
            return $this->subjectCategoryMap[$subject];
        }

        // Default category if subject not found in mapping
        return 'General';
    }
    protected $subjectCategoryMap = [
        'IT Support'           => 'Information Technology',
        'Human Resources'     => 'Human Resources',
        'Finance Inquiry'     => 'Finance',
        'General Inquiry'     => 'General',
        'Legal Notice'        => 'Legal',
        'Marketing Proposal'  => 'Marketing',
        // Add more mappings as needed
    ];
    public function release(Request $request, Incoming $incoming)
    {
        Log::info('IncomingController@release called for ID: ' . $incoming->id);
    
        $validator = Validator::make($request->all(), [
            'date_released' => 'required|date',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors.',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        DB::beginTransaction();
    
        try {
            $incoming->date_released = Carbon::parse($request->date_released);
            $incoming->save();
    
            $month   = $incoming->date_released->month;
            $quarter = intdiv($month - 1, 3) + 1;
            Log::info('Incoming released successfully:', $incoming->toArray());
    
            $category = $this->mapSubjectToCategory($incoming->subject);
    
            // Only create outgoing if one does not already exist.
            if (!$incoming->outgoing) {
                $outgoing = Outgoing::create([
                    // If you no longer need control_no, you can remove it.
                    'date_released'     => $incoming->date_released,
                    'category'          => $category,
                    'addressed_to'      => $incoming->sender_name,
                    'email'             => $incoming->sender_email,
                    'subject_of_letter' => $incoming->subject,
                    'remarks'           => $incoming->remarks,
                    'libcap_no'         => null,
                    'status'            => 'Released',
                    'chedrix_2025'      => 'CHEDRIX-2025',
                    'o'                 => 'O',
                    'incoming_id'       => $incoming->id,
                    'quarter'           => $quarter,
                ]);
    
                $incoming->outgoing_id = $outgoing->id;
                $incoming->save();
                Log::info('Outgoing record created successfully:', $outgoing->toArray());
            } else {
                Log::warning('Outgoing record already exists for Incoming ID: ' . $incoming->id);
            }
    
            DB::commit();
    
            $data = $incoming->fresh()->load('outgoing');
            $data->quarter = $quarter;
    
            return response()->json([
                'message' => 'Incoming record released successfully.',
                'data'    => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error releasing Incoming record:', ['error' => $e->getMessage()]);
    
            return response()->json([
                'message' => 'Failed to release Incoming record.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    
    
}    
