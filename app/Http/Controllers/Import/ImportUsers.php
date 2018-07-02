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
        $this->old = OldUser::orderBy('id')->get();
    }

    public function __invoke()
    {
        try {
            $this->old->each(function ($item, $key) {
                $this->setItem($item)->updateUser();
            });
    
            $this->sendNotif(
                [
                    'text' => 'User',
                    'message' => 'Data Users berhasil diperbarui',
                    'count' => User::count() 
                ] 
            );
    
            return response()->json($this->status);
        }

        catch (Exception $e) {
            $data = [
                'success' => 0,
                'message' => $e
            ];
            
            return response()->json($data);
        }
    }

    protected function updateUser()
    {
        $email = filter_var($this->item->vg_email, FILTER_VALIDATE_EMAIL) 
            ? $this->item->vg_email 
            : null;

        $phone = strlen($this->item->vg_phone) > 9 
            ? str_replace('+62','0',$this->item->vg_phone) 
            : null;

        $update = User::updateOrCreate(
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

        return $this;
    }

}