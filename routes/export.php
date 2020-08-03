<?php

use Illuminate\Support\Facades\Route;

Route::get('/','v1\ExportController@index')->name('index');
Route::get('/vars','Exports\ExportMagmaVar@export')->name('vars');
Route::get('/restore','Exports\ExportMagmaVarBackup@export')->name('restore');
Route::get('/ven','Exports\ExportMagmaVen@export')->name('ven');
Route::get('/absensi','Exports\ExportAbsensi@export')->name('absensi');
Route::get('/vona','Exports\ExportVona@export')->name('vona');