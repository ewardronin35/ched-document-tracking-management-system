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
        // Determine prefix for view (existing logic)
        $prefix = $this->getCurrentPrefix();

        // Fetch dropdown options from related models
        $heiOptions = Hei::pluck('HEIs')->toArray();
        $programOptions = Programs::pluck('name')->toArray();

        if ($prefix === 'admin') {
            return view('admin.cav.index', compact('heiOptions', 'programOptions'));
        } elseif ($prefix === 'records') {
            return view('records.cav.index', compact('heiOptions', 'programOptions'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Handle AJAX request for DataTables.
     */
    public function getCavs(Request $request)
    {
        $cavs = Cav::select('id', 'cav_no', 'full_name_of_hei', 'program_name', 'status_of_the_program', 'date_applied')->get();

        return DataTables::of($cavs)
            ->addColumn('status_badge', function($cav) {
                // Customize based on 'status_of_the_program'
                switch(strtolower($cav->status_of_the_program)) {
                    case 'completed':
                        return '<span class="badge badge-status-completed">'.ucfirst($cav->status_of_the_program).'</span>';
                    case 'pending':
                        return '<span class="badge badge-status-pending">'.ucfirst($cav->status_of_the_program).'</span>';
                    case 'cancelled':
                        return '<span class="badge badge-status-cancelled">'.ucfirst($cav->status_of_the_program).'</span>';
                    default:
                        return '<span class="badge bg-secondary">'.ucfirst($cav->status_of_the_program).'</span>';
                }
            })
            ->addColumn('actions', function($cav) {
                logger($cav); // Check if $cav->id exists

                $buttons = '';

                // View Button
                if (Auth::user()->can('cav.view')) {
                    $buttons .= '<button class="btn btn-sm btn-info me-1 view-cav-btn" data-cav-id="'.$cav->id.'" title="View CAV" data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="fas fa-eye"></i>
                                  </button>';
                }

                // Edit Button
                if (Auth::user()->can('cav.edit')) {
                    $buttons .= '<a href="'.route('admin.cav.edit', $cav->id).'" class="btn btn-sm btn-warning me-1" title="Edit CAV" data-bs-toggle="tooltip" data-bs-placement="top">
                                    <i class="fas fa-edit"></i>
                                  </a>';
                }

                // Delete Button
                if (Auth::user()->can('cav.delete')) {
                    $buttons .= '<form action="'.route('admin.cav.destroy', $cav->id).'" method="POST" style="display:inline-block;">
                                    '.csrf_field().'
                                    '.method_field('DELETE').'
                                    <button type="submit" class="btn btn-sm btn-danger me-1" title="Delete CAV" data-bs-toggle="tooltip" data-bs-placement="top" onclick="return confirm(\'Are you sure you want to delete this CAV?\');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                  </form>';
                }
                

                return $buttons;
            })
            ->rawColumns(['status_badge', 'actions']) // Allow HTML in these columns
            ->make(true);
    }

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
            'cav_no' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'institution_code' => 'required|string|max:255',
            'full_name_of_hei' => 'required|string|max:255',
            'address_of_hei' => 'nullable|string|max:255',
            'official_receipt_number' => 'nullable|string|max:255',
            'type_of_heis' => 'nullable|string|max:255',
            'discipline_code' => 'nullable|string|max:255',
            'program_name' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'program_level' => 'nullable|string|max:255',
            'status_of_the_program' => 'nullable|string|max:255',
            'date_started' => 'nullable|date',
            'date_ended' => 'nullable|date',
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
            'cav_no' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'institution_code' => 'required|string|max:255',
            'full_name_of_hei' => 'required|string|max:255',
            'address_of_hei' => 'nullable|string|max:255',
            'official_receipt_number' => 'nullable|string|max:255',
            'type_of_heis' => 'nullable|string|max:255',
            'discipline_code' => 'nullable|string|max:255',
            'program_name' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'program_level' => 'nullable|string|max:255',
            'status_of_the_program' => 'nullable|string|max:255',
            'date_started' => 'nullable|date',
            'date_ended' => 'nullable|date',
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
    public function importCsv(Request $request) {
        // Validate file presence
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240' // limit size as needed
        ]);
    
        $path = $request->file('file')->getRealPath();
    
        // Use a library for chunking if needed or manually process
        if (($handle = fopen($path, 'r')) !== false) {
            // Skip header row if present
            fgetcsv($handle);
    
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Process each row, optionally batching data for faster insertion
                Cav::create([
                  'cav_no' => $row[0] ?? null,
                  'region' => $row[1] ?? null,
                  // ... map remaining columns appropriately, using null if missing
                ]);
            }
    
            fclose($handle);
        }
    
        return response()->json(['success' => true]);
    }
    
}
