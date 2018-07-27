<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Import as ImportApp;
use App\Notifications\ImportNotification;

class Import extends Controller
{
    protected $old;

    protected $item,$key;

    protected $new;

    protected $status;

    protected $error;

    protected $data = false;

    protected function setNew($new)
    {
        $this->new = $new;
        return $this;
    }

    protected function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    protected function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**     
     *   Untuk mengirim notifikasi ke Slack
     *   @param string $type jenis notifikasi
     * 
     */
    protected function sendNotif($data)
    {
        try {
            $import = new ImportApp();
            $import->notify(new ImportNotification($data['text']));

            $this->status['success'] = $data['success'];
            $this->status['message'] = $data['message'];
            $this->status['count'] = $data['count'];

            return $this;
        }
        catch (Exception $e) {

            $this->status['success'] = $data['success'];
            $this->status['message'] = $data['message'];
            $this->status['count'] = 0;

            return $this;
        }
    }

    protected function sendError($e)
    {
        $data = [
            'success' => 0,
            'message' => $e
        ];
        
        $this->sendNotif($data);

        return response()->json($this->status);
    }
}