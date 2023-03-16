<?php

namespace App\Http\Controllers\Cendana;

use App\Http\Controllers\Controller;
use App\Services\Cendana\MagmaVarService;

class MagmaVarController extends Controller
{
    public function index(MagmaVarService $magmaVarService)
    {
        return $magmaVarService->var()->storeToOldMagmaVar();
    }
}
