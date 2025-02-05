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
        $programs = Programs::paginate(10);
        return view('admin.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create()
    {
        return view('admin.programs.create');
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

        return redirect()->route('admin.programs.index')->with('success', 'Program created successfully.');
    }

    /**
     * Display the specified program.
     */
    public function show(Programs $program)
    {
        return view('admin.programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(Programs $program)
    {
        return view('admin.programs.edit', compact('program'));
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

        return redirect()->route('admin.programs.index')->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(Programs $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Program deleted successfully.');
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

        return redirect()->route('admin.programs.index')->with('success', 'Programs imported successfully!');
    }
}