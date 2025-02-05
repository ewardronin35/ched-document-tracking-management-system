<?php

namespace App\Http\Controllers;

use App\Models\HEI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\HEIsImport;
use Maatwebsite\Excel\Facades\Excel;
class HEIController extends Controller
{
    /**
     * Display a listing of the HEIs.
     */
    public function index()
    {
        $heis = HEI::all();
        return view('admin.heis.index', compact('heis'));
    }

    /**
     * Show the form for creating a new HEI.
     */
    public function create()
    {
        return view('admin.heis.create');
    }

    /**
     * Store a newly created HEI in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Region' => 'required|string|max:255',
            'HEIs' => 'required|string|max:255',
            'UII' => 'required|string|max:255',
        ]);

        HEI::create($validated);
        return redirect()->route('admin.heis.index')->with('success', 'HEI created successfully!');
    }

    /**
     * Show the form for editing the specified HEI.
     */
    public function edit(HEI $hei)
    {
        return view('admin.heis.edit', compact('hei'));
    }

    /**
     * Update the specified HEI in storage.
     */
    public function update(Request $request, HEI $hei)
    {
        $validated = $request->validate([
            'Region' => 'required|string|max:255',
            'HEIs' => 'required|string|max:255',
            'UII' => 'required|string|max:255',
        ]);

        $hei->update($validated);
        return redirect()->route('admin.heis.index')->with('success', 'HEI updated successfully!');
    }

    /**
     * Remove the specified HEI from storage.
     */
    public function destroy(HEI $hei)
    {
        $hei->delete();
        return redirect()->route('admin.heis.index')->with('success', 'HEI deleted successfully!');
    }
    public function showImportForm()
    {
        return view('admin.heis.import');
    }

    /**
     * Handle the import process.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv|max:2048',
        ]);

        Excel::import(new HEIsImport, $request->file('file'));

        return redirect()->route('admin.heis.index')->with('success', 'HEIs imported successfully!');
    }
}
