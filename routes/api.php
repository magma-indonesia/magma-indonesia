<?php

use App\Http\Controllers\Api\VonaController;
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

Route::get('login/v1', 'Api\v1\LoginController@login')
    ->name('login.v1');
Route::get('logout/v1', 'Api\v1\LoginController@logout')
    ->name('logout.v1');
Route::get('login/v1/status', 'Api\v1\LoginController@status')
    ->name('login.v1.status');

Route::get('vona', 'Api\VonaController@index')
    ->name('vona.index');
Route::get('vona/descriptive', [VonaController::class, 'indexDescriptive'])
    ->name('vona.descriptive');
Route::get('vona/latest', [VonaController::class, 'latest'])
    ->name('vona.latest');
Route::get('vona/latest/descriptive', [VonaController::class, 'latestDescriptive'])
    ->name('vona.latest.descriptive');
Route::get('vona/filter', [VonaController::class, 'filter'])
    ->name('vona.filter');
Route::get('vona/{uuid}', 'Api\VonaController@show')
    ->name('vona.show');
Route::get('vona/{vona}/descriptive', [VonaController::class, 'showDescriptive'])
    ->name('vona.show.descriptive');

Route::name('gunung-api.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::group(['prefix' => 'gunung-api'], function () {
            Route::get('/', 'Api\GaddController@index')
                ->name('index');
            Route::get('/filter', 'Api\GaddController@filter')
                ->name('filter');
            Route::get('/{code}', 'Api\GaddController@show')
                ->name('show');

        });
    });
});

Route::name('mounts.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::group(['prefix' => 'mounts'], function () {
            Route::get('/', 'Api\MountsController@index')
                ->name('index');
            Route::get('latest/{code}', 'Api\MountsController@latest')
                ->name('latest');
            Route::get('update', 'Api\MountsController@update')
                ->name('update');
            Route::post('store', 'Api\MountsController@store')
                ->name('store');
        });
    });
});

Route::name('vogamos.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::group(['prefix' => 'vogamos'], function () {
            Route::get('/', 'Api\VogamosController@index')
                ->name('index');
            Route::post('store', 'Api\VogamosController@store')
                ->name('store');
        });
    });
});

// Route::group(['prefix' => 'tesuser'], function() {
//     Route::get('mgt','TesUserMgtController@index');
//     Route::get('mgb','TesUserMgbController@index');
// });

