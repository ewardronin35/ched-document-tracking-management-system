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
                'no'                => str_pad($item->id, 4, '0', STR_PAD_LEFT),

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
                'no'            => str_pad($item->id, 4, '0', STR_PAD_LEFT), // Display version
                'quarter_label' => $item->quarter_label,
                'o'             => $item->o,
                'date_released' => $item->date_released
                                    ? Carbon::parse($item->date_released)->format('Y-m-d')
                                    : null,
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
                'no'            => str_pad($item->id, 4, '0', STR_PAD_LEFT), 
                'o'             => $item->o,
                'date_released' => $item->date_released
                                    ? Carbon::parse($item->date_released)->format('Y-m-d')
                                    : null,
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
            'quarter'           => 'required|integer|min:1|max:4',
            'category'          => 'nullable|string',
            'addressed_to'      => 'nullable|string',
            'email'             => 'nullable|email',
            'subject_of_letter' => 'nullable|string',
            'remarks'           => 'nullable|string',
            'libcap_no'         => 'nullable|string',
            'status'            => 'nullable|string',
            'chedrix_2025'      => 'required|string',
            'o'                 => 'required|string',
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
            'date_released'     => 'required|date',
            'category'          => 'required|string',
            'addressed_to'      => 'required|string',
            'email'             => 'required|email',
            'subject_of_letter' => 'nullable|string',
            'remarks'           => 'nullable|string',
            'libcap_no'         => 'nullable|string',
            'status'            => 'required|string',
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
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            // Adjust your import class so it no longer expects 'control_no'
            Excel::import(new OutgoingImport, $request->file('csv_file'));

            return redirect()->route('admin.outgoings.index')->with('success', 'CSV imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->withFailures($failures);
        }
    }
}
