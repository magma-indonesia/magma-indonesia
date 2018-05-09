<?php

Route::get('/','ImportController@index')->name('index');
Route::post('crs','ImportController@crs')->name('crs');
Route::get('qls','ImportController@sigertan')->name('qls');
Route::post('gadds','ImportController@gadds')->name('gadds');            
Route::post('users','ImportController@users')->name('users');
Route::post('bidang','ImportController@bidang')->name('bidang');
Route::post('vars','ImportController@vars')->name('vars');
Route::post('dailies','ImportController@dailies')->name('dailies');
Route::post('visuals','ImportController@visuals')->name('visuals');
Route::post('klimatologi','ImportController@klimatologis')->name('klimatologi');
Route::post('gempa','ImportController@gempa')->name('gempa');
Route::get('vona','ImportController@vona')->name('vona');