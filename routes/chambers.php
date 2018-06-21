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

Route::resource('absensi','AbsensiController');

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
    Route::resource('letusan','MagmaVenController');
    Route::get('laporan/search','ActivityGaController@search')->name('laporan.search');
    Route::resource('laporan','ActivityGaController');
});

Route::name('gempabumi.')->group(function () {
    Route::group(['prefix' => 'gempabumi'], function () {
        Route::resource('tanggapan','RoqTanggapanController');
        Route::get('/','MagmaRoqController@index')->name('index');
        Route::delete('/{id}','MagmaRoqController@destroy')->name('destroy');
    });
});

Route::resource('permissions', 'PermissionController', ['except' => [
    'show','edit'
]]);

Route::resource('roles', 'RoleController', ['except' => [
    'show'
]]);

Route::resource('press', 'PressController');

Route::get('vona/draft','VonaController@draft')->name('vona.draft');
Route::get('vona/search','VonaController@search')->name('vona.search');
Route::post('vona/send','VonaController@send')->name('vona.send');
Route::resource('vona/subscribers','VonaSubscriberController');
Route::resource('vona', 'VonaController');
