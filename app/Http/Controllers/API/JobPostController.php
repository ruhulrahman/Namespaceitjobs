<?php

namespace App\Http\Controllers\API;

use App\User;
use App\JobPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function search()
    {
        if($search = \Request::get('q')){
            $query = JobPost::where('job_title', 'LIKE', "%$search%")
                        ->paginate(30);
        }else{
            $query = JobPost::latest()->paginate(30);
        }
        return $query;
    }

    public function show($id)
    {
        return JobPost::where('id', $id)->first();
    }

    public function jobpostByAuth()
    {
        $user_id = auth("api")->user()->id;
        // $user = User::find($user_id);
        return JobPost::where('user_id', $user_id)->get();
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
