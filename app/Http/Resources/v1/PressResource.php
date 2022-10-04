<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PressResource extends JsonResource
{

    protected function signedUrl()
    {
        return URL::signedRoute('v1.press.show', $this);
    }

    protected function datetTime()
    {
        $datetime = $this->datetime ?: $this->log;
        return $datetime;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'datetime' => $this->datetTime()->format('Y-m-d H:i:s'),
            'datetime_text' => $this->datetTime()->format('Y-m-d H:i:s') . ' WIB',
            'datetime_deskripsi' => $this->datetTime()->diffForHumans(),
            'judul' => $this->judul,
            'deskripsi' => Str::limit(strip_tags($this->deskripsi), 280),
            'image' => $this->fotolink,
            'share' => [
                'url' =>  $this->signedUrl(),
                'description' => Str::limit(strip_tags($this->deskripsi), 280),
                'photo' => $this->fotolink
            ],
        ];
    }
}
