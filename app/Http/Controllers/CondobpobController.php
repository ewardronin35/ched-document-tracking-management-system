<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Condobpob;

class CondobpobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data() {
        $data = \App\Models\Condobpob::all(); // or use appropriate filtering/ordering

        return response()->json($data);

    }
  

    /**
     * Show the form for creating a new resource.
     * (If you're building an API, this may not be used.)
     */
    public function create()
    {
        // Return a view for creating a new record.
        return view('condobpob.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data.
        $validated = $request->validate([
            'quarter'           => 'nullable|string',
            'No'                => 'nullable|integer',
            'surname'           => 'nullable|string|max:255',
            'first_name'        => 'nullable|string|max:255',
            'extension_name'    => 'nullable|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'sex'               => 'nullable|in:Male,Female',
            'or_number'         => 'nullable|string|max:255',
            'name_of_hei'       => 'nullable|string|max:255',
            'special_order_no'  => 'nullable|string|max:18',
            'type_of_correction'=> 'nullable|string|max:255',
            // Use alternative names for reserved words ("from" and "to").
            'from_date'         => 'nullable|string|max:255',
            'to_date'           => 'nullable|string|max:255',
            'date_applied'      => 'nullable|date',
            'date_released'     => 'nullable|date',
        ]);

        // Create a new record.
        $record = Condobpob::create($validated);
        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $record = Condobpob::findOrFail($id);
        return response()->json($record);
    }

    /**
     * Show the form for editing the specified resource.
     * (If you're building an API, this may not be used.)
     */
    public function edit($id)
    {
        $record = Condobpob::findOrFail($id);
        return view('condobpob.edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $record = Condobpob::findOrFail($id);

        $validated = $request->validate([
            'quarter'           => 'nullable|string',
            'No'                => 'nullable|integer',
            'surname'           => 'nullable|string|max:255',
            'first_name'        => 'nullable|string|max:255',
            'extension_name'    => 'nullable|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'sex'               => 'nullable|in:Male,Female',
            'or_number'         => 'nullable|string|max:255',
            'name_of_hei'       => 'nullable|string|max:255',
            'special_order_no'  => 'nullable|string|max:18',
            'type_of_correction'=> 'nullable|string|max:255',
            'from_date'         => 'nullable|string|max:255',
            'to_date'           => 'nullable|string|max:255',
            'date_applied'      => 'nullable|date',
            'date_released'     => 'nullable|date',
        ]);

        $record->update($validated);
        return response()->json($record);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $record = Condobpob::findOrFail($id);
        $record->delete();
        return response()->json(['message' => 'Record deleted successfully.']);
    }
}
