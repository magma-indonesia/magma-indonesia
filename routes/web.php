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
        Route::get('/','v1\HomeController@index')->name('index');
        Route::get('vona','FrontPage\v1\VonaController@index')->name('vona.index');

        Route::name('json.')->group(function () {
            Route::group(['prefix' => 'json'], function () {
                Route::post('var','v1\Json\MapController@showVar')->name('var.show');
                Route::any('gertan/{id?}','v1\Json\MapController@showSigertan')->name('sigertan.show');
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