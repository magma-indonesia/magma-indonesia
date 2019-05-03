<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateVarRekomendasi extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user()->administrasi->bidang_id == 2
            AND auth()->user()->administrasi->kantor_id == 'PVG')
        {
            return true;
        }

        return false;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Rekomendasi harus diisi/dipilih',
            'rekomendasi_text.required_if' => 'Rekomendasi harus diisi/dipilih',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'rekomendasi' => 'required|in:1,9999',
            'rekomendasi_text' => 'required_if:rekomendasi,9999'
        ];
    }
}
