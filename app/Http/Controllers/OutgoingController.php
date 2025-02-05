<?php

namespace App\Http\Controllers;

use App\Models\Outgoing;
use App\Models\Programs;
use App\Models\Majors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel; // Ensure this package is installed
use App\Imports\OutgoingImport; // Create this import class if needed
use Illuminate\Support\Facades\Storage;

class OutgoingController extends Controller
{
    /**
     * Helper method to determine the current prefix.
     */
    protected function getCurrentPrefix()
    {
        $routeName = Route::currentRouteName(); // e.g., 'admin.documents.outgoings.index'
        $parts = explode('.', $routeName);
        return $parts[0] ?? null; // 'admin' or 'records'
    }

    /**
     * Display a listing of the outgoings.
     */
    public function index()
    {
        $outgoings = Outgoing::all(); // Adjust pagination as needed
        $prefix = $this->getCurrentPrefix();

        if ($prefix === 'admin') {
            return view('admin.outgoings.index', compact('outgoings'));
        } elseif ($prefix === 'records') {
            return view('records.outgoings.index', compact('outgoings'));
        } else {
            abort(403, 'Unauthorized access.');
        }
    }

    /**
     * Show the form for creating a new outgoing.
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
     * Store a newly created outgoing in storage.
     */
    public function store(Request $request)
    {
        // Validation is already handled in your existing store method
        $request->validate([
            'control_no' => 'required|string|unique:outgoings,control_no|max:255',
            'date_released' => 'required|date',
            'category' => 'required|string|max:255',
            'addressed_to' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject_of_letter' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'libcap_no' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed,Rejected',
        ]);

        Outgoing::create($request->all());

        // Redirect based on prefix
        $prefix = $this->getCurrentPrefix();

        return redirect()->route("{$prefix}.outgoings.index")
                         ->with('success', 'Outgoing created successfully.');
    }

    /**
     * Display the specified outgoing.
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
     * Show the form for editing the specified outgoing.
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
     * Update the specified outgoing in storage.
     */
    public function update(Request $request, Outgoing $outgoing)
    {
        // Validation for update
        $request->validate([
            'control_no' => 'required|string|unique:outgoings,control_no,' . $outgoing->id . '|max:255',
            'date_released' => 'required|date',
            'category' => 'required|string|max:255',
            'addressed_to' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject_of_letter' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'libcap_no' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed,Rejected',
        ]);

        $outgoing->update($request->all());

        // Redirect based on prefix
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

        // Redirect based on prefix
        $prefix = $this->getCurrentPrefix();

        return redirect()->route("{$prefix}.outgoings.index")
                         ->with('success', 'Outgoing deleted successfully.');
    }

    /**
     * Import outgoing data from a CSV file.
     */

    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            Excel::import(new OutgoingImport, $request->file('csv_file'));
            return redirect()->route('admin.outgoings.index')->with('success', 'CSV imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return back()->withFailures($failures);
        }
    }
}
