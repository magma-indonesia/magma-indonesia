<?php

Route::get('/','ChamberController@index')->name('index');

Route::get('routes', function() {
    $routeCollection = Route::getRoutes();

    echo "<table style='width:100%'>";
        echo "<tr>";
            echo "<td width='10%'><h4>HTTP Method</h4></td>";
            echo "<td width='10%'><h4>Route</h4></td>";
            echo "<td width='10%'><h4>Name</h4></td>";
            echo "<td width='70%'><h4>Corresponding Action</h4></td>";
        echo "</tr>";
        foreach ($routeCollection as $value) {
            echo "<tr>";
                echo "<td>" . implode('|', $value->methods()) . "</td>";
                echo "<td>" . $value->uri() . "</td>";
                echo "<td>" . $value->getName() . "</td>";
                echo "<td>" . ltrim($value->getActionName()) . "</td>";
            echo "</tr>";
        }
    echo "</table>";
});

Route::resource('crs','CrsController');
Route::resource('pengajuan','PengajuanController');
Route::post('crs/lokasi','CrsController@getCities')->name('crs.getcities');
Route::get('export/{type}','ExportController@index')->name('export');

Route::name('administratif.')->group(function () {
    Route::resource('jabatan','JabatanController');

    Route::resource('administrasi','UserAdministratifController');

    Route::name('mga.')->group(function () {
        Route::group(['prefix' => 'mga'], function () {
            Route::resource('jenis-kegiatan','JenisKegiatanController');
            Route::resource('kegiatan','KegiatanController');
            Route::get('detail-kegiatan/download/{id}/{type}','DetailKegiatanController@download')
                    ->name('detail-kegiatan.download');
            Route::resource('detail-kegiatan','DetailKegiatanController');
            Route::resource('anggota-kegiatan','AnggotaKegiatanController');
        });
    });
});

Route::get('absensi/search','AbsensiController@search')->name('absensi.search');
Route::resource('absensi','AbsensiController');

Route::resource('users', 'UserController');

Route::name('indonesia.')->group(function () {
    Route::group(['prefix' => 'indonesia'], function () {
        Route::get('provinsi','IndonesiaController@getProvinsi')->name('provinsi');
        Route::get('provinsi/{id_provinsi}','IndonesiaController@getKota')->name('kota');
        Route::get('provinsi/{id_provinsi}/{id_kota}','IndonesiaController@getKabupaten')->name('kabupaten');
    });
});

Route::name('stakeholder.')->group(function () {
    Route::group(['middleware' => ['role:Super Admin'], 'prefix' => 'stakeholder'], function () {
        Route::get('/','StakeholderController@index')->name('index');
        Route::get('/create','StakeholderController@create')->name('create');
        Route::post('/store','StakeholderController@store')->name('store');
        Route::get('/{stakeholder}/edit','StakeholderController@edit')->name('edit');
        Route::put('/{stakeholder}','StakeholderController@update')->name('update');
        Route::delete('/{stakeholder}','StakeholderController@destroy')->name('destroy');
    });
});

