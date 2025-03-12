<?php
namespace App\Http\Controllers;

use App\Models\CavLocal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CavLocalController extends Controller
{

    public function data(){ 
        $data = \App\Models\CavLocal::all(); // or use appropriate filtering/ordering
        return response()->json($data);

    }
    public function index()
    {
        $localRecords = CavLocal::all();
        return response()->json($localRecords);
    }

    public function store(Request $request)
    {
        $cavLocal = CavLocal::create($request->all());
        return response()->json($cavLocal);
    }

    // Add other CRUD methods as needed
}