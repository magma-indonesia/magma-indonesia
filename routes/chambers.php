<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ChamberController,
    StatistikController,
    RoutesController,
    OvertimeController,
};

Route::get('/',[ChamberController::class, 'index'])->name('index');
Route::get('statistik/{year?}', [StatistikController::class, 'index'])
    ->name('statistik.index');

Route::get('routes', [RoutesController::class, 'index']);

Route::resource('edukasi', EdukasiController::class);
Route::resource('glossary', GlossaryController::class);
Route::resource('crs', CrsController::class);
Route::resource('pengajuan', PengajuanController::class);

Route::get('overtime/{date?}/{flush?}', [OvertimeController::class, 'index'])
    ->name('overtime.index');
Route::get('overtime/{nip}/show/{date?}', [OvertimeController::class, 'show'])
    ->name('overtime.show');
// Route::resource('overtime', OvertimeController::class);

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
            Route::get('statistik-kegiatan/{year?}','StatistikKegiatanController@index')
                    ->name('statistik-kegiatan.index');
        });
    });
});

Route::get('absensi/search','AbsensiController@search')->name('absensi.search');
Route::resource('absensi','AbsensiController');

Route::get('users/statistik/login/{date?}', 'StatistikLoginController@index')
    ->name('users.statistik.login');
Route::get('users/reset','UserController@reset')
    ->name('users.reset');
Route::put('users/reset','UserController@resetPassword')
    ->name('users.reset');
Route::resource('users', 'UserController');

Route::name('indonesia.')->group(function () {
    Route::group(['prefix' => 'indonesia'], function () {
        Route::get('provinsi','IndonesiaController@getProvinsi')->name('provinsi');
        Route::get('provinsi/{id_provinsi}','IndonesiaController@getKota')->name('kota');
        Route::get('provinsi/{id_provinsi}/{id_kota}','IndonesiaController@getKabupaten')->name('kabupaten');
    });
});

Route::name('partial.')->group(function () {
    Route::group(['prefix' => 'partial'], function () {
        Route::post('rekomendasi/{code?}/{status?}', 'VarRekomendasiController@partial')->name('rekomendasi');
        Route::post('seismometer/letusan/{code?}/{id?}', 'SeismometerController@partial')->name('seismometer');
        Route::post('seismometer/event-catalog/{code?}/{scnl?}', 'SeismometerController@partialScnl')->name('seismometer.scnl');
    });
});

Route::name('token.')->group(function () {
    Route::group(['prefix' => 'token'], function () {
        Route::get('/', 'TokenRequestController@index')
            ->name('index');
        Route::post('generate', 'TokenRequestController@generate')
            ->name('generate');
    });
});

Route::name('migration.')->group(function () {
    Route::group(['prefix' => 'migration'], function () {
        Route::get('/', 'MigrationController@index')
            ->name('index');
        Route::delete('/{migration}', 'MigrationController@destroy')
            ->name('destroy');
    });
});

Route::name('blacklist.')->group(function () {
    Route::group(['prefix' => 'blacklist'], function () {
        Route::get('/', 'BlacklistController@index')
            ->name('index');
        Route::post('/store', 'BlacklistController@store')
            ->name('store');
        Route::delete('/{blacklist}', 'BlacklistController@destroy')
            ->name('destroy');
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
    Route::resource('cctv','KameraGunungApiController');
    Route::resource('seismometer','SeismometerController');
    Route::resource('krb-gunungapi','PetaKrbGunungApiController');
    Route::resource('home-krb','HomeKrbController');
    Route::get('daftar-rekomendasi','VarRekomendasiController@index')
            ->name('rekomendasi.index');

    Route::resource('bencana-geologi','BencanaGeologiController');
    Route::resource('bencana-geologi-pendahuluan','BencanaGeologiPendahuluanController');
    Route::resource('resume-harian','ResumeHarianController');

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
        'create','store','edit','update'
    ]]);

    Route::resource('event-catalog', 'EventCatalogController', ['except' => [
        'show',
    ]]);
});