Route::group(['prefix' => 'gunungapi'], function () {
    Route::resource('datadasar','DataDasar');
    Route::resource('pos','PosPgaController');
    Route::resource('letusan','MagmaVenController');
    Route::resource('laporan-guguran','LaporanGuguranController');

    Route::name('peralatan.')->group(function () {
        Route::group(['prefix' => 'peralatan'], function () {
            Route::get('/','AlatController@index')->name('index');
            Route::get('jenis/create','AlatController@jenis')->name('jenis.create');
            Route::get('create','AlatController@create')->name('create');
            Route::post('create','AlatController@store')->name('store');
        });
    });

    Route::name('rsam.')->group(function () {
        Route::group(['prefix' => 'rsam'], function () {
            Route::get('/','RsamController@index')->name('index');
        });
    });

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

Route::resource('roles', 'RoleController');
Route::resource('press','PressController');

Route::get('vona/draft','VonaController@draft')->name('vona.draft');
Route::get('vona/search','VonaController@search')->name('vona.search');
Route::post('vona/send','VonaController@send')->name('vona.send');
Route::resource('vona/subscribers','VonaSubscriberController');
Route::resource('vona/exercise','VonaExerciseSubscriberController');
Route::resource('vona', 'VonaController');

Route::name('json.')->group(function () {
    Route::group(['prefix' => 'json'], function () {
        Route::post('peralatan','Json\PeralatanJson@index')
                ->name('peralatan.index')
                ->middleware('signed');

        Route::post('rsam','Json\RsamJson@index')
                ->name('rsam')
                ->middleware('signed');

        Route::post('wovodat\plume','Json\WOVOdat\GasPlumeJson@index')
                ->name('wovodat.plume')
                ->middleware('signed');
    });
});

Route::name('wovodat.')->group(function () {
    Route::group(['prefix' => 'wovodat'], function () {

        Route::view('/','wovodat.index')
            ->name('index');

        Route::get('volcano','WOVOdat\VolcanoController@index')
            ->name('volcano.index');

        Route::get('seismic-network','WOVOdat\SeismicNetworkController@index')
            ->name('seismic-network.index');

        Route::get('common-network','WOVOdat\CommonNetworkController@index')
            ->name('common-network.index');

        Route::get('common-network/deformation-station','WOVOdat\DeformationStationController@index')
            ->name('common-network.deformation-station.index');

        Route::get('common-network/deformation-station/tilt','WOVOdat\DeformationTiltController@index')
            ->name('common-network.deformation-station.tilt.index');
        Route::post('common-network/deformation-station/tilt','WOVOdat\DeformationTiltController@store')
            ->name('common-network.deformation-station.tilt.store');

        Route::get('common-network/gas-station','WOVOdat\GasStationController@index')
            ->name('common-network.gas-station.index');

        Route::get('common-network/gas-station/plume','WOVOdat\GasPlumeController@index')
            ->name('common-network.gas-station.plume.index');
        Route::post('common-network/gas-station/plume','WOVOdat\GasPlumeController@store')
            ->name('common-network.gas-station.plume.store');

        Route::get('interval-swarm','WOVOdat\IntervalSwarmController@index')
            ->name('interval-swarm.index');
        Route::post('interval-swarm','WOVOdat\IntervalSwarmController@store')
            ->name('interval-swarm.store');

        Route::get('rsam','WOVOdat\RsamController@create')
            ->name('rsam.create');
        Route::post('rsam','WOVOdat\RsamController@store')
            ->name('rsam.store');

        Route::get('event','WOVOdat\SeismicEventController@create')
            ->name('event.create');
        Route::post('event','WOVOdat\SeismicEventController@store')
            ->name('event.store');
    });
});

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

                Route::resource('form-kesimpulan','v1\KesimpulanController');

                Route::get('laporan/filter','v1\MagmaVarController@filter')
                    ->name('laporan.filter');
                Route::get('laporan/filter/gempa','v1\MagmaVarController@filterGempa')
                    ->name('laporan.filter.gempa');
                
                Route::get('laporan/create-var','v1\MagmaVarController@createVar')
                    ->name('laporan.create.var');
                Route::post('laporan/store-var','v1\MagmaVarController@storeVar')
                    ->name('laporan.store.var');

                Route::get('laporan/create-rekomendasi','v1\MagmaVarController@createRekomendasi')
                    ->name('laporan.create.rekomendasi');
                Route::post('laporan/store-rekomendasi','v1\MagmaVarController@storeRekomendasi')
                    ->name('laporan.store.rekomendasi');

                Route::get('laporan/create-visual','v1\MagmaVarController@createVisual')
                    ->name('laporan.create.visual');
                Route::post('laporan/store-visual','v1\MagmaVarController@storeVarVisual')
                    ->name('laporan.store.visual');

                Route::get('laporan/create-klimatologi','v1\MagmaVarController@createKlimatologi')
                    ->name('laporan.create.klimatologi');
                Route::post('laporan/store-klimatologi','v1\MagmaVarController@storeVarKlimatologi')
                    ->name('laporan.store.klimatologi');

                Route::post('laporan/exists','v1\MagmaVarController@exists')
                    ->name('laporan.exists');

                Route::get('laporan','v1\MagmaVarController@index')
                    ->name('laporan.index');

                Route::get('laporan/{id}','v1\MagmaVarController@show')
                    ->name('laporan.show');

                Route::get('evaluasi','v1\MagmaVarEvaluasi@index')
                    ->name('evaluasi.index');
                Route::get('evaluasi/result','v1\MagmaVarEvaluasi@show')
                    ->name('evaluasi.show');
                Route::get('evaluasi/result','v1\MagmaVarEvaluasi@result')
                    ->name('evaluasi.result');

                Route::get('ven','v1\MagmaVenController@index')
                    ->name('ven.index');
                Route::get('ven/filter','v1\MagmaVenController@filter')
                    ->name('ven.filter');
                Route::get('ven/{id}','v1\MagmaVenController@show')
                    ->name('ven.show');
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

        Route::name('visitor.')->group(function() {
            Route::get('visitor','v1\VisitorController@index')->name('index');
            Route::get('visitor/{year}','v1\VisitorController@filter')->name('filter');
        });
    });
});
