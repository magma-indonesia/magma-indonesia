<?php

namespace App\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Peralatan;

class PeralatanJson extends Controller
{
    use Peralatan;

    public function index()
    {
        return $this->getPeralatan();
    }
}
