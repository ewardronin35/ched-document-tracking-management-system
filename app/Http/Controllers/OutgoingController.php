<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use App\Models\Incoming;
use App\Models\Programs;
use App\Models\Majors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OutgoingImport;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf; // Ensure you have barryvdh/laravel-dompdf installed
use App\Exports\OutgoingsExport;
use App\Exports\OutgoingsPdfExport;
use App\Exports\DocumentTypeExport;
use App\Exports\StatusExport;
use App\Exports\QuarterlyReportExport;
use Illuminate\Support\Facades\View; // Add this import for View facade
use Illuminate\Support\Facades\File; // Add this import for File facade

class OutgoingController extends Controller
{
    /**
     * Helper method to determine the current prefix (e.g., 'admin' or 'records').
     */
    protected function getCurrentPrefix()
    {
        $routeName = Route::currentRouteName(); // e.g. 'admin.outgoings.index'
        $parts = explode('.', $routeName);
        return $parts[0] ?? null; // 'admin' or 'records'
    }

    /**
     * Display a listing of the outgoings.
     */
    public function index()
    {
        
        // 1) All Outgoings
        $outgoings = Outgoing::with('incoming')->get()->map(function ($item) {
            return [
                // Keep the actual DB id for references/updates (not displayed directly).
                'id'                => $item->id,

                // Show the user a zero-padded "No."
                'No'                => $item->no_formatted,

                'date_released'     => $item->date_released
                                          ? Carbon::parse($item->date_released)->format('Y-m-d')
                                          : null,

                'category'          => $item->category,
                'addressed_to'      => $item->addressed_to,
                'email'             => $item->email,
                'subject_of_letter' => $item->subject_of_letter,
                'remarks'           => $item->remarks,
                'libcap_no'         => $item->libcap_no,
                'status'            => $item->status,
                'chedrix_2025'      => $item->chedrix_2025,
                'o'                 => $item->o,
                'incoming_id'       => $item->incoming_id,
                'travel_date'       => $item->travel_date,
                'es_in_charge'      => $item->es_in_charge,
                'quarter_label' => $item->quarter_label, // calls getQuarterLabelAttribute()
                'quarter'       => $item->quarter,       // the raw integer from DB
            ];
        })->toArray();

        // 2) Incomings
        $incomings = Incoming::all()->map(function ($item) {
            return [
                'id'                => $item->id,
                'quarter'           => $item->quarter,
                'chedrix_2025'      => $item->chedrix_2025,
                'location'          => $item->location,
                'No'                => $item->No,
                'reference_number'  => $item->reference_number,
                'date_received'     => $item->date_received
                                          ? Carbon::parse($item->date_received)->format('Y-m-d')
                                          : null,
                'time_emailed'      => $item->time_emailed
                                          ? Carbon::parse($item->time_emailed)->format('H:i:s')
                                          : null,
                'sender_name'       => $item->sender_name,
                'sender_email'      => $item->sender_email,
                'subject'           => $item->subject,
                'remarks'           => $item->remarks,
                'date_time_routed'  => $item->date_time_routed
                                          ? Carbon::parse($item->date_time_routed)->format('Y-m-d H:i:s')
                                          : null,
                'routed_to'         => $item->routed_to,
                'date_acted_by_es'  => $item->date_acted_by_es
                                          ? Carbon::parse($item->date_acted_by_es)->format('Y-m-d H:i:s')
                                          : null,
                'outgoing_details'  => $item->outgoing_details,
                'year'              => $item->year,
                'outgoing_id'       => $item->outgoing_id,
                'date_released'     => $item->date_released
                                          ? Carbon::parse($item->date_released)->format('Y-m-d')
                                          : null,
            ];
        })->toArray();

        // 3) Travel Memos => category='TRAVEL ORDER'
        $travelMemos = Outgoing::where('category', 'TRAVEL ORDER')->get()->map(function ($item) {
            return [
                'id'            => $item->id,
                'No' => $item->no_formatted,
                'quarter_label' => $item->quarter_label,
                'o'             => $item->o,
                'date_released' => $item->date_released,
                'addressed_to'  => $item->addressed_to,
                'email'         => $item->email,
                'travel_date'   => $item->travel_date,
                'es_in_charge'  => $item->es_in_charge,
                'category'      => $item->category,
            ];
        })->toArray();

        // 4) O No. => category='ONO'
        $onoOutgoings = Outgoing::where('category', 'ONO')->get()->map(function ($item) {
            return [
                'id'            => $item->id,
                'No' => $item->no_formatted, 
                'o'             => $item->o,
                'date_released' => $item->date_released,
                'addressed_to'  => $item->addressed_to,
                'subject'       => $item->subject_of_letter,
                'remarks'       => $item->remarks,
                'libcap_no'     => $item->libcap_no,
                'status'        => $item->status,
                'category'      => $item->category,
            ];
        })->toArray();

        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.outgoings.index', compact('outgoings', 'incomings', 'travelMemos', 'onoOutgoings'));
        } elseif ($prefix === 'records') {
            return view('records.outgoings.index', compact('outgoings', 'incomings', 'travelMemos', 'onoOutgoings'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for creating a new outgoing (if you have a separate create form).
     */
    public function create()
    {
        $programs = Programs::all();
        $majors = Majors::all();
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.outgoings.create', compact('programs', 'majors'));
        } elseif ($prefix === 'records') {
            return view('records.outgoings.create', compact('programs', 'majors'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Store a newly created outgoing in storage (used by Handsontable via JSON or a standard form).
     */
    public function store(Request $request)
    {
        Log::info('OutgoingController@store called with data:', $request->all());

        if (!$request->filled('date_released')) {
            // e.g., set it to today's date (or any default you like).
            $request->merge(['date_released' => Carbon::now()->format('Y-m-d')]);
        }
    
        // 2) Compute the quarter from date_released
        //    This ensures quarter is set even if user never provides it.
        $month   = Carbon::parse($request->date_released)->month; // e.g. 1..12
        $quarter = ceil($month / 3);  // 1..4
        $request->merge(['quarter' => $quarter]);
    
        // 3) Provide fallback defaults for other fields (as before)
        if (!$request->filled('chedrix_2025')) {
            $request->merge(['chedrix_2025' => 'CHEDRIX-2025']);
        }
        if (!$request->filled('o')) {
            $request->merge(['o' => 'O']);
        }
        if (!$request->filled('status')) {
            $request->merge(['status' => 'Pending']);
        }
        $validated = $request->validate([
            'date_released'     => 'nullable|date',   // now guaranteed to be set
            'quarter'           => 'nullable|integer|min:1|max:4',
            'category'          => 'nullable|string',
            'addressed_to'      => 'nullable|string',
            'email'             => 'nullable|string',
            'subject_of_letter' => 'nullable|string',
            'remarks'           => 'nullable|string',
            'libcap_no'         => 'nullable|string',
            'status'            => 'nullable|string',
            'chedrix_2025'      => 'nullable|string',
            'o'                 => 'nullable|string',
            'no'                => 'nullable|string',
            'incoming_id'       => 'nullable|exists:incoming,id',
            'travel_date'       => 'nullable|date',
            'es_in_charge'      => 'nullable|string',
        ]);
    
        // 5) Create
        $outgoing = Outgoing::create($validated);
    
        // 6) Link to Incoming if present
        if ($outgoing->incoming_id) {
            $incoming = Incoming::find($outgoing->incoming_id);
            if ($incoming) {
                $incoming->outgoing_id = $outgoing->id;
                $incoming->save();
            }
        }
    
        Log::info('Outgoing created successfully:', $outgoing->toArray());
    
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Outgoing created successfully.',
                'data'    => $outgoing,
            ], 201);
        }
    
        $prefix = $this->getCurrentPrefix();
        return redirect()->route("{$prefix}.outgoings.index")
                         ->with('success', 'Outgoing created successfully.');
    }

    /**
     * Display a single outgoing (if you have a detail page).
     */
    public function show(Outgoing $outgoing)
    {
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.outgoings.show', compact('outgoing'));
        } elseif ($prefix === 'records') {
            return view('records.outgoings.show', compact('outgoing'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the edit form (if you have a standard edit page).
     */
    public function edit(Outgoing $outgoing)
    {
        $programs = Programs::all();
        $majors = Majors::all();
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.outgoings.edit', compact('outgoing', 'programs', 'majors'));
        } elseif ($prefix === 'records') {
            return view('records.outgoings.edit', compact('outgoing', 'programs', 'majors'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Update an existing outgoing.
     */
    public function update(Request $request, Outgoing $outgoing)
    {
        Log::info('OutgoingController@update called for ID: '.$outgoing->id);
        Log::info('Data received:', $request->all());

        // Provide defaults if they are missing
        if (!$request->filled('chedrix_2025')) {
            $request->merge(['chedrix_2025' => 'CHEDRIX-2025']);
        }
        if (!$request->filled('o')) {
            $request->merge(['o' => 'O']);
        }
        if (!$request->filled('status')) {
            $request->merge(['status' => 'Pending']);
        }

        // Validate
        $validated = $request->validate([
            'date_released'     => 'nullable|date',
            'category'          => 'nullable|string',
            'addressed_to'      => 'nullable|string',
            'email'             => 'nullable|string',
            'subject_of_letter' => 'nullable|string',
            'remarks'           => 'nullable|string',
            'libcap_no'         => 'nullable|string',
            'status'            => 'nullable|string',
            'chedrix_2025'      => 'nullable|string',
            'o'                 => 'nullable|string',
            'incoming_id'       => 'nullable|exists:incoming,id',
            'travel_date'       => 'nullable|date',
            'es_in_charge'      => 'nullable|string',
        ]);

        $outgoing->update($validated);

        // Re-link incoming if needed
        if ($outgoing->incoming_id) {
            $incoming = Incoming::find($outgoing->incoming_id);
            if ($incoming) {
                $incoming->outgoing_id = $outgoing->id;
                $incoming->save();
            }
        }

        Log::info('Outgoing updated successfully:', $outgoing->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Outgoing updated successfully.',
                'data'    => $outgoing,
            ]);
        }

        $prefix = $this->getCurrentPrefix();
        return redirect()->route("{$prefix}.outgoings.index")
                         ->with('success', 'Outgoing updated successfully.');
    }
    public function data()
    {
        $outgoings = Outgoing::with('incoming')->get();
        return response()->json($outgoings);
    }
    
    /**
     * Remove the specified outgoing from storage.
     */
    public function destroy(Outgoing $outgoing)
    {
        $outgoing->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Outgoing deleted successfully.',
            ]);
        }

        $prefix = $this->getCurrentPrefix();
        return redirect()->route("{$prefix}.outgoings.index")
                         ->with('success', 'Outgoing deleted successfully.');
    }

    /**
     * Import from CSV (if you still want to import but no longer have 'control_no' column).
     */
    public function import(Request $request)
    {
        Log::info('Outgoing import request started.');

        // Validate using the correct field name
        $request->validate([
            'outgoing_filepond' => 'required|file|mimes:csv,txt,xls,xlsx|max:2048',
        ]);

        // Check for the file using the same field name
        if (!$request->hasFile('outgoing_filepond')) {
            Log::error('No file was uploaded for outgoing import.');
            return redirect()->back()->with('error', 'No file was uploaded.');
        }

        $files = $request->file('outgoing_filepond');
        if (!is_array($files)) {
            $files = [$files];
        }

        $errors = [];
        foreach ($files as $file) {
            Log::info('Processing outgoing file: ' . $file->getClientOriginalName());
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
                Log::info('Importing outgoing file: ' . $file->getClientOriginalName() . ' using reader type: ' . $readerType);
                Excel::import(new OutgoingImport, $file->getRealPath(), null, $readerType);
                Log::info('Outgoing file imported: ' . $file->getClientOriginalName());
            } catch (\Exception $e) {
                Log::error("Error importing outgoing file: " . $e->getMessage());
                $errors[] = 'Error importing file: ' . $e->getMessage();
            }
        }

        if (count($errors)) {
            Log::error('Outgoing import completed with errors: ' . implode(', ', $errors));
            return redirect()->back()->with('error', implode(', ', $errors));
        }

        Log::info('Outgoing import completed successfully.');
        return redirect()->route('admin.outgoings.index')->with('success', 'File(s) imported successfully!');
    }
   
   /**
 * Get data for quarterly report - used for AJAX endpoint and initial page load
 */
private function getQuarterlyReportData($year = null, $specificQuarter = null, $docType = 'all')
{
    // Get the current year if not specified
    $year = $year ?? date('Y');
    
    // Base query for incoming and outgoing documents
    $incomingQuery = Incoming::whereYear('date_received', $year);
    $outgoingQuery = Outgoing::whereYear('date_released', $year);

    // If a specific quarter is specified
    if ($specificQuarter) {
        $incomingQuery->whereRaw('QUARTER(date_received) = ?', [$specificQuarter]);
        $outgoingQuery->whereRaw('QUARTER(date_released) = ?', [$specificQuarter]);
    }

    // If a specific document type is requested
    if ($docType === 'incoming') {
        $outgoingQuery = clone $outgoingQuery;
        $outgoingQuery->whereRaw('1 = 0'); // Ensures no outgoings are counted
    } elseif ($docType === 'outgoing') {
        $incomingQuery = clone $incomingQuery;
        $incomingQuery->whereRaw('1 = 0'); // Ensures no incomings are counted
    }

    // Count documents per quarter
    $incomingCounts = [];
    $outgoingCounts = [];
    $quarterlyDetails = [];

    for ($q = 1; $q <= 4; $q++) {
        // Skip quarters that don't match the specified quarter
        if ($specificQuarter && $q != $specificQuarter) {
            continue;
        }

        $inCount = (clone $incomingQuery)
            ->whereRaw('QUARTER(date_received) = ?', [$q])
            ->count();
        $outCount = (clone $outgoingQuery)
            ->whereRaw('QUARTER(date_released) = ?', [$q])
            ->count();

        $incomingCounts[] = $inCount;
        $outgoingCounts[] = $outCount;

        $quarterlyDetails[] = [
            'label' => "Q{$q}",
            'incomingCount' => $inCount,
            'outgoingCount' => $outCount,
            'total' => $inCount + $outCount
        ];
    }
    
    // Get incoming categories - Modified to use 'quarter' instead of 'quarters'
    $incomingCategories = [];
    if ($docType != 'outgoing') {
        $incomingByCategory = Incoming::select('quarter') // Changed from 'quarters' to 'quarter'
            ->selectRaw('COUNT(*) as count')
            ->whereYear('date_received', $year)
            ->groupBy('quarter') // Changed from 'quarters' to 'quarter'
            ->get()
            ->mapWithKeys(function ($item) {
                $quarter = $item->quarter ? "Q{$item->quarter}" : 'Uncategorized';
                return [$quarter => $item->count];
            })
            ->toArray();
        
        $incomingCategories = $incomingByCategory;
    }
    
    // Get outgoing categories for distribution chart
    $outgoingCategories = [];
    if ($docType != 'incoming') {
        $outgoingByCategory = Outgoing::select('category')
            ->selectRaw('COUNT(*) as count')
            ->whereYear('date_released', $year)
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category ?: 'Uncategorized' => $item->count];
            })
            ->toArray();
        
        $outgoingCategories = $outgoingByCategory;
    }

    return [
        'year' => $year,
        'quarterFilter' => $specificQuarter,
        'docType' => $docType,
        'incomingCounts' => $incomingCounts,
        'outgoingCounts' => $outgoingCounts,
        'quarterlyDetails' => $quarterlyDetails,
        'incomingCategories' => $incomingCategories,
        'outgoingCategories' => $outgoingCategories,
        'byQuarter' => true
    ];
}

