<?php

Route::get('/','v1\ExportController@index')->name('index');
Route::get('/vars','Export\ExportMagmaVar@export')->name('vars');
Route::get('/restore','Export\ExportMagmaVarBackup@export')->name('restore');
Route::get('/ven','Export\ExportMagmaVen@export')->name('ven');
Route::get('/absensi','Export\ExportAbsensi@export')->name('absensi');
Route::get('/vona','Export\ExportVona@export')->name('vona');