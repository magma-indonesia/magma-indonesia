<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\HomeController;

class EsdmController extends HomeController
{
    public function index()
    {
        return [
            'gunung_api' => $this->gunungapiStatus(),
            'gerakan_tanah' => $this->gerakanTanahLatest(),
            'gempa_bumi' => $this->gempaBumiLatest()
        ];
    }
}
