<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PressReleaseCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'judul' => 'required|max:254',
            'datetime' => 'nullable|date_format:Y-m-d H:i',
            'categories' => 'required|array',
            'categories.*' => 'required|in:gunung_api,gerakan_tanah,gempa_bumi,tsunami,lainnya',
            'code' => 'sometimes|required|size:3|exists:ga_dd,code',
            'tags' => 'required|array',
            'tags.*' => 'required|exists:tags,id',
            'deskripsi' => 'required|min:100',
            'files' => 'sometimes|required|array',
            'files.*' => 'sometimes|required|max:5120',
            'gambars' => 'sometimes|required|array',
            'gambars.*' => 'sometimes|required|max:3072',
        ];
    }
}
