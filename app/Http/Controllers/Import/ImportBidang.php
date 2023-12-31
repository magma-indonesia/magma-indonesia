<?php

namespace App\Http\Controllers\Import;

use App\UserAdministratif;
use App\User;
use App\v1\User as OldUser;

class ImportBidang extends Import
{

    protected $bidang;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldUser::orderBy('id')->get();
        $this->old->each(function ($item, $key) {
            $this->setItem($item)
                ->convertBidang()
                ->updateBidang();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'User Bidang', 'message' => 'User Bidang berhasil diperbarui', 'count' => UserAdministratif::count() ] 
                : [ 'success' => 0, 'text' => 'User Bidang', 'message' => 'User Bidang gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function updateBidang()
    {
        $user = User::where('nip', $this->item->vg_nip)->firstOrFail();

        try {
            $create = UserAdministratif::updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'bidang_id' => $this->bidang
                ]
            );

            if ($create) {
                $this->data = true;
            }
        }

        catch (\Exception $e) {
            $this->sendError($e);
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
}