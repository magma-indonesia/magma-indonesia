<?php

namespace App\Http\Controllers\Import;
use App\Traits\ImportHelper;
use App\UserAdministratif;
use App\UserBidang;
use App\User;
use App\v1\User as OldUser;
use App\v1\Kantor as OldKantor;

use Illuminate\Http\Request;

class ImportUserAdm extends Import 
{
    use ImportHelper;

    protected $bidang, $kantor;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldUser::with('kantor')->orderBy('id')->get();
        $this->old->each(function ($item, $key) {
            $this->setItem($item)
                ->setUser()
                ->setBidang()
                ->setTglLahir()
                ->setKelamin()
                ->setGolongan()
                ->setKantor()
                ->updateAdm();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'Data Administrasi', 'message' => 'Data Administrasi berhasil diperbarui', 'count' => UserAdministratif::count() ] 
                : [ 'success' => 0, 'text' => 'Data Administrasi', 'message' => 'Data Administrasi gagal diperbarui', 'count' => 0 ];

        // $this->sendNotif($data);

        return response()->json($data);
    }

    protected function setUser()
    {
        $this->user = User::where('nip', $this->item->vg_nip)->firstOrFail();
        return $this;
    }

    protected function setBidang()
    {
        $bidang = $this->item->vg_bid;

        if (empty($bidang) || strlen($bidang)<3 )
        {
            $this->bidang = 5;
            return $this;
        }

        if (str_contains($bidang,'kanologi'))
        {
            $this->bidang = 1;
            return $this;
        }

        if (str_contains($bidang,'unung'))
        {
            $this->bidang = 2;
            return $this;
        }

        if (str_contains($bidang,'anah'))
        {
            $this->bidang = 3;
            return $this;
        }

        if (str_contains($bidang,'empa'))
        {
            $this->bidang = 4;
            return $this;
        }

        if (str_contains($bidang,'alai'))
        {
            $this->bidang = 5;
            return $this;
        }

        if (str_contains($bidang,'saha'))
        {
            $this->bidang = 6;
            return $this;
        }
        
        $this->bidang = 5;
        return $this;
    }

    protected function setTglLahir()
    {
        $nip = strlen($this->item->vg_nip) == 18 ? $this->item->vg_nip : false;
        if ($nip) {
            $year = substr($nip,0,4);
            $month = substr($nip,4,2);
            $day = substr($nip,6,2);
            $birthday = $year.'-'.$month.'-'.$day;
    
            $this->birthday = $birthday;
            return $this;
        }

        $this->birthday = null;
        return $this;
    }

    protected function setKelamin()
    {
        $this->jk = $this->item->vg_jk == 'Perempuan' ? 'PEREMPUAN' : 'LAKI-LAKI';
        return $this;
    }

    protected function setGolongan()
    {
        $this->golongan = strlen($this->item->vg_gol) > 2 ? $this->item->vg_gol : null;
        return $this;
    }

    protected function setKantor()
    {
        $this->kantor = optional($this->item->kantor)->obscode;
        return $this;
    }

    protected function updateAdm()
    {
        try {
            $user = User::where('nip',$this->item->vg_nip)->firstOrFail();

            $create = UserAdministratif::updateOrCreate(
                [
                    'user_id' => $this->user->id
                ],
                [
                    'bidang_id' => $this->bidang,
                    'tanggal_lahir' => $this->birthday,
                    'kelamin' => $this->jk,
                    'golongan' => $this->golongan,
                    'kantor_id' => $this->kantor
                ]
            );

            if ($create) {
                $this->data = true;
            }

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }
}