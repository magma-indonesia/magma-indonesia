<?php
/*
| Magma Indonesia - Chambers
|--------------------------------------------------------------------------
| Chambers Routes
|--------------------------------------------------------------------------
| Import, CRS, Administrasi, Users, Aktivitas, Gunung Api, 
| Permissions, Roles, Press Release, VONA
|
*/
Route::get('/','ChamberController@index')->name('index');

/*
| Magma Indonesia - Chambers/CRS
|--------------------------------------------------------------------------
| Chambers Routes
|--------------------------------------------------------------------------
| Import, CRS, Administrasi, Users, Aktivitas, Gunung Api, 
| Permissions, Roles, Press Release, VONA
|
*/
Route::resource('crs','CrsController');
Route::post('crs/lokasi','CrsController@getCities')->name('crs.getcities');
Route::get('export/{type}','ExportController@index')->name('export');

Route::name('administratif.')->group(function () {
    Route::resource('jabatan','JabatanController');
});

Route::name('users.administrasi.')->group(function() {
    Route::get('users/administrasi','UserAdministratifController@index')->name('index');
});
Route::resource('users', 'UserController');

Route::group(['prefix' => 'activities'], function(){
    Route::get('/', 'ActivityController@index')->name('activities.index');
});

Route::group(['prefix' => 'gunungapi'], function () {
    Route::resource('datadasar','DataDasar');
    Route::resource('pos','PosPgaController');      
    Route::resource('laporanga','ActivityGaController');
    Route::get('laporan/search','ActivityGaController@search')->name('laporanga.search');
});

Route::resource('permissions', 'PermissionController', ['except' => [
    'show','edit'
]]);
Route::resource('roles', 'RoleController', ['except' => [
    'show'
]]);
Route::resource('press', 'PressController');

// Route::name('vona.')->group(function(){
//     Route::get('vona','VonaController@index')->name('index');
//     Route::post('vona','VonaController@store')->name('store');
//     Route::get('vona/create','VonaController@create')->name('create');
//     Route::delete('vona/{vona}','VonaController@destroy')->name('destroy');
//     Route::match(['put','patch'],'vona/{vona}','VonaController@update')->name('update');
//     Route::get('vona/{vona}','VonaController@show')->name('show');
//     Route::get('vona/{vona}/edit','VonaController@show')->name('edit');
// });
Route::get('vona/draft','VonaController@draft')->name('vona.draft');
Route::resource('vona', 'VonaController');
