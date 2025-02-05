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
use Illuminate\Support\Facades\Storage;
use App\Imports\SoMasterListImport;
use Yajra\DataTables\Facades\DataTables;

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
    $soMasterLists = SoMasterList::with(['program', 'major'])->get();
    $heis = HEI::all();
    $heiOptions = HEI::pluck('uii', 'HEIs')->toArray(); // Key: HEI Name, Value: UII

    // Transform the date fields to 'YYYY-MM-DD' for each record


    $programs = Programs::all(); 
    $majors = Majors::all();     

    // Create options arrays for dropdowns
    $programOptions = $programs->pluck('name', 'id')->toArray();
    $majorOptions = $majors->pluck('name', 'id')->toArray();
    $programOptions = $programs->mapWithKeys(function($p) {
        return [$p->id => [
            'name' => $p->name,
            'psced_code' => $p->psced_code
        ]];
    })->toArray();
    $prefix = $this->getCurrentPrefix();
    if ($prefix === 'admin') {
        return view('admin.SoMasterList.index', compact('soMasterLists', 'programs', 'majors', 'programOptions', 'majorOptions', 'heiOptions'));
    } elseif ($prefix === 'records') {
        return view('records.SoMasterList.index', compact('soMasterLists', 'programs', 'majors', 'programOptions', 'majorOptions'));
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
        $this->authorize('create', SoMasterList::class);

        // Validate input
        $request->validate([
            'hei_name' => 'required|string|max:255',
            'hei_uii' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female,Other',
            'program_id' => 'required|exists:programs,id',
            'major_id' => 'required|exists:majors,id',
            'started' => 'required|date',
            'academic_year' => 'required|string|max:50',
            'ended' => 'nullable|date',
            'date_of_application' => 'required|date',
            'date_of_issuance' => 'nullable|date',
            'registrar' => 'nullable|string|max:255',
            'govt_permit_reco' => 'nullable|string|max:255',
            'total' => 'required|integer',
            'semester' => 'required|integer',
            'date_of_graduation' => 'nullable|date',
            'semester1_start' => 'nullable|date',
            'semester1_end' => 'nullable|date',
            'semester2_start' => 'nullable|date',
            'semester2_end' => 'nullable|date',
        ]);

        // Create student
        SoMasterList::create($request->all());

        // Redirect based on prefix
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
    
        // Validate input with 'sometimes' rules for partial updates
        $validatedData = $request->validate([
            'hei_name'             => 'sometimes|required|string|max:255',
            'hei_uii'              => 'sometimes|required|string|max:255',
            'last_name'            => 'sometimes|required|string|max:255',
            'first_name'           => 'sometimes|required|string|max:255',
            'middle_name'          => 'nullable|string|max:255',
            'extension_name'       => 'nullable|string|max:255',
            'sex'                  => 'sometimes|required|in:Male,Female,Other',
            'program_id'           => 'sometimes|required|exists:programs,id',
            'major_id'             => 'sometimes|required|exists:majors,id',
            'started'              => 'sometimes|required|date',
            'academic_year'        => 'sometimes|required|string|max:50',
            'ended'                => 'nullable|date',
            'date_of_application'  => 'sometimes|required|date',
            'date_of_issuance'     => 'nullable|date',
            'registrar'            => 'nullable|string|max:255',
            'govt_permit_reco'     => 'nullable|string|max:255',
            'total'                => 'sometimes|required|integer',
            'semester'             => 'sometimes|required|integer',
            'date_of_graduation'   => 'nullable|date',
            'semester1_start'      => 'nullable|date',
            'semester1_end'        => 'nullable|date',
            'semester2_start'      => 'nullable|date',
            'semester2_end'        => 'nullable|date',
        ]);
    
        // Update the record with validated data
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
        $validator = Validator::make($request->all(), [
            'hei_name'             => 'sometimes|required|string|max:255',
            'hei_uii'              => 'sometimes|required|string|max:255',
            'last_name'            => 'sometimes|required|string|max:255',
            'first_name'           => 'sometimes|required|string|max:255',
            'middle_name'          => 'nullable|string|max:255',
            'extension_name'       => 'nullable|string|max:255',
            'sex'                  => 'sometimes|required|in:Male,Female,Other',
            'program_id'           => 'sometimes|required|exists:programs,id',
            'major_id'             => 'sometimes|required|exists:majors,id',
            'started'              => 'sometimes|required|date',
            'academic_year'        => 'sometimes|required|string|max:50',
            'ended'                => 'nullable|date',
            'date_of_application'  => 'sometimes|required|date',
            'date_of_issuance'     => 'nullable|date',
            'registrar'            => 'nullable|string|max:255',
            'govt_permit_reco'     => 'nullable|string|max:255',
            'total'                => 'sometimes|required|integer',
            'semester'             => 'sometimes|required|integer',
            'date_of_graduation'   => 'nullable|date',
            'semester1_start'      => 'nullable|date',
            'semester1_end'        => 'nullable|date',
            'semester2_start'      => 'nullable|date',
            'semester2_end'        => 'nullable|date',
            'psced_code'           => 'sometimes|nullable|string|max:50',
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
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    Excel::import(new SoMasterListImport, $request->file('csv_file'));

    return redirect()->route('admin.so_master_lists.index')->with('success', 'CSV imported successfully!');
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
        'csv_file' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    Excel::import(new MajorsImport, $request->file('csv_file'));

    return redirect()->back()->with('success', 'Majors imported successfully!');
}
public function getData(Request $request)
{
    $query = SoMasterList::with(['program', 'major']);

    return DataTables::of($query)
        // Convert relevant date columns to 'YYYY-MM-DD'
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

        // Replace program_id and major_id with their names for display
        
        
        ->editColumn('program_id', function ($row) {
            return $row->program ? $row->program->name : '';
        })
        ->editColumn('major_id', function ($row) {
            return $row->major ? $row->major->name : '';
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
