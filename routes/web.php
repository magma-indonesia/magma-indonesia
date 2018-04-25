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
    Route::get('/login', 'UserController@showLoginForm')->name('login');
    Route::post('/login', 'UserController@login');
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
    });

    Route::group(['prefix' => 'chambers'], function () {
        Route::get('/', 'ChamberController@index')->name('chamber');

        Route::group(['prefix' => 'import'], function () {
            Route::get('/', 'ImportController@index')->name('import');
            Route::post('crs','ImportController@crs')->name('import.crs');
            Route::post('gadds','ImportController@gadds')->name('import.gadds');            
            Route::post('users','ImportController@users')->name('import.users');
            Route::post('bidang','ImportController@bidang')->name('import.bidang');
            Route::post('vars','ImportController@vars')->name('import.vars');
            Route::post('dailies','ImportController@dailies')->name('import.dailies');
            Route::post('visuals','ImportController@visuals')->name('import.visuals');
            Route::post('klimatologi','ImportController@klimatologis')->name('import.klimatologi');
            Route::post('gempa','ImportController@gempa')->name('import.gempa');
            Route::get('vona','ImportController@vona')->name('import.vona');                     
        });

        Route::get('export/{jenis}/{tipe?}','ExportController@crs')->name('export');

        Route::resource('crs','CrsController');
        Route::post('crs/lokasi','CrsController@getCities')->name('crs.getcities');
        

        Route::resource('users', 'UserController');

        Route::group(['prefix' => 'gunungapi'], function () {
            Route::resource('datadasar','DataDasar');
            Route::resource('pos','PosPgaController');      
            Route::resource('laporanga','ActivityGaController');
            Route::get('laporan/search','ActivityGaController@search')->name('laporan.gunungapi.search');
            
        });

        Route::resource('permissions', 'PermissionController', ['except' => [
            'show','edit'
        ]]);
        Route::resource('roles', 'RoleController', ['except' => [
            'show'
        ]]);
        Route::resource('press', 'PressController');

        Route::group(['prefix' => 'activities'], function(){
            Route::get('/', 'ActivityController@index')->name('activities.index');
        });
        
        Route::resource('vona', 'VonaController');    

    });
    
    Route::group(['prefix' => 'img'], function (){
        Route::get('/user/{id}', 'ImageController@user');
    });
});