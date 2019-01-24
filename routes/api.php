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

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::get('import/vona','Api\ImportController@vona');       
        Route::get('user','Api\UserController@index');
        Route::get('user/{nip}','Api\UserController@show');
        Route::get('var/latest','Api\VarController@latest');
        Route::get('var/{id}','Api\VarController@show');
        Route::get('vona','Api\v1\VonaController@index');
        Route::get('vona/{uuid}','Api\v1\VonaController@show');
        Route::get('roq','Api\OldRoqController@index');
        Route::get('roq/{no}','Api\OldRoqController@show');
        Route::get('magma-var','Api\v1\MagmaVarController@index');
        Route::get('magma-var/{code}/{noticenumber?}','Api\v1\MagmaVarController@show');
    });
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return new UserResource(User::find(1));
// });
