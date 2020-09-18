<?php

namespace App\Http\Controllers\API;

use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public function index()
    {
        return Profile::all();
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $user_id = auth("api")->user()->id;

        $this->validate($request, [
            'resume' => 'required|string',
            'skills' => 'required|string',
        ]);

        return Profile::create([
            'user_id' => $user_id,
            'resume' => $request['resume'],
            'skills' => $request['skills'],
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
        $profile = Profile::findOrFail($id);

        $this->validate($request, [
            'resume' => 'required|string',
            'skills' => 'required|string',
        ]);

        $profile->update($request->all());  
    }


    public function destroy($id)
    {
        //
    }
}
