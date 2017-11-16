<?php

Route::get('/', 'HomeController@index')->name('home');


/*
| Magma Indonesia - Routes
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
| UserConstroller, including View, Login, Register, Update, Delete
|
*/
Route::resource('users','UserController');
Route::get('/login', 'UserController@showLoginForm')->name('login');
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout')->name('logout');

/*
|--------------------------------------------------------------------------
| Chamber Routes
|--------------------------------------------------------------------------
| UserConstroller, including View, Login, Register, Update, Delete
|
*/
Route::get('/chambers', 'ChamberController@index')->name('chamber');
Route::group(['prefix' => 'chambers'], function (){
    Route::resource('users', 'UserController');
});
