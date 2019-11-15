<?php

namespace App\Http\Controllers\Exports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Export extends Controller
{
    protected $old;

    protected $new;

    protected $item;

    protected $key;

    protected $status;

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

    protected function setNotif($data)
    {
        $this->status['success'] = $data['success'];
        $this->status['message'] = $data['message'];
        $this->status['count'] = $data['count'];

        return $this;
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
