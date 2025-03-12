<?php

namespace App\Http\Controllers;

use App\Models\SoMasterList;
use App\Models\Programs;
use App\Models\Majors;
use App\Models\HEI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel; // Ensure this package is installed
use App\Imports\ProgramsImport;
use App\Imports\MajorsImport;
use App\Imports\MultiSheetSoImport;
use Illuminate\Support\Facades\Storage;
use App\Imports\SoMasterListImport;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\AcademicYear;


class SoMasterListController extends Controller
{
    /**
     * Helper method to determine the current role prefix.
     */
    protected function getCurrentPrefix()
    {
        $routeName = Route::currentRouteName(); // e.g., 'admin.so_master_lists.index'
        $parts = explode('.', $routeName);
        return $parts[0] ?? null; // 'admin' or 'records'
    }

    /**
     * Display a listing of the students.
     */

    public function index()
    {
        // Retrieve academic years from the database (ordered as needed)
        $academicYears = AcademicYear::orderBy('year', 'desc')->pluck('year')->toArray();
    
        // Existing code for soMasterLists, programs, majors, etc.
        $soMasterLists = SoMasterList::all();
        $heis = HEI::all();
        $heiOptions = HEI::pluck('uii', 'HEIs')->toArray();
        $programs = Programs::all();
        $majors = Majors::all();
        $programOptions = $programs->mapWithKeys(function($p) {
            return [$p->id => [
                'name' => $p->name,
                'psced_code' => $p->psced_code
            ]];
        })->toArray();
        $majorOptions = $majors->pluck('name', 'id')->toArray();
        $prefix = $this->getCurrentPrefix();
    
        if ($prefix === 'admin') {
            return view('admin.SoMasterList.index', compact('soMasterLists', 'programs', 'majors', 'programOptions', 'majorOptions', 'heiOptions', 'academicYears'));
        } elseif ($prefix === 'records') {
            return view('records.SoMasterList.index', compact('soMasterLists', 'programs', 'majors', 'programOptions', 'majorOptions', 'academicYears'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }
    


    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $programs = Programs::all();
        $majors = Majors::all();
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.SoMasterList.create', compact('programs', 'majors'));
        } elseif ($prefix === 'records') {
            return view('records.SoMasterList.create', compact('programs', 'majors'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        
        // (Optional) Convert empty strings to null if needed.
        // ...
    
        $this->authorize('create', SoMasterList::class);
    
        // Validate input.
        $validatedData = $request->validate([
            'status' => 'nullable|string|max:255',
            'processing_slip_number' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'hei_name' => 'nullable|string|max:255',
            'hei_uii' => 'nullable|string|max:255',
            'special_order_number' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'sex' => 'nullable|in:Male,Female,Other',
            'total' => 'nullable|integer',
            'program' => 'nullable|string|max:255',
            'psced_code' => 'nullable|string|max:50',
            'major' => 'nullable|string|max:255',
            'started' => 'nullable|date',
            'ended' => 'nullable|date',
            'date_of_application' => 'nullable|date',
            'date_of_issuance' => 'nullable|date',
            'registrar' => 'nullable|string|max:255',
            'govt_permit_recognition' => 'nullable|string|max:255',
            'signed_by' => 'nullable|string|max:255',
            'semester' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
            'date_of_graduation' => 'nullable|date',
            'semester2' => 'nullable|string|max:50',
            'academic_year2' => 'nullable|string|max:20',
        ]);
    
        // Create record.
        $record = SoMasterList::create($validatedData);
        Log::debug('Incoming request data:', $request->all());
    
        // If the request expects JSON, return JSON.
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['data' => $record, 'success' => true], 200);
        }
    
        // Otherwise, perform the normal redirect.
        $prefix = $this->getCurrentPrefix();
        return redirect()->route("{$prefix}.so_master_lists.index")
                         ->with('success', 'Student created successfully.');
    }
    
    
    /**
     * Display the specified student.
     */
    public function show(SoMasterList $soMasterList)
    {
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.SoMasterList.show', compact('soMasterList'));
        } elseif ($prefix === 'records') {
            return view('records.SoMasterList.show', compact('soMasterList'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(SoMasterList $soMasterList)
    {
        $programs = Programs::all();
        $majors = Majors::all();
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.SoMasterList.edit', compact('soMasterList', 'programs', 'majors'));
        } elseif ($prefix === 'records') {
            return view('records.SoMasterList.edit', compact('soMasterList', 'programs', 'majors'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, SoMasterList $soMasterList)
{
    $this->authorize('update', $soMasterList);

    // Pre-process input: convert empty strings to null
    $input = $request->all();
    if (isset($input['program_id']) && $input['program_id'] === '') {
        $input['program_id'] = null;
    }
    if (isset($input['major_id']) && $input['major_id'] === '') {
        $input['major_id'] = null;
    }
    $request->replace($input);

    // Validate input using similar rules as in store()
    $validatedData = $request->validate([
        'hei_name'             => 'sometimes|required|string|max:255',
        'hei_uii'              => 'sometimes|required|string|max:255',
        'last_name'            => 'sometimes|required|string|max:255',
        'first_name'           => 'sometimes|required|string|max:255',
        'middle_name'          => 'nullable|string|max:255',
        'extension_name'       => 'nullable|string|max:255',
        'sex'                  => 'sometimes|required|in:Male,Female,Other',
        'program'           => 'nullable|string|max:255',
        'major'             => 'nullable|string|max:255',
        'started'              => 'sometimes|required|date',
        'academic_year'        => 'sometimes|required|string|max:50',
        'ended'                => 'nullable|date',
        'date_of_application'  => 'sometimes|required|date',
        'date_of_issuance'     => 'nullable|date',
        'registrar'            => 'nullable|string|max:255',
        'govt_permit_reco'     => 'nullable|string|max:255',
        'total'                => 'sometimes|required|integer',
        'semester'             => 'sometimes|required|in:First,Second,Summer',
        'date_of_graduation'   => 'nullable|date',
        'semester1_start'      => 'nullable|date',
        'semester1_end'        => 'nullable|date',
        'semester2_start'      => 'nullable|date',
        'semester2_end'        => 'nullable|date',
    ]);

    // Update the record using validated data
    $soMasterList->update($validatedData);

    $prefix = $this->getCurrentPrefix();

    return redirect()->route("{$prefix}.so_master_lists.index")
                     ->with('success', 'Student updated successfully.');
}

    

    /**
     * Remove the specified student from storage.
     */
    public function destroy(SoMasterList $soMasterList)
    {
        $this->authorize('delete', $soMasterList);

        $soMasterList->delete();

        // Redirect based on prefix
        $prefix = $this->getCurrentPrefix();

        return redirect()->route("{$prefix}.so_master_lists.index")
                         ->with('success', 'Student deleted successfully.');
    }
    public function updateInline(Request $request, SoMasterList $soMasterList)
    {
        // Pre-process input: convert empty strings for program_id and major_id to null
        $input = $request->all();
        if (isset($input['program_id']) && $input['program_id'] === '') {
            $input['program_id'] = null;
        }
        if (isset($input['major_id']) && $input['major_id'] === '') {
            $input['major_id'] = null;
        }
        $request->replace($input);
    
        // Validate input. Note that we've added additional rules
        // for fields that were not previously included.
        $validator = Validator::make($request->all(), [
            'status'                    => 'nullable|string|max:255',
            'hei_name'                  => 'sometimes|required|string|max:255',
            'hei_uii'                   => 'sometimes|required|string|max:255',
            'last_name'                 => 'sometimes|required|string|max:255',
            'first_name'                => 'sometimes|required|string|max:255',
            'middle_name'               => 'nullable|string|max:255',
            'extension_name'            => 'nullable|string|max:255',
            'sex'                       => 'sometimes|required|in:Male,Female,Other',
            'program'                => 'nullable|string|max:255',
            'major'                  => 'nullable|string|max:255',
            'started'                   => 'sometimes|required|date',
            'academic_year'             => 'sometimes|required|string|max:50',
            'ended'                     => 'nullable|date',
            'date_of_application'       => 'sometimes|required|date',
            'date_of_issuance'          => 'nullable|date',
            'registrar'                 => 'sometimes|nullable|string|max:255',
            // Make sure the field name matches your database column:
            'govt_permit_recognition'   => 'sometimes|nullable|string|max:255',
            'total'                     => 'sometimes|required|integer',
            'semester'                  => 'sometimes|required|in:First,Second,Summer',
            'date_of_graduation'        => 'nullable|date',
            'semester1_start'           => 'nullable|date',
            'semester1_end'             => 'nullable|date',
            'semester2_start'           => 'nullable|date',
            'semester2_end'             => 'nullable|date',
            'psced_code'                => 'sometimes|nullable|string|max:50',
            // Additional fields you wish to update inline:
            'processing_slip_number'    => 'sometimes|nullable|string|max:255',
            'region'                    => 'sometimes|nullable|string|max:255',
            'special_order_number'      => 'sometimes|nullable|string|max:255',
            'signed_by'                 => 'sometimes|nullable|string|max:255',
            'academic_year2'            => 'sometimes|nullable|string|max:50',
            'semester2'                 => 'sometimes|required|in:First,Second,Summer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $soMasterList->update($validator->validated());
    
        return response()->json(['success' => 'Data updated successfully.'], 200);
    }
    

    

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:60000',
        ]);
    
        set_time_limit(3000);
        $file = $request->file('csv_file');
        $extension = strtolower($file->getClientOriginalExtension());
    
        Log::info("Starting import. File extension: {$extension}");
    
        try {
            if ($extension === 'csv' || $extension === 'txt') {
                Excel::import(new \App\Imports\SoMasterListImport, $file);
                Log::info("SoMasterListImport: CSV/TXT file imported successfully.");
            } else {
                Excel::import(new \App\Imports\MultiSheetSoImport, $file);
                Log::info("MultiSheetSoImport: Excel file (multi-sheet) imported successfully.");
            }
            Log::info("Import finished without exceptions.");
            return redirect()->route('admin.so_master_lists.index')
                             ->with('success', 'Import started! The data will be available soon.');
        } catch (\Exception $e) {
            Log::error("Import failed with exception: " . $e->getMessage());
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
public function importPrograms(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    Excel::import(new ProgramsImport, $request->file('csv_file'));

    return redirect()->back()->with('success', 'Programs imported successfully!');
}

public function importMajors(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
    ]);

    Excel::import(new MajorsImport, $request->file('csv_file'));

    return redirect()->back()->with('success', 'Majors imported successfully!');
}
public function getData(Request $request)
{
    // Remove eager loading of relationships that no longer exist.
    $query = SoMasterList::query();

    // If a program filter is provided, filter by that.
    if ($request->has('program') && $request->program != '') {
        // Since we now store program as a string, you might want to compare directly.
        // Or if you are still filtering by ID, adjust accordingly.
        $programId = $request->program;
        // For example, if your program filter now holds the program name, do:
        $query->where('program', $programId);
        // Otherwise, if you still use an ID, you need to map it to the name.
    }

    return DataTables::of($query)
        ->editColumn('started', function ($row) {
            return $row->started ? \Carbon\Carbon::parse($row->started)->format('Y-m-d') : null;
        })
        ->editColumn('ended', function ($row) {
            return $row->ended ? \Carbon\Carbon::parse($row->ended)->format('Y-m-d') : null;
        })
        ->editColumn('date_of_application', function ($row) {
            return $row->date_of_application ? \Carbon\Carbon::parse($row->date_of_application)->format('Y-m-d') : null;
        })
        ->editColumn('date_of_issuance', function ($row) {
            return $row->date_of_issuance ? \Carbon\Carbon::parse($row->date_of_issuance)->format('Y-m-d') : null;
        })
        ->editColumn('date_of_graduation', function ($row) {
            return $row->date_of_graduation ? \Carbon\Carbon::parse($row->date_of_graduation)->format('Y-m-d') : null;
        })
        // Instead of 'program_id', return the string field 'program'
        ->editColumn('program', function ($row) {
            return $row->program ?? '';
        })
        // Instead of 'major_id', return the string field 'major'
        ->editColumn('major', function ($row) {
            return $row->major ?? '';
        })
        ->addColumn('status', function ($row) {
            return $row->status;
        })
        ->addColumn('processing_slip_number', function ($row) {
            return $row->processing_slip_number;
        })
        ->addColumn('region', function ($row) {
            return $row->region;
        })
        ->addColumn('special_order_number', function ($row) {
            return $row->special_order_number;
        })
        ->addColumn('signed_by', function ($row) {
            return $row->signed_by;
        })
        ->addColumn('semester2', function ($row) {
            return $row->semester2;
        })
        ->addColumn('academic_year2', function ($row) {
            return $row->academic_year2;
        })
        ->addColumn('psced_code', function ($row) {
            return $row->psced_code ?? '';
        })
        ->addColumn('actions', function ($row) {
            return sprintf(
                '<a href="%s" class="btn btn-sm btn-warning">Edit</a>
                 <button class="btn btn-sm btn-danger delete-btn" data-id="%d">Delete</button>',
                route('admin.so_master_lists.edit', $row->id),
                $row->id
            );
        })
        ->rawColumns(['actions'])
        ->make(true);
}



}
