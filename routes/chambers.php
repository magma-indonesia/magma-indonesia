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
Route::resource('pengajuan','PengajuanController');
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
    Route::post('laporan/verifikasiv1','ActivityGaController@verifikasiv1')->name('laporan.verifikasiv1');
    Route::post('laporan/validasi','ActivityGaController@validasi')->name('laporan.validasi');
    Route::get('laporan/create-step1','MagmaVarController@createStep1')->name('laporan.create.1');
    Route::get('laporan/create-step2','MagmaVarController@createStep2')->name('laporan.create.2');
    Route::get('laporan/create-step3','MagmaVarController@createStep3')->name('laporan.create.3');
    Route::post('laporan/create-step1','MagmaVarController@storeStep1')->name('laporan.store.1');
    Route::post('laporan/create-step2','MagmaVarController@storeStep2')->name('laporan.store.2');
    Route::post('laporan/create-step3','MagmaVarController@storeStep3')->name('laporan.store.3');
    Route::post('laporan/exists','MagmaVarController@exists')->name('laporan.exists');
    Route::resource('laporan','ActivityGaController', ['except' => [
        'create','store','edit','update','destroy'
    ]]);
});

Route::name('gerakantanah.')->group(function() {
    Route::group(['prefix' => 'gerakantanah'], function () {
        Route::resource('laporan','ActivityMgtController');
    });
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
