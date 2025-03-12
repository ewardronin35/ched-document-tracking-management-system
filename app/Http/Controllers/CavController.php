<?php

namespace App\Http\Controllers;

use App\Models\Cav;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\Hei;      // Assuming there's an Hei model for HEI records
use App\Models\Programs;  // Assuming there's a Program model for Program records
use App\Models\Majors;    // Assuming there's a Major model for Major records
use App\Imports\MultiSheetCavImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
class CavController extends Controller
{
    /**
     * Constructor to apply middleware for authorization.
     */
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch dropdown options from related models
        $heiOptions = Hei::pluck('HEIs')->toArray();
        $programOptions = Programs::pluck('name')->toArray();
        $majorOptions = Majors::pluck('name')->toArray();


        // Determine prefix for view (for admin vs. records, etc.)
        $prefix = request()->routeIs('admin.*') ? 'admin' : 'records';

        if ($prefix === 'admin') {
            return view('admin.cav.index', compact('heiOptions', 'programOptions', 'majorOptions'));
        } elseif ($prefix === 'records') {
            return view('records.cav.index', compact('heiOptions', 'programOptions', 'majorOptions'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }
    public function data()
    {
        // For example, if you have a Cav model that holds your records:
        $data = \App\Models\CavsOsd::all(); // or use appropriate filtering/ordering

        return response()->json($data);
    }
    /**
     * Handle AJAX request for DataTables.
     */
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.cav.create');
        } elseif ($prefix === 'records') {
            return view('records.cav.create');
        } else {
            abort(403, 'Unauthorized access.');
        }
    }
    public function getAllCavs()
    {
        // Return all Cav records as JSON, including all fields.
        return response()->json(Cav::all());
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        logger('Store payload:', $request->all());

        // Validation
        $validator = Validator::make($request->all(), [
            'cav_no' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'sex' => 'nullable|in:Male,Female',
            'institution_code' => 'nullable|string|max:255',
            'full_name_of_hei' => 'nullable|string|max:255',
            'address_of_hei' => 'nullable|string|max:255',
            'official_receipt_number' => 'nullable|string|max:255',
            'type_of_heis' => 'nullable|string|max:255',
            'discipline_code' => 'nullable|string|max:255',
            'program_name' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'program_level' => 'nullable|string|max:255',
            'status_of_the_program' => 'nullable|string|max:255',
            'date_started' => 'nullable|string|max:255',
            'date_ended' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
            'units_earned' => 'nullable|integer',            
            'special_order_no' => 'nullable|string|max:255',
            'series' => 'nullable|string|max:255',
            'date_applied' => 'nullable|date',
            'date_released' => 'nullable|date',
            'airway_bill_no' => 'nullable|string|max:255',
            'serial_number_of_security_paper' => 'nullable|string|max:255',
            'purpose_of_cav' => 'nullable|string|max:255',
            'target_country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            logger('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }        
        try {
            // Attempt to create the record
            $cav = Cav::create($request->all());
    
            // Log successful creation
            logger('CAV created with ID: ' . $cav->id);
    
            return response()->json([
                'id' => $cav->id,
                'message' => 'CAV created successfully.'
            ]);
        } catch (\Exception $e) {
            // Log any exceptions during creation
            logger('CAV creation error: ' . $e->getMessage());
            return response()->json([
                'errors' => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cav $cav)
    {
        // Check if the request is AJAX
        if (request()->ajax()) {
            return response()->json(['cav' => $cav]);
        }
    
        // For non-AJAX requests, load the appropriate view
        $prefix = $this->getCurrentPrefix();
    
        if ($prefix === 'admin') {
            return view('admin.cav.show', compact('cav'));
        } elseif ($prefix === 'records') {
            return view('records.cav.show', compact('cav'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cav $cav)
    {
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.cav.edit', compact('cav'));
        } elseif ($prefix === 'records') {
            return view('records.cav.edit', compact('cav'));
        } else {
            abort(403, 'Unauthorized access.');
        }   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cav $cav)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'cav_no' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'sex' => 'nullable|in:Male,Female',
            'institution_code' => 'nullable|string|max:255',
            'full_name_of_hei' => 'nullable|string|max:255',
            'address_of_hei' => 'nullable|string|max:255',
            'official_receipt_number' => 'nullable|string|max:255',
            'type_of_heis' => 'nullable|string|max:255',
            'discipline_code' => 'nullable|string|max:255',
            'program_name' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'program_level' => 'nullable|string|max:255',
            'status_of_the_program' => 'nullable|string|max:255',
            'date_started' => 'nullable|string|max:255',
            'date_ended' => 'nullable|string|max:255',
            'graduation_date' => 'nullable|date',
            'units_earned' => 'nullable|integer',            
            'special_order_no' => 'nullable|string|max:255',        
            'series' => 'nullable|string|max:255',
            'date_applied' => 'nullable|date',
            'date_released' => 'nullable|date',
            'airway_bill_no' => 'nullable|string|max:255',
            'serial_number_of_security_paper' => 'nullable|string|max:255',
            'purpose_of_cav' => 'nullable|string|max:255',
            'target_country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }      

        $cav->update($request->all());

        return response()->json([
            'id' => $cav->id,
            'message' => 'CAV updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cav $cav)
    {
        // Authorization is handled by middleware

        // Delete the record
        $cav->delete();
    
        // Determine the role-based prefix
        $prefix = $this->getCurrentPrefix();
    
        // Redirect to the appropriate route
        return redirect()->route("{$prefix}.cav.index")
                         ->with('success', 'CAV deleted successfully.');
    }

    /**
     * Helper method to determine current prefix.
     */
    private function getCurrentPrefix()
    {
        $prefix = trim(request()->route()->getPrefix(), '/');
        if ($prefix === 'admin') {
            return 'admin';
        } elseif ($prefix === 'records') {
            return 'records';
        } else {
            return null;
        }
    }
    public function getLocalCavRecords() {
        // Adjust the condition according to how you determine "local" records.
        // Example: Filter where target_country is 'Philippines'.

        $localRecords = Cav::where('target_country', 'Philippines')->get();
        return response()->json($localRecords);
    }
    
    public function getAbroadCavRecords() {
        // Adjust the condition according to how you determine "abroad" records.
        // Example: Filter where target_country is not 'Philippines'.
        $abroadRecords = Cav::where('target_country', '!=', 'Philippines')->get();
        return response()->json($abroadRecords);
    }
   
    public function importExcel(Request $request)
    {
        Log::info('Importing CAV records...');
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);
        Log::info('Validation passed.');

        if (!$request->hasFile('file')) {
            Log::error('No file found in request.');
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        Log::info('Uploaded file info:', ['filename' => $request->file('file')->getClientOriginalName()]);

    
        try {
            Excel::import(new MultiSheetCavImport, $request->file('file'));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Excel import error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
}
