<?php

namespace App\Http\Requests;

use App\Http\Requests\PressReleaseCreateRequest;

class PressReleaseUpdateRequest extends PressReleaseCreateRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'slug' => 'required|unique:press_releases,slug,' . $this->route('press_release')->id,
        ]);
    }
}
