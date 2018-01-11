<?php
/*
| Magma Indonesia - Routes
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
| UserConstroller, including View, Login, Register, Update, Delete
|
*/
Route::group(['middleware' => ['guest']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/login', 'UserController@showLoginForm')->name('login');
    Route::post('/login', 'UserController@login');
});

Route::get('/logout', 'UserController@logout')->name('logout');
Route::get('/tes', function(){
    return view('tes');
});;

/*
|--------------------------------------------------------------------------
| Chamber Routes
|--------------------------------------------------------------------------
| UserConstroller, including View, Login, Register, Update, Delete
|
*/
Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'chambers'], function (){
        Route::get('/', 'ChamberController@index')->name('chamber');
        Route::resource('users', 'UserController');
        Route::resource('permissions', 'PermissionController', ['except' => [
            'show','edit'
        ]]);
        Route::resource('roles', 'RoleController', ['except' => [
            'show'
        ]]);
        Route::resource('press', 'PressController');
        Route::resource('activities', 'ActivityController');
        
        Route::resource('vona', 'VonaController');    

        Route::group(['prefix' => 'import'], function () {
            Route::get('/', 'ImportController@index')->name('import');
            Route::post('gadds','ImportController@gadds')->name('import.gadds');            
            Route::post('users','ImportController@users')->name('import.users');
            Route::post('vars','ImportController@vars')->name('import.vars');
            Route::post('dailies','ImportController@dailies')->name('import.dailies');
            Route::post('visuals','ImportController@visuals')->name('import.visuals');
            
        });
    });
    
    Route::group(['prefix' => 'img'], function (){
        Route::get('/user/{id}', 'ImageController@user');
    });
});


