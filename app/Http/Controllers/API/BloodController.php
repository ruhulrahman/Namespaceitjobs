<?php

namespace App\Http\Controllers\API;

use App\Blood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BloodController extends Controller
{
    public function index()
    {
        return Blood::all();
    }


    public function create()
    {
        
    }


    public function store(Request $request)
    {
        return Blood::create([
            'blood_name' => $request['blood_name'],
            'blood_name_bn' => $request['blood_name_bn'],
        ]);
    }

    public function show($id)
    {

    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $query = Blood::findOrFail($id);
        $this->validate($request, [
            'blood_name' => 'required|string'
        ]);

        $query->update($request->all());  
    }


    public function destroy($id)
    {
        $query = Blood::findOrFail($id);
        $query->delete();
    }
}
