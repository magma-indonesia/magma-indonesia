<?php

namespace App\Traits\v1;

use Illuminate\Http\Request;
use App\v1\Gadd;

trait GunungApiTerdekat
{
    protected function getGunungApiTerdekat($latitude, $longitude, $radius = 2000)
    {
        $gadd = Gadd::selectRaw('( 3959 * acos( cos( radians(?) ) * cos( radians( ga_lat_gapi ) ) * cos( radians( ga_lon_gapi ) - radians(?)) + sin( radians(?) ) * sin( radians( ga_lat_gapi ) ) ) ) AS distance, ga_nama_gapi AS gunungapi, ga_code AS code', [$latitude, $longitude, $latitude])
                    ->havingRaw('distance < ?', [$radius])
                    ->orderBy('distance')
                    ->first();

        $gadd = $gadd 
            ? $gadd->gunungapi.' ('.intval($gadd->distance).' km)'
            : null;

        return $gadd;
    }
}