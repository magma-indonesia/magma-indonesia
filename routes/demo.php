<?php
/*
| Magma Indonesia - Routes
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
| UserController, including View, Login, Register, Update, Delete
|
*/
Route::name('nusa-geomatika.')->group(function () {
    Route::group(['prefix' => 'nusa-geomatika'], function () {
        Route::view('/','demo.nusa-geomatika.index')
            ->name('index');

        Route::get('login','LoginController@index')
            ->name('index');

        Route::post('login','LoginController@login')
            ->name('login');
    });
});
