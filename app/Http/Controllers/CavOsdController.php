<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CavsOsd;
use Illuminate\Support\Facades\Validator;

class CavOsdController extends Controller
{
    /**
     * Display a listing of the CAV-OSD records.
     */
    public function data()
    {
        // For example, if you have a Cav model that holds your records:
        $data = \App\Models\CavsOsd::all(); // or use appropriate filtering/ordering

        return response()->json($data);
    }

    /**
     * Store a newly created CAV-OSD record in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quarter'            => 'nullable|string',
            'o'                  => 'nullable|string',         // e.g., O-
            'seq'                => 'nullable|string',
            'cav_osds'           => 'nullable|string',
            'surname'            => 'nullable|string',
            'first_name'         => 'nullable|string',
            'extension_name'     => 'nullable|string',
            'middle_name'        => 'nullable|string',
            'sex'                => 'nullable|in:Male,Female',
            'institution_code'   => 'nullable|string',
            'full_name_of_hei'   => 'nullable|string',
            'address_of_hei'     => 'nullable|string',
            'type_of_heis'       => 'nullable|string',
            'discipline_code'    => 'nullable|string',
            'program_name'       => 'nullable|string',
            'major'              => 'nullable|string',
            'program_level'      => 'nullable|string',
            'status_of_the_program' => 'nullable|string',
            'semester1'          => 'nullable|string',
            'date_started'       => 'nullable|string',
            'semester2'          => 'nullable|string',
            'date_ended'         => 'nullable|string',
            'graduation_date'    => 'nullable|date',
            'units_earned'       => 'nullable|string',
            'special_order_no'   => 'nullable|string',
            'date_applied'       => 'nullable|date',
            'date_released'      => 'nullable|date',
            'purpose_of_cav'     => 'nullable|string',
            'target_country'     => 'nullable|string',
            'semester'           => 'nullable|string',
            'academic_year'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cav = CavsOsd::create($validator->validated());
        return response()->json([
            'message' => 'CAV-OSD record created successfully.',
            'data'    => $cav
        ], 201);
    }

    /**
     * Update the specified CAV-OSD record in storage.
     */
    public function update(Request $request, $id)
    {
        $cav = CavsOsd::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'quarter'            => 'nullable|string',
            'o'                  => 'nullable|string',         // e.g., O-
            'seq'                => 'nullable|string',
            'cav_osds'           => 'nullable|string',
            'surname'            => 'nullable|string',
            'first_name'         => 'nullable|string',
            'extension_name'     => 'nullable|string',
            'middle_name'        => 'nullable|string',
            'sex'                => 'nullable|in:Male,Female',
            'institution_code'   => 'nullable|string',
            'full_name_of_hei'   => 'nullable|string',
            'address_of_hei'     => 'nullable|string',
            'type_of_heis'       => 'nullable|string',
            'discipline_code'    => 'nullable|string',
            'program_name'       => 'nullable|string',
            'major'              => 'nullable|string',
            'program_level'      => 'nullable|string',
            'status_of_the_program' => 'nullable|string',
            'semester1'          => 'nullable|string',
            'date_started'       => 'nullable|string',
            'semester2'          => 'nullable|string',
            'date_ended'         => 'nullable|string',
            'graduation_date'    => 'nullable|date',
            'units_earned'       => 'nullable|string',
            'special_order_no'   => 'nullable|string',
            'date_applied'       => 'nullable|date',
            'date_released'      => 'nullable|date',
            'purpose_of_cav'     => 'nullable|string',
            'target_country'     => 'nullable|string',
            'semester'           => 'nullable|string',
            'academic_year'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cav->update($validator->validated());
        return response()->json([
            'message' => 'CAV-OSD record updated successfully.',
            'data'    => $cav
        ], 200);
    }

    /**
     * Remove the specified CAV-OSD record from storage.
     */
    public function destroy($id)
    {
        $cav = CavsOsd::findOrFail($id);
        $cav->delete();
        return response()->json([
            'message' => 'CAV-OSD record deleted successfully.'
        ]);
    }

    /**
     * Import CAV-OSD records (if needed).
     */
    public function import(Request $request)
    {
        // Implementation for CSV/Excel import (if required)
    }
}
