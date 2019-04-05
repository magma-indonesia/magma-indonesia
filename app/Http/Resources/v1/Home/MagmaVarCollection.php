<?php

namespace App\Http\Resources\v1\Home;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MagmaVarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return MagmaVarResource::collection($this->collection);
    }
}