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
Route::get('status', 'Api\UserController@status')
    ->name('status');

Route::post('login', 'Api\UserController@login')
    ->name('login');
Route::get('logout', 'Api\UserController@logout')
    ->name('logout');

Route::post('login/stakeholder', 'Api\StakeholderController@login')
    ->name('login.stakeholder');
Route::get('stakeholder/status', 'Api\StakeholderController@status')
    ->name('stakeholder.status');

// Route::group(['prefix' => 'tesuser'], function() {
//     Route::get('mgt','TesUserMgtController@index');
//     Route::get('mgb','TesUserMgbController@index');
// });

Route::name('v1.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function() {
        Route::group(['prefix' => 'v1'], function () {
            Route::get('import/vona','Api\ImportController@vona');       
            Route::get('user','Api\UserController@index');
            Route::get('user/{nip}','Api\UserController@show');
            Route::get('var/latest','Api\VarController@latest');
            Route::get('var/{id}','Api\VarController@show');
            Route::get('vona','Api\v1\VonaController@index')
                    ->name('vona');
            Route::get('vona/latest','Api\v1\VonaController@latest')
                    ->name('vona.latest');
            Route::get('vona/{uuid}','Api\v1\VonaController@show')
                    ->name('vona.show');
            Route::get('magma-roq','Api\OldRoqController@index');
            Route::get('magma-roq/{no}','Api\OldRoqController@show');
            Route::get('magma-ven','Api\v1\MagmaVenController@index');
            Route::get('magma-var','Api\v1\MagmaVarController@index');
            Route::get('magma-var/{code}/{noticenumber?}','Api\v1\MagmaVarController@show');
            Route::get('magma-sigertan','Api\v1\MagmaSigertanController@index');
            Route::get('press-release','Api\v1\PressController@index');
    
            Route::name('home.')->group(function () {
                Route::group(['prefix' => 'home'], function () {
    
                    Route::group(['prefix' => 'gunung-api'], function () {
                        Route::get('/','Api\v1\HomeController@gunungapi')
                            ->name('gunung-api');
                        Route::get('/informasi-letusan','Api\v1\MagmaVenController@index')
                            ->name('gunung-api.letusan');
                        Route::get('/informasi-letusan/latest','Api\v1\MagmaVenController@latest')
                            ->name('gunung-api.letusan.latest');
                        Route::get('/status','Api\v1\HomeController@gunungapiStatus')
                            ->name('gunung-api.status');
                        Route::get('/var/{code}','Api\v1\HomeController@showVar')
                            ->name('gunung-api.var.show');
                    });
    
                    Route::group(['prefix' => 'gerakan-tanah'], function () {
                        Route::get('/','Api\v1\HomeController@gerakanTanah')
                            ->name('gerakan-tanah');
                        Route::get('/latest','Api\v1\HomeController@gerakanTanahLatest')
                            ->name('gerakan-tanah.latest');
                        Route::get('/sigertan/{id}','Api\v1\HomeController@showSigertan')
                            ->name('gerakan-tanah.sigertan.show');
                    });
    
                    Route::group(['prefix' => 'gempa-bumi'], function () {
                        Route::get('/','Api\v1\HomeController@gempaBumi')
                            ->name('gempa-bumi');
                        Route::get('/latest','Api\v1\HomeController@gempaBumiLatest')
                            ->name('gempa-bumi.latest');
                        Route::get('/roq/{id}','Api\v1\HomeController@showGempaBumi')
                            ->name('gempa-bumi.roq.show');
                    });
                });
            });
    
        });
    });
});

Route::fallback(function(){
    return response()->json([
        'status' => 'false',
        'code' => '404',
        'message' => 'URL tidak ditemukan'], 404);
});