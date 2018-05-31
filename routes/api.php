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
Route::post('login', 'Api\UserController@login');
Route::get('logout', 'Api\UserController@logout');

Route::group(['prefix' => 'tesuser'], function() {
    Route::get('mgt','TesUserMgtController@index');
    Route::get('mgb','TesUserMgbController@index');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::get('import/vona','Api\ImporController@vona');       
        Route::get('user','Api\UserController@index');
        Route::get('user/{nip}','Api\UserController@show');
        Route::get('var/latest','Api\VarController@latest');
        Route::get('var/{id}','Api\VarController@show');
        Route::get('vona','Api\VonaController@index');
        Route::get('vona/{uuid}','Api\VonaController@show');

        Route::resource('roq','Api\OldRoqController', ['except' => [
            'create','edit','store','destroy'
        ]]);
    });
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return new UserResource(User::find(1));
// });
