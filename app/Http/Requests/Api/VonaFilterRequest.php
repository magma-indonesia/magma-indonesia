<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class VonaFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Semua paramater harus diisi',
            'after_or_equal' => 'Tanggal minimal 2015-05-01',
            'before' => 'Tanggal awal harus sebelum tanggal akhir dan tidak boleh melebihi tanggal hari ini',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => 'required|after_or_equal:2015-05-01|before:today',
            'end_date' => 'required|after_or_equal:start_date|before_or_equal:today',
            'code' => 'required|exists:ga_dd,code',
        ];
    }
}
