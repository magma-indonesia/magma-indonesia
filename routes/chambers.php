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

Route::get('absensi/search','AbsensiController@search')->name('absensi.search');
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
    Route::get('laporan/create-var','MagmaVarController@createVar')
        ->name('laporan.create.var');
    Route::get('laporan/select-var-rekomendasi','MagmaVarController@selectVarRekomendasi')
        ->name('laporan.select.var.rekomendasi');
    Route::get('laporan/create-var-visual','MagmaVarController@createVarVisual')
        ->name('laporan.create.var.visual');
    Route::get('laporan/create-var-klimatologi','MagmaVarController@createVarKlimatologi')
        ->name('laporan.create.var.klimatologi');
    Route::get('laporan/create-var-gempa','MagmaVarController@createVarGempa')
        ->name('laporan.create.var.gempa');
    Route::post('laporan/create-var','MagmaVarController@storeVar')
        ->name('laporan.store.var');
    Route::post('laporan/select-var','MagmaVarController@storeVarRekomendasi')
        ->name('laporan.store.var.rekomendasi');
    Route::post('laporan/create-var-visual','MagmaVarController@storeVarVisual')
        ->name('laporan.store.var.visual');
    Route::post('laporan/create-var-klimatologi','MagmaVarController@storeVarKlimatologi')
        ->name('laporan.store.var.klimatologi');
    Route::post('laporan/create-var-gempa','MagmaVarController@storeVarGempa')
        ->name('laporan.store.var.gempa');
    Route::post('laporan/delete-var-rekomendasi/{id}','MagmaVarController@destroyVarRekomendasi')
        ->name('laporan.destroy.var.rekomendasi');
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

Route::resource('press','PressController');

Route::get('vona/draft','VonaController@draft')->name('vona.draft');
Route::get('vona/search','VonaController@search')->name('vona.search');
Route::post('vona/send','VonaController@send')->name('vona.send');
Route::resource('vona/subscribers','VonaSubscriberController');
Route::resource('vona/exercise','VonaExerciseSubscriberController');
Route::resource('vona', 'VonaController');

Route::name('v1.')->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::resource('press','v1\PressReleaseController');
        Route::resource('users','v1\UserController');
    });
});
