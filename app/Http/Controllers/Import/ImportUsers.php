<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\User;
use App\v1\User as OldUser;

class ImportUsers extends Import
{

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldUser::whereIn('vg_bid',[
                'Pengamatan dan Penyelidikan Gunungapi',
                'Mitigasi Gerakan Tanah',
                'Mitigasi Gempabumi dan Tsunami',
                'Balai Penyelidikan dan Pengembangan Teknologi Kebencanaan Geologi',
                'Pusat Vulkanologi dan Mitigasi Bencana Geologi',
                'Tata Usaha',
                'MITIGASI GA',
                'MITIGASI GT',
                'BPPTKG',
            ])
            ->orderBy('id')
            ->get();
        $this->old->each(function ($item, $key) {
            $this->setItem($item)->updateUser();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' => 'Data User', 'message' => 'Data User berhasil diperbarui', 'count' => User::count() ] 
                : [ 'success' => 0, 'text' => 'Data User', 'message' => 'Data User gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function updateUser()
    {
        $email = filter_var($this->item->vg_email, FILTER_VALIDATE_EMAIL) 
            ? $this->item->vg_email 
            : null;

        $phone = strlen($this->item->vg_phone) > 9 
            ? str_replace('+62','0',$this->item->vg_phone) 
            : null;

        try {
            $update = User::firstOrCreate(
                [ 
                    'nip' => $this->item->vg_nip 
                ],
                [                 
                    'name'  => $this->item->vg_nama,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $this->item->vg_password,
                    'status' => 1
                ]
            );

            if ($update) {
                $this->data = true;
            }
        }

        catch (\Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }
}