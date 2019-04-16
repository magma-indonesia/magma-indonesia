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

Route::name('indonesia.')->group(function () {
    Route::group(['prefix' => 'indonesia'], function () {
        Route::get('provinsi','IndonesiaController@getProvinsi')->name('provinsi');
        Route::get('provinsi/{id_provinsi}','IndonesiaController@getKota')->name('kota');
        Route::get('provinsi/{id_provinsi}/{id_kota}','IndonesiaController@getKabupaten')->name('kabupaten');
    });
});

Route::group(['prefix' => 'gunungapi'], function () {
    Route::resource('datadasar','DataDasar');
    Route::resource('pos','PosPgaController');
    Route::resource('letusan','MagmaVenController');
    Route::get('laporan/search','ActivityGaController@search')->name('laporan.search');
    Route::post('laporan/verifikasiv1','ActivityGaController@verifikasiv1')->name('laporan.verifikasiv1');
    Route::post('laporan/validasi','ActivityGaController@validasi')->name('laporan.validasi');

    /**
     * Create and Store Var
     */
    Route::get('laporan/create-var','MagmaVarController@createVar')
        ->name('laporan.create.var');
    Route::post('laporan/create-var','MagmaVarController@storeVar')
        ->name('laporan.store.var');

    /**
     * Select, Create, Store, and Delete Rekomendasi
     */
    Route::get('laporan/select-var-rekomendasi','MagmaVarController@selectVarRekomendasi')
        ->name('laporan.select.var.rekomendasi');
    Route::post('laporan/select-var-rekomendasi','MagmaVarController@storeVarRekomendasi')
        ->name('laporan.store.var.rekomendasi');
    Route::post('laporan/delete-var-rekomendasi/{id}','MagmaVarController@destroyVarRekomendasi')
        ->name('laporan.destroy.var.rekomendasi');

    /**
     * Create and Store Var Visual
     */
    Route::get('laporan/create-var-visual','MagmaVarController@createVarVisual')
        ->name('laporan.create.var.visual');
    Route::post('laporan/create-var-visual','MagmaVarController@storeVarVisual')
        ->name('laporan.store.var.visual');
        
    /**
     * Create and Store Var Klimatologi
     */
    Route::get('laporan/create-var-klimatologi','MagmaVarController@createVarKlimatologi')
        ->name('laporan.create.var.klimatologi');
    Route::post('laporan/create-var-klimatologi','MagmaVarController@storeVarKlimatologi')
        ->name('laporan.store.var.klimatologi');

    /**
     * Create and Store Var Gempa
     */
    Route::get('laporan/create-var-gempa','MagmaVarController@createVarGempa')
        ->name('laporan.create.var.gempa');
    Route::post('laporan/create-var-gempa','MagmaVarController@storeVarGempa')
        ->name('laporan.store.var.gempa');

    /**
     * Preview MAGMA-VAR
     */
    Route::get('laporan/preview-magma-var','MagmaVarController@previewMagmaVar')
        ->name('laporan.preview.magma.var');
    Route::post('laporan/preview-magma-var','MagmaVarController@storePreviewMagmaVar')
        ->name('laporan.store.magma.var');

    /**
     * Check Existing VAR
     */
    Route::post('laporan/exists','MagmaVarController@exists')->name('laporan.exists');

    /**
     * Draft VAR
     */
    Route::resource('laporan/draft','MagmaVarDraftController');
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
        Route::resource('gempabumi','v1\MagmaRoqController');
        Route::resource('subscribers','v1\VonaSubscriberController');

        Route::name('gunungapi.')->group(function () {

            Route::group(['prefix' => 'gunungapi'], function () {

                Route::resource('data-dasar','v1\GaddController', [
                    'except' => [
                        'create','store'
                    ]
                ]);

                Route::get('laporan/filter','v1\MagmaVarController@filter')->name('laporan.filter');
                
                Route::get('laporan/create-var','v1\MagmaVarController@createVar')
                    ->name('laporan.create.var');
                Route::post('laporan/store-var','v1\MagmaVarController@storeVar')
                    ->name('laporan.store.var');

                Route::post('laporan/exists','v1\MagmaVarController@exists')
                    ->name('laporan.exists');

                Route::get('laporan','v1\MagmaVarController@index')
                    ->name('laporan.index');

                Route::get('laporan/{id}','v1\MagmaVarController@show')
                    ->name('laporan.show');

                Route::get('ven','v1\MagmaVenController@index')->name('ven.index');
                Route::get('ven/{id}','v1\MagmaVenController@show')->name('ven.show');
            });
        });

        Route::name('vona.')->group(function() {
            Route::get('vona','v1\VonaController@index')->name('index');
            Route::get('vona/{no}','v1\VonaController@show')->name('show');
            Route::delete('vona/{no}','v1\VonaController@destroy')->name('destroy');
        });

        Route::name('absensi.')->group(function() {
            Route::get('absensi','v1\AbsensiController@index')->name('index');
        });
    });
});
