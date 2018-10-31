<?php

Route::name('fpl.')->group(function() {
    Route::get('/fpl','Fun\FplPvmbgController@index')->name('index');
});