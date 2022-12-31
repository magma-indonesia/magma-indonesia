<?php
/*
| Magma Indonesia - Routes
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
| UserController, including View, Login, Register, Update, Delete
|
*/

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::get('/','v1\HomeController@home')
        ->name('home');

    Route::get('/welcome', 'HomeController@index')
        ->name('welcome');

    Route::get('login','LoginController@index')
        ->name('login')
        ->middleware('guest');

    Route::post('login','LoginController@login')
        ->name('login.post')
        ->middleware('guest');
});

Route::get('logout','LoginController@logout')
        ->name('logout');

Route::name('v1.')->group(function () {
    Route::group(['prefix' => 'v1', 'middleware' => ['statistik.home']], function () {

        Route::get('/','v1\HomeController@home')
            ->name('home');
        Route::get('home','v1\HomeController@index')
            ->name('home.index');
        Route::get('in-frame','v1\HomeController@frame')
            ->name('home.frame')
            ->middleware('cors');
        Route::post('home/check-location','v1\HomeController@check')
            ->name('home.check-location')
            ->middleware('signed');

        Route::get('statistik/{year?}', 'StatistikController@index')
            ->name('statistik.index');

        Route::get('vona','FrontPage\v1\VonaController@index')
            ->name('vona.index');
        Route::get('vona/{id}','FrontPage\v1\VonaController@show')
            ->name('vona.show')
            ->middleware('signed');

        // Route::post('subscribe', 'SubscriberController@store')
        //     ->name('subscribe.store');

        Route::get('press-release','FrontPage\v1\PressController@index')->name('press.index');
        Route::get('press-release/{id}/{slug}', 'FrontPage\v1\PressController@show')
            ->name('press.show.slug');
        Route::get('press-release/{id}','FrontPage\v1\PressController@show')
            ->name('press.show')
            ->middleware('signed');

        Route::get('gunung-api/peta-kawasan-rawan-bencana','FrontPage\v1\PetaKrbGunungApiController@index')
            ->name('gunungapi.peta-kawasan-rawan-bencana');

        Route::get('gunung-api/tingkat-aktivitas','FrontPage\v1\GunungApiController@tingkatAktivitas')
            ->name('gunungapi.tingkat-aktivitas');

        Route::get('gunung-api/informasi-letusan/{code?}','FrontPage\v1\GunungApiController@indexVen')
            ->name('gunungapi.ven');
        Route::get('gunung-api/informasi-letusan/{id}/show','FrontPage\v1\GunungApiController@showVen')
            ->name('gunungapi.ven.show');

        Route::get('gunung-api/laporan','FrontPage\v1\GunungApiController@indexVar')
            ->name('gunungapi.var');
        Route::get('gunung-api/laporan/{id?}','FrontPage\v1\GunungApiController@showVar')
            ->name('gunungapi.var.show')
            ->middleware('signed');
        Route::get('gunung-api/laporan/search/{q?}','FrontPage\v1\GunungApiController@indexVar')
            ->name('gunungapi.var.search')
            ->middleware('custom_throttle:20,1');

        Route::get('gunung-api/cctv','FrontPage\v1\KameraGunungApiController@index')
            ->name('gunungapi.cctv')
            ->middleware(['blacklist']);
        Route::get('gunung-api/cctv/{code}','FrontPage\v1\KameraGunungApiController@filter')
            ->name('gunungapi.cctv.filter')
            ->middleware(['blacklist']);
        Route::post('gunung-api/cctv/{code}/show','FrontPage\v1\KameraGunungApiController@show')
            ->name('gunungapi.cctv.show')
            ->middleware(['blacklist','signed']);

        Route::get('gunung-api/gallery','FrontPage\v1\GalleryGunungApiController@index')
            ->name('gunungapi.gallery')
            ->middleware('revalidate');
        Route::post('gunung-api/gallery','FrontPage\v1\GalleryGunungApiController@statistic')
            ->name('gunungapi.gallery.post')
            ->middleware('signed');
        Route::get('gunung-api/gallery/search/{q?}', 'FrontPage\v1\GalleryGunungApiController@index')
            ->name('gunungapi.gallery.search')
            ->middleware('custom_throttle:20,1');

        Route::get('gunung-api/live-seismogram','FrontPage\v1\LiveSeismogramController@index')
            ->name('gunungapi.live-seismogram')
            ->middleware('revalidate');
        Route::post('gunung-api/live-seismogram','FrontPage\v1\LiveSeismogramController@show')
            ->name('gunungapi.live-seismogram.show')
            ->middleware('signed');

        Route::get('gunung-api/laporan-harian', 'FrontPage\v1\LaporanHarianController@index')
            ->name('gunungapi.laporan-harian')
            ->middleware('revalidate');
        Route::get('gunung-api/laporan-harian/{date}', 'FrontPage\v1\LaporanHarianController@show')
            ->name('gunungapi.laporan-harian.show')
            ->middleware('revalidate');

        // Route::get('gunung-api/{name}', 'FrontPage\v1\GunungApiByVolcanoController@show')
        //     ->name('gunungapi.show');

        Route::get('gerakan-tanah/tanggapan','FrontPage\v1\GerakanTanahController@indexGertan')
            ->name('gertan.sigertan');
        Route::get('gerakan-tanah/tanggapan/{id?}','FrontPage\v1\GerakanTanahController@showGertan')
            ->name('gertan.sigertan.show')
            ->middleware('signed');
        Route::get('gerakan-tanah/tanggapan/search/{q?}','FrontPage\v1\GerakanTanahController@indexGertan')
            ->name('gertan.sigertan.search')
            ->middleware('custom_throttle:20,1');

        Route::get('gempa-bumi-dan-tsunami/kajian','FrontPage\v1\GempaBumiController@indexGempa')
            ->name('gempabumi.roq');
        Route::get('gempa-bumi-dan-tsunami/kajian/{id?}','FrontPage\v1\GempaBumiController@showGempa')
            ->name('gempabumi.roq.show')
            ->middleware('signed');
        Route::get('gempa-bumi-dan-tsunami/kajian/search/{q?}', 'FrontPage\v1\GempaBumiController@indexGempa')
            ->name('gempabumi.roq.search')
            ->middleware('custom_throttle:20,1');

        Route::get('edukasi','FrontPage\v1\EdukasiController@index')
            ->name('edukasi.index');
        Route::get('edukasi/glossary', 'FrontPage\v1\GlossaryController@index')
            ->name('edukasi.glossary.index');
        Route::get('edukasi/glossary/{slug}', 'FrontPage\v1\GlossaryController@show')
            ->name('edukasi.glossary.show');
        Route::get('edukasi/{slug}','FrontPage\v1\EdukasiController@show')
            ->name('edukasi.show');

        Route::name('json.')->group(function () {
            Route::group(['prefix' => 'json'], function () {
                Route::post('var','v1\Json\MapController@showVar')
                    ->name('var.show')
                    ->middleware('signed');
                Route::post('highcharts','v1\Json\HighCharts@getCharts')
                    ->name('highcharts')
                    ->middleware('signed');
                Route::post('gertan','v1\Json\MapController@showSigertan')
                    ->name('sigertan.show')
                    ->middleware('signed');
                Route::post('gempa','v1\Json\MapController@showGempa')
                    ->name('gempa.show')
                    ->middleware('signed');
                Route::post('has-vona','v1\Json\MapController@hasVona')
                    ->name('has.vona')
                    ->middleware('signed');
                Route::post('has-eruptions','v1\Json\MapController@hasEruptions')
                    ->name('has.eruptions')
                    ->middleware('signed');
            });
        });
    });
});

Route::name('.projects')->group(function () {
    Route::group(['prefix' => 'projects'], function () {

        Route::name('.peer')->group(function () {
            Route::group(['prefix' => 'peer'], function () {
                Route::get('/','Projects\PeerController@index')
                    ->name('index');
            });
        });

    });
});

Route::name('.edukasi')->group(function() {

});

/*
|--------------------------------------------------------------------------
| Chamber Routes
|--------------------------------------------------------------------------
| UserConstroller, including View, Login, Register, Update, Delete
|
*/
Route::group(['middleware' => ['web','auth']], function () {

    Route::get('change-password', 'ChangePasswordController@index')
            ->name('change-password.index');
    Route::put('change-password', 'ChangePasswordController@update')
            ->name('change-password.update');

    Route::group(['prefix' => 'images'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('photo/{id?}/{high?}','UserPhotoController@photo')->name('user.photo');
        });
        Route::get('var/{noticenumber}/{draft?}','MagmaVarPhotoController@show')->name('var.show');
    });
    Route::group(['prefix' => 'img'], function (){
        Route::get('/user/{id}', 'ImageController@user');
    });
});