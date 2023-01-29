<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->judul),
        ]);
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
            'slug' => 'required|unique:press_releases,slug',
            'datetime' => 'nullable|date_format:Y-m-d H:i',
            'no_surat' => 'nullable|max:254',
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
            'petas' => 'sometimes|required|array',
            'petas.*' => 'sometimes|required|max:3072',
            'is_published' => 'required|boolean',
        ];
    }
}
