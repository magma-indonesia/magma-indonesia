<?php

use App\Http\Controllers\Chambers\v1\GunungApi\GunungApiController;
use App\Http\Controllers\Chambers\v1\GunungApi\MagmaVarController;
use Illuminate\Support\Facades\Route;


Route::resource('v1/gunung-api/laporan-aktivitas', MagmaVarController::class);
Route::resource('v1/gunung-api', GunungApiController::class);