   /**
    * Get quarterly data for reports via API
    */
   /**
 * Export quarterly report
 */
public function quarterlyReport(Request $request)
{
    $year = $request->input('year', date('Y'));
    $specificQuarter = $request->input('quarter');
    $docType = $request->input('doc_type', 'all');

    $data = $this->getQuarterlyReportData($year, $specificQuarter, $docType);
    
    return response()->json($data);
}
public function quarterlyExport(Request $request)
{
    $year = $request->input('year', date('Y'));
    $type = $request->input('type', 'excel');
    $quarter = $request->input('quarter');
    $docType = $request->input('doc_type', 'all');

    // Generate the report data
    $data = $this->getQuarterlyReportData($year, $quarter, $docType);

    if ($type === 'excel') {
        return Excel::download(new QuarterlyReportExport($year, $quarter, $docType), "quarterly_report_{$year}.xlsx");
    } elseif ($type === 'pdf') {
        // Check if the view exists
        if (!View::exists('reports.quarterly_pdf')) {
            // Create reports directory if it doesn't exist
            if (!File::exists(resource_path('views/reports'))) {
                File::makeDirectory(resource_path('views/reports'), 0755, true);
            }
            
            // Create a basic PDF template if it doesn't exist
            $template = '<!DOCTYPE html>
<html>
<head>
    <title>Quarterly Document Report {{ $data->year }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0078d4; font-size: 24px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #0078d4; color: white; padding: 8px; text-align: left; border: 1px solid #ddd; }
        table td { padding: 8px; border: 1px solid #ddd; }
        table tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHED Document Tracking System</h1>
        <h2>Quarterly Document Report</h2>
        <p><strong>Period:</strong> 
            @if(isset($data->quarterFilter))
                Q{{ $data->quarterFilter }} {{ $data->year }}
            @else
                Full Year {{ $data->year }}
            @endif
        </p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Quarter</th>
                <th>Incoming Documents</th>
                <th>Outgoing Documents</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->quarterlyDetails as $quarter)
            <tr>
                <td>{{ $quarter["label"] }}</td>
                <td>{{ $quarter["incomingCount"] }}</td>
                <td>{{ $quarter["outgoingCount"] }}</td>
                <td>{{ $quarter["incomingCount"] + $quarter["outgoingCount"] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on: {{ date("F d, Y") }}</p>
    </div>
</body>
</html>';
            
            // Save the template
            File::put(resource_path('views/reports/quarterly_pdf.blade.php'), $template);
        }
        
        try {
            // Convert array to object for easier blade access
            $dataObject = json_decode(json_encode($data));
            
            $pdf = PDF::loadView('reports.quarterly_pdf', ['data' => $dataObject]);
            return $pdf->download("quarterly_report_{$year}.pdf");
        } catch (\Exception $e) {
            // Log the error
            Log::error('PDF generation error: ' . $e->getMessage());
            
            // Return a fallback response with error message
            return response()->json([
                'error' => 'Could not generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
   /**
    * Generate document-specific reports
    */
   public function generateReport(Request $request)
   {
       $request->validate([
           'document_type' => 'required|string',
           'export_type'   => 'nullable|string|in:pdf,excel'
       ]);
   
       $documentType = $request->document_type;
       $exportType = $request->export_type;
   
       // Fetch data
       $outgoings = Outgoing::where('category', $documentType)->get();
   
       // Export logic
       if ($exportType === 'excel') {
           return Excel::download(new OutgoingsExport($documentType), "outgoings_report_{$documentType}.xlsx");
       } elseif ($exportType === 'pdf') {
           $pdf = Pdf::loadView('reports.outgoings_pdf', compact('outgoings', 'documentType'))
                     ->setPaper('letter', 'portrait');
           return $pdf->download("outgoings_report_{$documentType}.pdf");
       }
   
       // Return the HTML view if not exporting
       return view('reports.outgoings', compact('outgoings', 'documentType'));
   }
   
   /**
    * Generate data for document type report.
    */
   public function documentTypeReport(Request $request)
   {
       $year = $request->input('year', date('Y'));
       $specificQuarter = $request->input('quarter');
       $docType = $request->input('docType', 'all');

       // Base query
       $query = Outgoing::whereYear('date_released', $year);

       // If a specific quarter is specified
       if ($specificQuarter) {
           $query->whereRaw('QUARTER(date_released) = ?', [$specificQuarter]);
       }

       // If a specific document type is specified
       if ($docType !== 'all') {
           $query->where('category', $docType);
       }

       // Get categories and counts
       $categories = $query->select('category')
           ->selectRaw('COUNT(*) as count')
           ->groupBy('category')
           ->get();

       // Calculate total for percentages
       $total = $categories->sum('count');

       // Format data for the chart
       $categoryData = $categories->map(function ($item) use ($total) {
           return [
               'name' => $item->category ?: 'Uncategorized',
               'count' => $item->count,
               'percentage' => $total > 0 ? round(($item->count / $total) * 100, 1) : 0
           ];
       })->toArray();

       return response()->json([
           'year' => $year,
           'categories' => $categoryData
       ]);
   }

   /**
    * Generate data for status report.
    */
   public function statusReport(Request $request)
   {
       $year = $request->input('year', date('Y'));
       $specificQuarter = $request->input('quarter');
       $docType = $request->input('docType', 'all');

       // Base query
       $query = Outgoing::whereYear('date_released', $year);

       // If a specific quarter is specified
       if ($specificQuarter) {
           $query->whereRaw('QUARTER(date_released) = ?', [$specificQuarter]);
       }

       // If a specific document type is specified
       if ($docType !== 'all') {
           $query->where('category', $docType);
       }

       // Get statuses and counts
       $statuses = $query->select('status')
           ->selectRaw('COUNT(*) as count')
           ->groupBy('status')
           ->get();

       // Calculate total for percentages
       $total = $statuses->sum('count');

       // Format data for the chart
       $statusData = $statuses->map(function ($item) use ($total) {
           return [
               'name' => $item->status ?: 'Undefined',
               'count' => $item->count,
               'percentage' => $total > 0 ? round(($item->count / $total) * 100, 1) : 0
           ];
       })->toArray();

       return response()->json([
           'year' => $year,
           'statuses' => $statusData
       ]);
   }

   /**
    * Export document type report to Excel or PDF.
    */
   public function documentTypeExport(Request $request)
   {
       $year = $request->input('year', date('Y'));
       $type = $request->input('type', 'excel');
       $quarter = $request->input('quarter');

       // Get data (reuse document type report logic)
       $data = $this->documentTypeReport($request)->getData();
       
       if ($type === 'excel') {
           // Excel export
           return Excel::download(new DocumentTypeExport($data), "document_type_report_{$year}.xlsx");
       } else {
           // PDF export
           $pdf = PDF::loadView('reports.document_type_pdf', ['data' => $data]);
           return $pdf->download("document_type_report_{$year}.pdf");
       }
   }

   /**
    * Export status report to Excel or PDF.
    */
   public function statusExport(Request $request)
   {
       $year = $request->input('year', date('Y'));
       $type = $request->input('type', 'excel');
       $quarter = $request->input('quarter');

       // Get data (reuse status report logic)
       $data = $this->statusReport($request)->getData();
       
       if ($type === 'excel') {
           // Excel export
           return Excel::download(new StatusExport($data), "status_report_{$year}.xlsx");
       } else {
           // PDF export
           $pdf = PDF::loadView('reports.status_pdf', ['data' => $data]);
           return $pdf->download("status_report_{$year}.pdf");
       }
   }
   
   /**
    * Export reports in various formats
    */
// Add this function to your OutgoingController to debug the export issue
public function export(Request $request)
{
    Log::info('Export report request received', $request->all());

    // Log the request details for debugging
    Log::info('Export request received', [
        'format' => $request->input('format', 'pdf'),
        'quarter' => $request->input('quarter'),
        'doc_type' => $request->input('doc_type', 'all'),
        'year' => $request->input('year', date('Y'))
    ]);
    
    // Get parameters
    $format = $request->input('format', 'pdf');
    $quarter = $request->input('quarter');
    $docType = $request->input('doc_type', 'all');
    $year = $request->input('year', date('Y'));
    
    // Get report data
    $data = $this->getQuarterlyReportData($year, $quarter, $docType);
    
    // Create a filename
    $filename = "document_report_{$year}" . ($quarter ? "_Q{$quarter}" : "") . ($format === 'excel' ? ".xlsx" : ".pdf");
    
    Log::info('Generating export file', ['filename' => $filename, 'format' => $format]);
    
    try {
        if ($format === 'excel') {
            // Make sure the view/template exists before trying to download
            return Excel::download(
                new QuarterlyReportExport($data), 
                $filename
            );
        } else {
            // Create a simple PDF view if it doesn't exist
            if (!View::exists('reports.quarterly_pdf')) {
                $this->createDefaultPdfTemplate();
            }
            
            // Convert array to object for easier blade access
            $dataObject = json_decode(json_encode($data));
            
            // Generate PDF
            $pdf = PDF::loadView('reports.quarterly_pdf', ['data' => $dataObject]);
            return $pdf->download($filename);
        }
    } catch (\Exception $e) {
        // Log the error
        Log::error('Export error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return a fallback response with error message
        return response()->json([
            'error' => 'Could not generate export: ' . $e->getMessage()
        ], 500);
    }
}

// Add this helper function to create a default PDF template
private function createDefaultPdfTemplate()
{
    // Create reports directory if it doesn't exist
    if (!File::exists(resource_path('views/reports'))) {
        File::makeDirectory(resource_path('views/reports'), 0755, true);
    }
    
    // Create a basic PDF template if it doesn't exist
    $template = <<<'EOT'
<!DOCTYPE html>
<html>
<head>
    <title>Quarterly Document Report {{ $data->year }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #0078d4; font-size: 24px; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { background-color: #0078d4; color: white; padding: 8px; text-align: left; border: 1px solid #ddd; }
        table td { padding: 8px; border: 1px solid #ddd; }
        table tr:nth-child(even) { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHED Document Tracking System</h1>
        <h2>Quarterly Document Report</h2>
        <p><strong>Period:</strong> 
            @if(isset($data->quarterFilter))
                Q{{ $data->quarterFilter }} {{ $data->year }}
            @else
                Full Year {{ $data->year }}
            @endif
        </p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Quarter</th>
                <th>Incoming Documents</th>
                <th>Outgoing Documents</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data->quarterlyDetails))
                @foreach($data->quarterlyDetails as $quarter)
                <tr>
                    <td>{{ $quarter->label }}</td>
                    <td>{{ $quarter->incomingCount }}</td>
                    <td>{{ $quarter->outgoingCount }}</td>
                    <td>{{ $quarter->incomingCount + $quarter->outgoingCount }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on: {{ date("F d, Y") }}</p>
    </div>
</body>
</html>
EOT;
    
    // Save the template
    File::put(resource_path('views/reports/quarterly_pdf.blade.php'), $template);
}
}
