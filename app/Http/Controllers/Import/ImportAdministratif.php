<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Traits\ImportHelper;
use App\UserAdministratif as Administrasi;
use App\User;
use App\v1\User as OldUser;
use App\v1\Kantor as OldKantor;

class ImportAdministratif extends Import
{

    use ImportHelper;

    protected $bidang;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {

        $this->importBidang()->importKantor();

        $data = $this->data
                ? [ 'success' => 1, 'text' => 'Administasi Kantor', 'message' => 'Data Administasi Kantor berhasil diperbarui','count' => Administrasi::count() ] 
                : [ 'success' => 0, 'text' => 'Absensi', 'message' => 'Data Administasi Kantor gagal diperbarui','count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function importKantor()
    {
        $this->old = OldKantor::all();

        $this->old->each(function ($item, $key){
            $this->setItem($item)->updateKantorId();
        });

        if ($this->data) {
            return $this;
        }
    }

    protected function importBidang()
    {
        $this->old = OldUser::orderBy('id')->get();
        $this->old->each(function ($item, $key) {
            $this->setItem($item)
                ->convertBidang()
                ->updateBidang();
        });

        if ($this->data) {
            return $this;
        }
    }

    protected function updateBidang()
    {
        if ($user = User::where('nip', $this->item->vg_nip)->first())
        {
            try {
                $create = Administrasi::updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'bidang_id' => $this->bidang
                    ]
                );
    
                if ($create) {
                    $this->data = true;
                    return $this;
                }
    
            }

            catch (Exceptipn $e) {
                $this->sendError($e);
            }
        }

    }

    protected function convertBidang()
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

    protected function updateKantorId()
    {
        $nip = $this->item->vg_nip;
        $obscode = $this->item->obscode;

        if ($user = User::has('administrasi')->select('id')->where('nip',$nip)->first())
        {
            try {
                $administrasi = Administrasi::where('user_id',$user->id)->first();
                $administrasi->kantor_id = $obscode;
                if ($administrasi->save())
                {
                    $this->data = true;
                }
            }

            catch (Exception $e) {
                $this->sendError($e);
            }
        }
    }
}
