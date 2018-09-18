<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\VonaExerciseSubscriber;
use App\Traits\ImportHelper;
use App\v1\VonaExerciseSubscriber as OldSub;

class ImportExerciseSubscriber extends Import
{
    use ImportHelper;
    
    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldSub::whereBetween('no',[$this->startNo('exer_subs'),$this->endNo('exer_subs')])->get();

        $this->old = $this->old->reject(function ($value, $key) {
            return !filter_var($value->email, FILTER_VALIDATE_EMAIL);
        });

        $this->old->each(function ($item,$key) {
            $this->setItem($item)->createSubscriber();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'Subscribers', 'message' => 'Subscriber berhasil diperbarui', 'count' => VonaExerciseSubscriber::count() ] 
                : [ 'success' => 0, 'text' => 'Subscribers', 'message' => 'Subscriber gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createSubscriber()
    {
        $no = $this->item->no;

        try {
            $create = VonaExerciseSubscriber::firstOrCreate(
                [
                    'email' => $this->item->email
                ],
                [
                    'name' => $this->item->nama,
                    'status' => $this->item->subscribe
                ]
            );

            if ($create) {
                $this->data = $this->tempTable('exer_subs',$no);
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }
}
