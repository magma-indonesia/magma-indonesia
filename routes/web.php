<?php
/*
| Magma Indonesia - Routes
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
| UserController, including View, Login, Register, Update, Delete
|
*/
Route::group(['middleware' => ['web','guest']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/login', 'UserController@showLoginForm')->name('login')->middleware('revalidate');
    Route::post('/login', 'UserController@login')->name('login');
});

Route::name('v1.')->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::get('/','v1\HomeController@index')->name('home');
        Route::get('vona','FrontPage\v1\VonaController@index')->name('vona.index');
        Route::get('vona/{id}','FrontPage\v1\VonaController@show')
            ->name('vona.show')
            ->middleware('signed');

        Route::get('press-release','FrontPage\v1\PressController@index')->name('press.index');
        Route::get('press-release/{id}','FrontPage\v1\PressController@show')
            ->name('press.show')
            ->middleware('signed');

        Route::get('gunung-api/informasi-letusan/{code?}','FrontPage\v1\GunungApiController@indexVen')->name('gunungapi.ven');
        Route::get('gunung-api/laporan','FrontPage\v1\GunungApiController@indexVar')
            ->name('gunungapi.var');
        Route::get('gunung-api/laporan/{id?}','FrontPage\v1\GunungApiController@showVar')
            ->name('gunungapi.var.show')
            ->middleware('signed');
        Route::get('gunung-api/laporan/search/{q?}','FrontPage\v1\GunungApiController@indexVar')
            ->name('gunungapi.var.search');

        Route::get('gerakan-tanah/tanggapan','FrontPage\v1\GerakanTanahController@indexGertan')
            ->name('gertan.sigertan');
        Route::get('gerakan-tanah/tanggapan/{id?}','FrontPage\v1\GerakanTanahController@showGertan')
            ->name('gertan.sigertan.show')
            ->middleware('signed');

        Route::name('json.')->group(function () {
            Route::group(['prefix' => 'json'], function () {
                Route::post('var','v1\Json\MapController@showVar')->name('var.show');
                Route::post('highcharts','v1\Json\HighCharts@homeGempaThreeMonths')
                    ->name('highcharts')
                    ->middleware('signed');
                Route::post('gertan','v1\Json\MapController@showSigertan')->name('sigertan.show');
                Route::post('gempa','v1\Json\MapController@showGempa')->name('gempa.show');
                Route::get('has-vona','v1\Json\MapController@hasVona')->name('has.vona');
                Route::get('has-eruptions','v1\Json\MapController@hasEruptions')->name('has.eruptions');
            });
        });
    });
});

Route::get('/logout', 'UserController@logout')->name('logout');
Route::get('/tes', 'TesController@index');
Route::get('/tes/image/', 'TesController@imageCrop');
Route::get('/tes/image/{id?}', 'TesController@getFile')->name('tesimage');
Route::post('/tes/image', 'TesController@imageCropPost');

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