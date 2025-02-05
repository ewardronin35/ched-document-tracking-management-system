<?php

namespace App\Http\Controllers;

use App\Models\Majors;
use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MajorsImport;

class MajorsController extends Controller
{
    /**
     * Display a listing of the majors.
     */
    public function index()
    {
        $programs = Programs::all(); // Fetch all programs
        $majors = Majors::with('program')->paginate(10);
        return view('admin.majors.index', compact('majors', 'programs'));
    }

    /**
     * Show the form for creating a new major.
     */
    public function create()
    {
        $programs = Programs::all();
        return view('admin.majors.create', compact('programs'));
    }

    /**
     * Store a newly created major in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:majors,name',
            'program_id' => 'required|exists:programs,id',
        ]);

        Majors::create($request->only('name', 'program_id'));

        return redirect()->route('admin.majors.index')->with('success', 'Major created successfully.');
    }

    /**
     * Display the specified major.
     */
    public function show(Majors $major)
    {
        return view('admin.majors.show', compact('major'));
    }

    /**
     * Show the form for editing the specified major.
     */
    public function edit(Majors $major)
    {
        $programs = Programs::all();
        return view('admin.majors.edit', compact('major', 'programs'));
    }

    /**
     * Update the specified major in storage.
     */
    public function update(Request $request, Majors $major)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:majors,name,' . $major->id,
            'program_id' => 'required|exists:programs,id',
        ]);

        $major->update($request->only('name', 'program_id'));

        return redirect()->route('admin.majors.index')->with('success', 'Major updated successfully.');
    }

    /**
     * Remove the specified major from storage.
     */
    public function destroy(Majors $major)
    {
        $major->delete();
        return redirect()->route('admin.majors.index')->with('success', 'Major deleted successfully.');
    }

    /**
     * Handle inline updates via AJAX.
     */
    public function updateInline(Request $request, Majors $major)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:majors,name,' . $major->id,
            'program_id' => 'required|exists:programs,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $major->update($request->only('name', 'program_id'));

        return response()->json(['success' => 'Major updated successfully.']);
    }

    /**
     * Import majors from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        Excel::import(new MajorsImport, $request->file('csv_file'));

        return redirect()->route('admin.majors.index')->with('success', 'Majors imported successfully!');
    }
}
