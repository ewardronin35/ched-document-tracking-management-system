<?php

namespace App\Http\Controllers;

use App\Models\SoMasterList;
use App\Models\Programs;
use App\Models\Majors;
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
        $programs = Programs::all(); // Fetch all programs
        $majors = Majors::all();     // Fetch all majors
        $prefix = $this->getCurrentPrefix();
    
        if ($prefix === 'admin') {
            return view('admin.SoMasterList.index', compact('soMasterLists', 'programs', 'majors'));
        } elseif ($prefix === 'records') {
            return view('records.SoMasterList.index', compact('soMasterLists', 'programs', 'majors'));
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

        // Update student
        $soMasterList->update($request->all());

        // Redirect based on prefix
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
        'column' => 'required|string',
        'value' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    $soMasterList->update([$request->column => $request->value]);

    return response()->json(['success' => 'Data updated successfully.']);
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
public function uploadGovtPermit(Request $request, SoMasterList $soMasterList)
{
    $this->authorize('update', $soMasterList);

    // Validate the uploaded file
    $validatedData = $request->validate([
        'govt_permit_file' => 'required|mimes:pdf,docx|max:2048', // Adjust max size as needed
    ]);

    if ($request->hasFile('govt_permit_file')) {
        // Delete old file if exists
        if ($soMasterList->govt_permit_reco) {
            Storage::disk('public')->delete($soMasterList->govt_permit_reco);
        }

        // Store the new file
        $filePath = $request->file('govt_permit_file')->store('govt_permit_reco', 'public');

        // Update the model
        $soMasterList->govt_permit_reco = $filePath;
        $soMasterList->save();
    }

    return response()->json(['success' => 'Government Permit Recommendation uploaded successfully.']);
}
public function getData(Request $request)
{
    // Build the base query (with relationships if you want to show them)
    $query = SoMasterList::with(['program', 'major']); 
    // Or ->select(...) if you only want specific columns

    // Let Yajra handle the request and produce a DataTable JSON
    return DataTables::of($query)
        // Optionally modify or add columns
        ->addColumn('program_name', function ($row) {
            return $row->program ? $row->program->name : '';
        })
        ->addColumn('major_name', function ($row) {
            return $row->major ? $row->major->name : '';
        })
        ->addColumn('actions', function ($row) {
            // Example: return HTML for edit/delete buttons
            return sprintf('
                <a href="%s" class="btn btn-sm btn-warning">Edit</a>
                <button class="btn btn-sm btn-danger delete-btn" data-id="%d">Delete</button>',
                route('admin.so_master_lists.edit', $row->id),
                $row->id
            );
        })
        ->rawColumns(['actions']) // Mark these columns as "raw" HTML
        ->make(true);
}
}
