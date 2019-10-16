<?php
/*
| Magma Indonesia - Routes
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
| UserController, including View, Login, Register, Update, Delete
|
*/
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'HomeController@index')
        ->name('home');

    Route::get('login','LoginController@index')
        ->name('login')
        ->middleware('guest');

    Route::post('login','LoginController@login')
        ->name('login.post')
        ->middleware('guest');
});

Route::get('logout','LoginController@logout')
        ->name('logout');

Route::name('v1.')->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        
        Route::get('/','v1\HomeController@home')
            ->name('home');
        Route::get('home','v1\HomeController@index')
            ->name('home.index');
        Route::get('in-frame','v1\HomeController@frame')
            ->name('home.frame')
            ->middleware('cors');
        Route::post('home/check-location','v1\HomeController@check')
            ->name('home.check-location')
            ->middleware('signed');
            
        Route::get('vona','FrontPage\v1\VonaController@index')->name('vona.index');
        Route::get('vona/{id}','FrontPage\v1\VonaController@show')
            ->name('vona.show')
            ->middleware('signed');

        Route::get('press-release','FrontPage\v1\PressController@index')->name('press.index');
        Route::get('press-release/{id}','FrontPage\v1\PressController@show')
            ->name('press.show')
            ->middleware('signed');

        Route::get('gunung-api/informasi-letusan/{code?}','FrontPage\v1\GunungApiController@indexVen')
            ->name('gunungapi.ven');
        Route::get('gunung-api/informasi-letusan/{id}/show','FrontPage\v1\GunungApiController@showVen')
            ->name('gunungapi.ven.show');
        Route::get('gunung-api/laporan','FrontPage\v1\GunungApiController@indexVar')
            ->name('gunungapi.var');
        Route::get('gunung-api/laporan/{id?}','FrontPage\v1\GunungApiController@showVar')
            ->name('gunungapi.var.show')
            ->middleware('signed');
        Route::get('gunung-api/laporan/search/{q?}','FrontPage\v1\GunungApiController@indexVar')
            ->name('gunungapi.var.search')
            ->middleware('throttle:15,1');
        Route::get('gunung-api/cctv/{code?}','FrontPage\v1\KameraGunungApiController@index')
            ->name('gunungapi.cctv')
            ->middleware('revalidate');
        Route::post('gunung-api/cctv','FrontPage\v1\KameraGunungApiController@show')
            ->name('gunungapi.cctv.show')
            ->middleware('signed');

        Route::get('gerakan-tanah/tanggapan','FrontPage\v1\GerakanTanahController@indexGertan')
            ->name('gertan.sigertan');
        Route::get('gerakan-tanah/tanggapan/{id?}','FrontPage\v1\GerakanTanahController@showGertan')
            ->name('gertan.sigertan.show')
            ->middleware('signed');
        Route::get('gerakan-tanah/tanggapan/search/{q?}','FrontPage\v1\GerakanTanahController@indexGertan')
            ->name('gertan.sigertan.search')
            ->middleware('throttle:15,1');

        Route::get('gempa-bumi-dan-tsunami/tanggapan','FrontPage\v1\GempaBumiController@indexGempa')
            ->name('gempabumi.roq');
        Route::get('gempa-bumi-dan-tsunami/tanggapan/{id?}','FrontPage\v1\GempaBumiController@showGempa')
            ->name('gempabumi.roq.show')
            ->middleware('signed');
        Route::get('gempa-bumi-dan-tsunami/tanggapan/search/{q?}','FrontPage\v1\GerakanTanahController@indexGertan')
            ->name('gempabumi.roq.search')
            ->middleware('throttle:15,1');

        Route::name('json.')->group(function () {
            Route::group(['prefix' => 'json'], function () {
                Route::post('var','v1\Json\MapController@showVar')
                    ->name('var.show')
                    ->middleware('signed');
                Route::post('highcharts','v1\Json\HighCharts@getCharts')
                    ->name('highcharts')
                    ->middleware('signed');
                Route::post('gertan','v1\Json\MapController@showSigertan')
                    ->name('sigertan.show')
                    ->middleware('signed');
                Route::post('gempa','v1\Json\MapController@showGempa')
                    ->name('gempa.show')
                    ->middleware('signed');
                Route::post('has-vona','v1\Json\MapController@hasVona')
                    ->name('has.vona')
                    ->middleware('signed');
                Route::post('has-eruptions','v1\Json\MapController@hasEruptions')
                    ->name('has.eruptions')
                    ->middleware('signed');
            });
        });
    });
});

Route::name('.projects')->group(function () {
    Route::group(['prefix' => 'projects'], function () {

        Route::name('.peer')->group(function () {
            Route::group(['prefix' => 'peer'], function () {
                Route::get('/','Projects\PeerController@index')
                    ->name('index');
            });
        });

    });
});

Route::get('tes', 'TesController@index');
Route::get('tes/image/', 'TesController@imageCrop');
Route::get('tes/image/{id?}', 'TesController@getFile')->name('tesimage');
Route::post('tes/image', 'TesController@imageCropPost');

/*
|--------------------------------------------------------------------------
| Chamber Routes
|--------------------------------------------------------------------------
| UserConstroller, including View, Login, Register, Update, Delete
|
*/
Route::group(['middleware' => ['web','auth']], function () {
    Route::group(['prefix' => 'images'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('photo/{id?}/{high?}','UserPhotoController@photo')->name('user.photo');
        });
        Route::get('var/{noticenumber}/{draft?}','MagmaVarPhotoController@show')->name('var.show');
    });    
    Route::group(['prefix' => 'img'], function (){
        Route::get('/user/{id}', 'ImageController@user');
    });
});