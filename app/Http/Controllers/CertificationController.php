<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certification;
use Illuminate\Support\Facades\Validator;

class CertificationController extends Controller
{
    /**
     * Display a listing of the Certification records.
     */
  
public function data () {
    $data = Certification::all();
    return response()->json($data);
}
    
    /**
     * Store a newly created Certification record in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quarter'        => 'nullable|integer',
            'o_prefix'       => 'nullable|string',
            'cav_no'         => 'nullable|string',
            'certification'  => 'nullable|string',  // e.g., CERTIFICATION DEFUNCT / KUWAIT / MARINA / DFA / STUDENT VERIFICATION
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

        $cert = Certification::create($validator->validated());
        return response()->json([
            'message' => 'Certification record created successfully.',
            'data'    => $cert
        ], 201);
    }

    /**
     * Update the specified Certification record.
     */
    public function update(Request $request, $id)
    {
        $cert = Certification::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'o'              => 'nullable|string',
            'cav_no'         => 'nullable|string',
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

        $cert->update($validator->validated());
        return response()->json([
            'message' => 'Certification record updated successfully.',
            'data'    => $cert
        ], 200);
    }

    /**
     * Remove the specified Certification record from storage.
     */
    public function destroy($id)
    {
        $cert = Certification::findOrFail($id);
        $cert->delete();
        return response()->json([
            'message' => 'Certification record deleted successfully.'
        ]);
    }

    /**
     * Import Certification records (if needed).
     */
    public function import(Request $request)
    {
        // Implement import functionality for certifications if needed.
    }
}
