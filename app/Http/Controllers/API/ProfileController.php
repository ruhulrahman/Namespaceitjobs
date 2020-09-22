<?php

namespace App\Http\Controllers\API;

use App\Profile;
use Faker\Documentor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function index()
    {
        $user = auth("api")->user();
        return Profile::where('user_id', $user->id)->first();
    }


    public function create()
    {
        //
    }


    public function profileInfo(){
        $user_id = auth("api")->user()->id;
        return Profile::where('user_id', $user_id)->first();
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
            'skills' => 'string',            
            // 'resume'  => 'mimes:docx,pdf'
        ]);
        $file = $request->resume;

        $currentresume = $profile->resume;
        if($request->resume != $currentresume){
            $name = time().'.'.explode("/", explode(":", substr($request->resume, 0, strpos($request->resume, ';'))) [1])[1];
            // $request->resume->save(public_path('files/').$name;
            // Storage::putFile($name, new File(public_path('files/')));
            // ($request->resume)->save(public_path('uploads/').$name);
            // $file = $request->resume;
            if($file){
                $destinationPath = 'uploads'; // upload path
                // $file->move($destinationPath, $name);
                Storage::putFileAs($file, new File($destinationPath), $name);
            }else{
                return 'file not found';
            }
            
            // $insert['file'] = "$profilefile";

            $request->merge(['resume' => $name]);

            $userresume = public_path('uploads/').$currentresume;
            if(file_exists($userresume)){
                @unlink($userresume);
            }
        }

        $profile->update($request->all());  
    }


    public function destroy($id)
    {
        //
    }
}
