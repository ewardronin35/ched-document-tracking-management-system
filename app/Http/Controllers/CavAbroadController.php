<?php
namespace App\Http\Controllers;

use App\Models\CavAbroad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CavAbroadController extends Controller
{
    public function data(){ 
        $data = \App\Models\CavAbroad::all(); // or use appropriate filtering/ordering
        return response()->json($data);

    }
    public function index()
    {
        $abroadRecords = CavAbroad::all();
        return response()->json($abroadRecords);
    }

    public function store(Request $request)
    {
        $cavAbroad = CavAbroad::create($request->all());
        return response()->json($cavAbroad);
    }

    // Add other CRUD methods as needed
}