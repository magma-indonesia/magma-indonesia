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
        Route::resource('press', 'PressController');        
        Route::resource('roles', 'RoleController', ['except' => [
            'show'
        ]]);
        Route::resource('permissions', 'PermissionController', ['except' => [
            'show','edit'
        ]]);
    });
    
    Route::group(['prefix' => 'img'], function (){
        Route::get('/user/{id}', 'ImageController@user');
    });
});