Route::name('gerakan-tanah.')->group(function() {
    Route::group(['prefix' => 'gerakan-tanah'], function () {
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

Route::get('roles/assign', 'RoleController@assign')
    ->name('roles.assign');
Route::post('roles/assign', 'RoleController@saveRole');
Route::resource('roles', 'RoleController');

Route::post('press-release/publish/{id?}', 'PressReleaseController@publish')
    ->name('press-release.publish');
Route::resource('press-release', PressReleaseController::class);

Route::resource('tag', 'TagController');

Route::post('vona/send/{vona}', 'VonaController@send')
    ->name('vona.send-email');
Route::post('vona/pdf/{vona}', 'VonaController@pdf')
    ->name('vona.pdf');
Route::post('vona/reupdate/{vona}', 'VonaController@reupdate')
    ->name('vona.reupdate');
Route::resource('vona/subscribers','VonaSubscriberController');
Route::resource('vona', 'VonaController');

Route::name('json.')->group(function () {
    Route::group(['prefix' => 'json'], function () {
        Route::post('peralatan','Json\PeralatanJson@index')
                ->name('peralatan.index')
                ->middleware('signed');

        Route::post('rsam','Json\RsamJson@index')
                ->name('rsam')
                ->middleware('signed');

        Route::post('wovodat/plume','Json\WOVOdat\GasPlumeJson@index')
                ->name('wovodat.plume')
                ->middleware('signed');

        Route::post('wovodat/tilt/{deformation_station?}','Json\WOVOdat\RealtimeTiltJson@json')
                ->name('wovodat.tilt.realtime')
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
        Route::get('common-network/deformation-station/tilt/realtime/{deformation_station?}','WOVOdat\DeformationTiltController@realtime')
            ->name('common-network.deformation-station.tilt.realtime');

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

        Route::get('kantor', 'v1\KantorController@index')
            ->name('kantor.index');
        Route::get('kantor/pos-pengamatan-gunung-api', 'v1\KantorController@indexByPosPengamatan')
            ->name('kantor.pos-pengamatan.index');
        Route::get('kantor/gunung-api', 'v1\KantorController@indexByGunungApi')
            ->name('kantor.gunung-api.index');

        Route::get('gempabumi/filter','v1\MagmaRoqController@filter')
            ->name('gempabumi.filter');
        Route::post('gempabumi/filter','v1\MagmaRoqController@filter')
            ->name('gempabumi.filter');
        Route::resource('gempabumi','v1\MagmaRoqController');

        // Route::resource('gerakan-tanah','v1\MagmaSigertanController');

        Route::resource('subscribers','v1\VonaSubscriberController');

        Route::name('gunungapi.')->group(function () {

            Route::group(['prefix' => 'gunungapi'], function () {

                Route::resource('data-dasar','v1\GaddController', [
                    'except' => [
                        'create','store'
                    ]
                ]);

                Route::get('rekap-laporan/{year?}', 'RekapPembuatLaporanController@index')
                    ->name('rekap-laporan.index');
                Route::get('rekap-laporan/{year}/gunung-api', 'RekapPembuatLaporanController@indexByGunungApi')
                    ->name('rekap-laporan.index.gunung-api');
                Route::get('rekap-laporan/{year}/gunung-api/{slug}', 'RekapPembuatLaporanController@showByGunungApi')
                    ->name('rekap-laporan.show.gunung-api');
                Route::get('rekap-laporan/{year}/{nip}', 'RekapPembuatLaporanController@showByNip')
                    ->name('rekap-laporan.show.nip');


                // Route::resource('laporan-harian', 'v1\LaporanHarianController');

                Route::resource('form-kesimpulan','v1\KesimpulanController');

                Route::get('laporan/compile/var','v1\CompileVarController@compile')
                    ->name('laporan.compile');
                Route::get('laporan/compile','v1\CompileVarController@index')
                    ->name('laporan.compile.index');

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
                    ->name('evaluasi.result')
                    ->middleware('softban');
                Route::delete('evaluasi/result/{id}','v1\MagmaVarEvaluasi@destroy')
                    ->name('evaluasi.destroy');

                Route::get('ven','v1\MagmaVenController@index')
                    ->name('ven.index');
                Route::get('ven/filter','v1\MagmaVenController@filter')
                    ->name('ven.filter');
                Route::get('ven/export','v1\MagmaVenController@export')
                    ->name('ven.export');
                Route::get('ven/{id}','v1\MagmaVenController@show')
                    ->name('ven.show');

            });
        });

        Route::name('vona.')->group(function() {
            Route::get('vona','v1\VonaController@index')->name('index');
            Route::get('vona/filter', 'v1\VonaController@filter')->name('filter');
            Route::get('vona/{no}','v1\VonaController@show')->name('show');
            Route::delete('vona/{no}','v1\VonaController@destroy')->name('destroy');
        });

        Route::resource('absensi','v1\AbsensiController');

        Route::name('visitor.')->group(function() {
            Route::get('visitor','v1\VisitorController@index')->name('index');
            Route::get('visitor/{year}','v1\VisitorController@filter')->name('filter');
        });
    });
});

// Route::get('test/{year?}', 'RekapPembuatLaporanController@index')->name('test');
Route::get('test', 'TestController@index')->name('test');