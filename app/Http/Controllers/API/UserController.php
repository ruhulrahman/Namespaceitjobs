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
        if (Gate::allows('isAdmin') || Gate::allows('isSuperAdmin') || Gate::allows('isAuthor')) {
            // The current user can edit
            return User::latest()->paginate(10);
        }
    }

    public function search()
    {
        if($search = \Request::get('q')){
            $users = User::where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('type', 'LIKE', "%$search%")
                        ->paginate(10);
        }else{
            $users = User::latest()->paginate(10);
        }
        return $users;
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:users|max:50',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|min:6|max:255',
            'type' => 'required',
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => 'boy.png',
        ]);
    }

    public function profile()
    {
        return auth("api")->user();
    }

    public function updateProfile(Request $request)
    {
        $user = auth("api")->user();

        $this->validate($request, [
            'name' => 'required|string|max:50|unique:users,name,'.$user->id,
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
        //
    }


    public function update(Request $request, $id)
    {
        
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:50|unique:users,name,'.$user->id,
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6|max:255',
            'type' => 'required',
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
