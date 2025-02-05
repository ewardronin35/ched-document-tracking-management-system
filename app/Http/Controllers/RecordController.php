<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Contracts\DataTableScope;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Route;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */
    protected function getCurrentPrefix()
    {
        $routeName = Route::currentRouteName(); // e.g., 'admin.so_master_lists.index'
        $parts = explode('.', $routeName);
        return $parts[0] ?? null; // 'admin' or 'records'
    }

    public function index()
    {
        // Retrieve all records (consider pagination for large datasets)
        $prefix = $this->getCurrentPrefix();
        $records = Record::all();
        if ($prefix === 'admin') {
            $records = Record::all();
            return view('admin.records.index', compact('records'));
        } elseif ($prefix === 'records') {
            $records = Record::all();
            return view('records.index', compact('records'));
        } else {
            abort(403, 'Unauthorized access.');
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'project' => 'required|string|max:255',
            'relevant_hei' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
            'name_of_document' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'transaction_type' => 'required|string|max:255',
            'assigned_staff' => 'required|string|max:255',
            'collaborators' => 'nullable|string',
            'upload_files' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        Record::create($request->all());

        return redirect()->route('records.index')
                         ->with('success', 'Record created successfully.');
    }
    public function data(Request $request)
    {
        $query = Record::query();
        return DataTables::of($query)
            ->addColumn('actions', function($record) {
                $buttons  = '<a href="'.route('admin.record.edit', $record->id).'" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>';
                $buttons .= '<form action="'.route('admin.record.destroy', $record->id).'" method="POST" style="display:inline-block;">'.csrf_field().method_field('DELETE').'<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash-alt"></i></button></form>';
                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Record $record)
    {
        return view('records.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Record $record)
    {
        return view('records.edit', compact('record'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Record $record)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'project' => 'required|string|max:255',
            'relevant_hei' => 'required|string|max:255',
            'document_type' => 'required|string|max:255',
            'name_of_document' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'transaction_type' => 'required|string|max:255',
            'assigned_staff' => 'required|string|max:255',
            'collaborators' => 'nullable|string',
            'upload_files' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $record->update($request->all());

        return redirect()->route('records.index')
                         ->with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->route('records.index')
                         ->with('success', 'Record deleted successfully.');
    }
}
