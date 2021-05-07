<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController',
    'jobapply' => 'API\JobApplyController',
    'jobpost' => 'API\JobPostController',
    'userprofile' => 'API\ProfileController',
]);


Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('jobpostByAuth', 'API\JobPostController@jobpostByAuth');

Route::get('profileInfo', 'API\ProfileController@profileInfo');
Route::put('employeeResume', 'API\ProfileController@employeeResume');

//JOb Apply Check
Route::get('jobapplyCheck/{id}', 'API\JobApplyController@jobapplyCheck');
Route::get('jobapplicants/{id}', 'API\JobApplyController@jobapplicants');

//Search Users
Route::get('findUser', 'API\UserController@search');
Route::get('findjob', 'API\JobPostController@search');