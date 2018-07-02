<?php

namespace App\Http\Controllers\Import;

use App\Import as ImportApp;
use App\Notifications\ImportNotification;

class Import extends \App\Http\Controllers\Controller
{
    protected $old;

    protected $item,$key;

    protected $new;

    protected $status;

    protected $error;

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

            $this->status['success'] = 1;
            $this->status['notification'] = 1;
            $this->status['message'] = $data['message'];
            $this->status['count'] = $data['count'];

            return $this;
        }
        catch (Exception $e) {

            $this->status['success'] = 1;
            $this->status['notification'] = $e;
            $this->status['message'] = $data['message'];
            $this->status['count'] = 0;

            return $this;
        }
    }
}