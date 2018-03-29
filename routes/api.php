<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

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
Route::get('login','Api\UserController@index');
Route::post('login', 'Api\UserController@login');

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'v1'], function () {

        Route::get('user/{nip}','Api\UserController@show');
    });
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return new UserResource(User::find(1));
// });
