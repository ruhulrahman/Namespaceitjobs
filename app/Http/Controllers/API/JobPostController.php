<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\JobPost;
use Illuminate\Http\Request;

class JobPostController extends Controller
{

    public function index()
    {
        return JobPost::all();
    }


    public function create()
    {
        
    }


    public function store(Request $request)
    {
        $user_id = auth("api")->user()->id;

        $this->validate($request, [
            'job_title' => 'required|string',
            'job_description' => 'required|string',
            'salary' => 'required|integer',
            'location' => 'required|string',
            'country' => 'required|string',
        ]);

        return JobPost::create([
            'user_id' => $user_id,
            'job_title' => $request['job_title'],
            'job_description' => $request['job_description'],
            'salary' => $request['salary'],
            'location' => $request['location'],
            'country' => $request['country'],
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
        $jobPost = JobPost::findOrFail($id);
        $this->validate($request, [
            'job_title' => 'required|string',
            'job_description' => 'required|string',
            'salary' => 'required|integer',
            'location' => 'required|string',
            'country' => 'required|string',
        ]);

        $jobPost->update($request->all());  
    }


    public function destroy($id)
    {
        $query = JobPost::findOrFail($id);
        $query->delete();
    }
}
