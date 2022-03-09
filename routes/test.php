<?php

use App\Http\Controllers\Test\TestController;
use Illuminate\Support\Facades\Route;


Route::get('/', [TestController::class, 'index']);
