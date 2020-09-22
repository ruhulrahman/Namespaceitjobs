<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('auth:api');
    }


    public function index()
    {
        // $this->authorize('isAdmin');
        if (Gate::allows('isAdmin') || Gate::allows('isEmployee') || Gate::allows('isEmployer')) {
            // The current user can edit
            return User::latest()->paginate(15);
        }
    }

    // public function jobpostByAuth()
    // {
    //     $user_id = auth("api")->id();
    //     $user = User::find($user_id);
    //     return $user->jobposts()->get();
    // }

    public function search()
    {
        if($search = \Request::get('q')){
            $users = User::where('first_name', 'LIKE', "%$search%")
                        ->orWhere('last_name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->paginate(30);
        }else{
            $users = User::latest()->paginate(30);
        }
        return $users;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|min:6|max:255',
        ]);
        $type = "";
        if($request['business_name']!=""){
            $type = "employer";
        }else{
            $type = "employee";
        }
        

        return User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'business_name' => $request['business_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $type,
            'photo' => 'boy.png',
        ]);
    }

    public function profile()
    {
        // return auth("api")->user();
        
        return User::with('profile')->where('id', auth("api")->user()->id)->first();
    }

    public function updateProfile(Request $request)
    {
        $user = auth("api")->user();

        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6|max:255',
        ]);

        $currentPhoto = $user->photo;
        if($request->photo != $currentPhoto){
            $name = time().'.'.explode("/", explode(":", substr($request->photo, 0, strpos($request->photo, ';'))) [1])[1];
            Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }

        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());
    }

    public function show($id)
    {
        return User::where('id', $id)->first();
    }


    public function update(Request $request, $id)
    {
        
        $user = User::findOrFail($id);

        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6|max:255',
        ]);

        $user->update($request->all());        
        //return ['message' => 'User updated'];
    }


    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        //Delete the user
        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
