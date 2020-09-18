<?php

namespace App\Http\Controllers\API;

use App\JobApply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JobApplyController extends Controller
{
    public function index()
    {
        return JobApply::all();
    }


    public function create()
    {
        
    }


    public function store(Request $request)
    {
        $user_id = auth("api")->user()->id;

        return JobApply::create([
            'job_id' => $request['job_id'],
            'user_id' => $user_id,
        ]);
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $query = JobApply::findOrFail($id);
        $this->validate($request, [
            'job_title' => 'required|string',
            'job_description' => 'required|string',
            'salary' => 'required|integer',
            'location' => 'required|string',
            'country' => 'required|string',
        ]);

        $query->update($request->all());  
    }


    public function destroy($id)
    {
        $query = JobApply::findOrFail($id);
        $query->delete();
    }
}