Route::name('v1.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function() {
        Route::group(['prefix' => 'v1'], function () {
            Route::get('import/vona','Api\ImportController@vona')
                ->name('import.vona');

            Route::get('bencana-geologi/terkini', 'Api\v1\BencanaGeologiController@index')
                ->name('bencana-geologi.terkini');

            Route::get('user','Api\UserController@index')
                ->name('user.index');
            Route::get('user/{nip}','Api\UserController@show');

            Route::get('var/latest','Api\VarController@latest');
            Route::get('var/{id}','Api\VarController@show');

            Route::get('vona','Api\v1\VonaController@index')
                ->name('vona.index');
            Route::get('vona/filter', 'Api\v1\MagmaVenController@filter')
                ->name('vona.filter');
            Route::get('vona/latest','Api\v1\VonaController@latest')
                ->name('vona.latest');
            Route::get('vona/{uuid}','Api\v1\VonaController@show')
                ->name('vona.show');

            Route::get('magma-roq','Api\OldRoqController@index');
            Route::get('magma-roq/{no}','Api\OldRoqController@show');

            Route::get('magma-ven','Api\v1\MagmaVenController@index');
            Route::get('magma-ven/filter', 'Api\v1\MagmaVenController@filter')
                ->name('magma-ven.filter');

            Route::get('magma-var','Api\v1\MagmaVarController@index');
            Route::get('magma-var/filter','Api\v1\MagmaVarController@filter')
                ->name('magma-var.filter');
            Route::get('magma-var/seismic/{code}','Api\v1\MagmaVarController@seismic')
                ->name('magma-var.seismicity');
            Route::get('magma-var/{code}/{noticenumber?}','Api\v1\MagmaVarController@show')
                ->name('magma-var.show');

            Route::get('magma-sigertan','Api\v1\MagmaSigertanController@index');

            Route::get('press-release','Api\v1\PressController@index');
            Route::get('press-release/{id}/{slug}', 'Api\v1\PressController@show');
            Route::get('press-release/{id}', 'Api\v1\PressController@show');

            Route::name('home.')->group(function () {
                Route::group(['prefix' => 'home'], function () {

                    Route::get('resume-harian','Api\v1\ResumeHarianController@index')
                            ->name('resume-harian.index');
                    Route::get('resume-harian/latest','Api\v1\ResumeHarianController@latest')
                            ->name('resume-harian.latest');
                    Route::get('resume-harian/{date}','Api\v1\ResumeHarianController@show')
                            ->name('resume-harian.show');

                    Route::get('esdm','Api\v1\EsdmController@index')
                            ->name('esdm');

                    Route::group(['prefix' => 'gunung-api'], function () {
                        Route::get('/','Api\v1\HomeController@gunungapi')
                            ->name('gunung-api');
                        Route::get('/cctv', 'Api\v1\KameraGunungApiController@index')
                            ->name('gunung-api.cctv');
                        Route::post('/cctv', 'Api\v1\KameraGunungApiController@show')
                            ->name('gunung-api.cctv');
                        Route::get('/cctv/{code}', 'Api\v1\KameraGunungApiController@filter')
                            ->name('gunung-api.cctv.filter');
                        Route::get('/informasi-letusan','Api\v1\MagmaVenController@index')
                            ->name('gunung-api.letusan');
                        Route::get('/informasi-letusan/latest','Api\v1\MagmaVenController@latest')
                            ->name('gunung-api.letusan.latest');
                        Route::get('/informasi-letusan/filter', 'Api\v1\MagmaVenController@filter')
                            ->name('gunung-api.letusan.filter');
                        Route::get('/informasi-letusan/{id}','Api\v1\MagmaVenController@show')
                            ->name('gunung-api.letusan.show');
                        Route::get('/status','Api\v1\HomeController@gunungapiStatus')
                            ->name('gunung-api.status');
                        Route::get('/var/{code}','Api\v1\HomeController@showVar')
                            ->name('gunung-api.var.show');
                    });

                    Route::group(['prefix' => 'gerakan-tanah'], function () {
                        Route::get('/', 'Api\v1\MagmaSigertanController@index')
                            ->name('gerakan-tanah');
                        Route::get('/filter', 'Api\v1\MagmaSigertanController@filter')
                            ->name('gerakan-tanah.filter');
                        Route::get('/latest', 'Api\v1\MagmaSigertanController@latest')
                            ->name('gerakan-tanah.latest');
                        Route::get('/{id}', 'Api\v1\MagmaSigertanController@show')
                            ->name('gerakan-tanah.show');
                    });

                    Route::group(['prefix' => 'gempa-bumi'], function () {
                        Route::get('/', 'Api\v1\MagmaRoqController@index')
                            ->name('gempa-bumi');
                        Route::get('/filter', 'Api\v1\MagmaRoqController@filter')
                            ->name('gempa-bumi.filter');
                        Route::get('/latest', 'Api\v1\MagmaRoqController@latest')
                            ->name('gempa-bumi.latest');
                        Route::get('/{id}', 'Api\v1\MagmaRoqController@show')
                            ->name('gempa-bumi.show');
                    });

                    Route::group(['prefix' => 'edukasi'], function() {
                        Route::get('glossary', 'Api\v1\GlossaryController@index')
                            ->name('edukasi.glossary');
                    });
                });
            });

            Route::name('python.')->group(function () {
                Route::group(['prefix' => 'python'], function () {

                    Route::group(['prefix' => 'magma-var'], function () {
                        Route::get('evaluasi', 'Api\v1\Python\MagmaVarEvaluasi@result')
                            ->name('magma-var.evaluasi');
                    });

                });
            });

            Route::name('android.')->group(function () {
                Route::group(['prefix' => 'android'], function () {

                    // Route::group(['prefix' => 'public'], function () {
                    //     Route::post('token', 'Api\AndroidPublicController@public')
                    //     ->name('public.token');
                    // });
                });
            });

        });
    });
});

Route::name('wovodat.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function() {
        Route::group(['prefix' => 'wovodat'], function () {

            Route::post('tilt/realtime/{deformation_station?}','Api\WOVOdat\DeformationTiltController@realtime')
                ->name('tilt.realtime');

            Route::post('tilt/realtime/{deformation_station}/data','Json\WOVOdat\RealtimeTiltJson@json')
                ->name('tilt.realtime.data');

        });
    });
});

Route::name('novac.')->group(function () {
    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::group(['prefix' => 'novac'], function () {
            Route::get('/', 'Api\NovacDataController@index')
                ->name('index');
            Route::post('store', 'Api\NovacDataController@create')
                ->name('create');
        });
    });
});

Route::name('winston.')->group(function () {
    Route::group(['prefix' => 'winston'], function () {

        Route::get('helicorder', 'Api\Winston\HelicorderController@index')
            ->name('helicorder.index');
        Route::get('helicorder/{scnl}', 'Api\Winston\HelicorderController@show')
            ->name('helicorder.show');

    });
});

// Route::fallback(function(){
//     return response()->json([
//         'status' => 'false',
//         'code' => '404',
//         'message' => 'URL tidak ditemukan'], 404);
// });