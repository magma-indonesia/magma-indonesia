<?php

use Illuminate\Support\Facades\Route;

Route::get('/','ImportController@index')->name('index');
Route::post('pengajuan','Import\ImportPengajuan@import')->name('pengajuan');
Route::post('crs','Import\ImportCrs@import')->name('crs');
Route::post('sigertan','Import\ImportSigertan@import')->name('sigertan');
Route::post('gadds','Import\ImportGadd@import')->name('gadds');
Route::any('absensi','Import\ImportAbsensi@import')->name('absensi');   
Route::post('users','Import\ImportUsers@import')->name('users');
Route::post('administrasi','Import\ImportAdministratif@import')->name('administrasi');
Route::any('vars','Import\ImportMagmaVar@import')->name('vars');
Route::post('dailies','Import\ImportVarHarian@import')->name('dailies');
Route::any('visuals','Import\ImportVarVisual@import')->name('visuals');
Route::any('foto-visuals','Import\ImportFotoVisual@import')->name('foto-visuals');
Route::any('klimatologi','Import\ImportVarKlimatologi@import')->name('klimatologi');
Route::any('gempa','Import\ImportGempa@import')->name('gempa');
Route::post('rekomendasi','Import\ImportVarRekomendasi@import')->name('rekomendasi');
Route::post('vens','Import\ImportMagmaVen@import')->name('vens');
Route::post('vona','Import\ImportVona@import')->name('vona');
Route::post('subscribers','Import\ImportSubscriber@import')->name('subscribers');
Route::post('subscribers/excercise','Import\ImportExerciseSubscriber@import')->name('exercise_subscribers');
Route::post('roq','Import\ImportRoq@import')->name('roq');
Route::post('adm','Import\ImportUserAdm@import')->name('adm');
Route::get('status','ImportController@status')->name('status');