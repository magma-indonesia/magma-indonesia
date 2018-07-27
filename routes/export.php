<?php

Route::get('/','v1\ExportController@index')->name('index');
Route::get('/vars','Export\ExportMagmaVar@export')->name('vars');
Route::get('/ven','Export\ExportMagmaVen@export')->name('ven');