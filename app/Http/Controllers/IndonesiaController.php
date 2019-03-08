<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IndonesiaProvince as Provinsi;
use App\IndonesiaCity as Kota;
use App\IndonesiaDistrict as Kabupaten;
use App\IndonesiaVillage as Desa;
use Illuminate\Support\Facades\Cache;

class IndonesiaController extends Controller
{
    public function getProvinsi()
    {
        return Cache::remember('provinsi', 120, function() {
            return Provinsi::all();
        });
    }

    public function getKota($id_provinsi)
    {
        return Cache::remember('kota/'.$id_provinsi, 120, function() use ($id_provinsi) {
            return Kota::where('province_id',$id_provinsi)->get();
        });
    }

    public function getKabupaten($id_provinsi,$id_kota)
    {
        return Cache::remember('kabupaten/'.$id_provinsi.'-'.$id_kota, 120, function() use ($id_kota) {
           return Kabupaten::where('city_id',$id_kota)->get();
        });
    }

    public function getDesa($id_provinsi,$id_kota,$id_kabupaten)
    {
        return Cache::remember('desa/'.$id_provinsi.'-'.$id_kota.'-'.$id_kabupaten, 120, function() use ($id_kabupaten) {
           return Desa::where('district_id',$id_kabupaten)->get();
        });
    }
}
