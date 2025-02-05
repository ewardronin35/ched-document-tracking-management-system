<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProgramsImport;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all programs for dropdown options
        $programOptions = Programs::all(); 
        return view('admin.programs.index', compact('programOptions'));
    }

    /**
     * Provide data for Handsontable via AJAX.
     */
    public function indexData()
    {
        // Fetch all programs without pagination for Handsontable
        $programs = Programs::all();
        return response()->json($programs);
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:programs,name',
            'psced_code' => 'nullable|string|max:50',
        ]);

        Programs::create($request->only('name', 'psced_code'));

        return response()->json(['success' => 'Program created successfully.', 'data' => Programs::all()]);
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, Programs $program)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:programs,name,' . $program->id,
            'psced_code' => 'nullable|string|max:50',
        ]);

        $program->update($request->only('name', 'psced_code'));

        return response()->json(['success' => 'Program updated successfully.']);
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(Programs $program)
    {
        $program->delete();
        return response()->json(['success' => 'Program deleted successfully.']);
    }

    /**
     * Handle inline updates via AJAX.
     */
    public function updateInline(Request $request, Programs $program)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:programs,name,' . $program->id,
            'psced_code' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $program->update($request->only('name', 'psced_code'));

        return response()->json(['success' => 'Program updated successfully.']);
    }

    /**
     * Import programs from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        Excel::import(new ProgramsImport, $request->file('csv_file'));

        return response()->json(['success' => 'Programs imported successfully.', 'data' => Programs::all()]);
    }
}
