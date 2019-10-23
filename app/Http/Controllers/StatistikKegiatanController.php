<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class StatistikKegiatanController extends Controller
{
    protected function transformDataHarian($users)
    {
        $collection = $users->map(function ($user, $key) {
            return [
                $user->name, $user->jumlah_dinas
            ];
        });
        
        return $collection->sortByDesc(function ($user, $key) {
            return $user[1];
        })->values()->all();
    }

    protected function transformDataRealisasi($users)
    {
        $collection = $users->map(function ($user, $key) {
            return [
                $user->name, $user->jumlah_realisasi
            ];
        });
        
        return $collection->sortByDesc(function ($user, $key) {
            return $user[1];
        })->values()->all();
    }

    public function index($year = null)
    {
        $users = User::whereHas('anggota_kegiatan', function ($query) use($year) {
                        $query->where('start_date','like','%'.$year ?: now()->format('Y').'%');
                    })
                    ->withCount('anggota_kegiatan')
                    ->get()
                    ->sortByDesc('anggota_kegiatan_count')->values();

        if ($users->isEmpty())
            return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');

        $grafik_harian = $this->transformDataHarian($users);
        $grafik_realisasi = $this->transformDataRealisasi($users);

        return view('mga.statistik-kegiatan.index', compact('users','grafik_harian','grafik_realisasi'));
    }
}
