<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentAuthentication;
use Illuminate\Support\Facades\Validator;

class DocumentAuthenticationController extends Controller
{
    /**
     * Display a listing of the Document Authentication records.
     */
  public function data()
    {
        // For example, if you have a Document Authentication model that holds your records:
        $data = \App\Models\DocumentAuthentication::all(); // or use appropriate filtering/ordering

        return response()->json($data);
    }

    /**
     * Store a newly created Document Authentication record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quarter'        => 'nullable|integer',
            'No'              => 'nullable|integer',  // e.g., O-
            'document_no'    => 'nullable|string',  // Document number
            'certification'  => 'nullable|string',  // Certification text, etc.
            'surname'        => 'nullable|string',
            'first_name'     => 'nullable|string',
            'extension_name' => 'nullable|string',
            'middle_name'    => 'nullable|string',
            'full_name_of_hei' => 'nullable|string',
            'program_name'   => 'nullable|string',
            'major'          => 'nullable|string',
            'date_of_entry'  => 'nullable|date',
            'date_ended'     => 'nullable|date',
            'year_graduated' => 'nullable|date',
            'so_no'          => 'nullable|string',
            'or_no'          => 'nullable|string',
            'date_applied'   => 'nullable|date',
            'date_released'  => 'nullable|date',
            'remarks'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $doc = DocumentAuthentication::create($validator->validated());
        return response()->json([
            'message' => 'Document Authentication record created successfully.',
            'data'    => $doc
        ], 201);
    }

    /**
     * Update the specified Document Authentication record.
     */
    public function update(Request $request, $id)
    {
        $doc = DocumentAuthentication::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'quarter'        => 'nullable|integer',
            'No'              => 'nullable|integer',  // e.g., O-
            'document_no'    => 'nullable|string',
            'certification'  => 'nullable|string',
            'surname'        => 'nullable|string',
            'first_name'     => 'nullable|string',
            'extension_name' => 'nullable|string',
            'middle_name'    => 'nullable|string',
            'full_name_of_hei' => 'nullable|string',
            'program_name'   => 'nullable|string',
            'major'          => 'nullable|string',
            'date_of_entry'  => 'nullable|date',
            'date_ended'     => 'nullable|date',
            'year_graduated' => 'nullable|date',
            'so_no'          => 'nullable|string',
            'or_no'          => 'nullable|string',
            'date_applied'   => 'nullable|date',
            'date_released'  => 'nullable|date',
            'remarks'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $doc->update($validator->validated());
        return response()->json([
            'message' => 'Document Authentication record updated successfully.',
            'data'    => $doc
        ], 200);
    }

    /**
     * Remove the specified Document Authentication record.
     */
    public function destroy($id)
    {
        $doc = DocumentAuthentication::findOrFail($id);
        $doc->delete();
        return response()->json([
            'message' => 'Document Authentication record deleted successfully.'
        ]);
    }

    /**
     * Import Document Authentication records (if needed).
     */
    public function import(Request $request)
    {
        // Implement import functionality if needed.
    }
}
