<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\RekomendasiExists;

class SelectVarRekomendasi extends FormRequest
{

    public function __construct(Request $request)
    {
        $request->merge(['code_id' => session('var')['code_id']]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'rekomendasi_text.required_if' => 'Rekomendasi harus diisi',
        ];
    }

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
            'rekomendasi' => ['required','numeric',new RekomendasiExists],
            'rekomendasi_text' => 'required_if:rekomendasi,9999'
        ];
    }
}
