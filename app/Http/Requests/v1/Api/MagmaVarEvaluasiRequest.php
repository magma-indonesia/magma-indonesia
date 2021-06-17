<?php

namespace App\Http\Requests\v1\Api;

use Illuminate\Foundation\Http\FormRequest;

class MagmaVarEvaluasiRequest extends FormRequest
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
            'array' => 'Format Jenis Gempa tidak sesuai',
            'gempa.*.in' => 'Format Jenis Gempa tidak valid',
            'jenis.in' => 'Format Jenis Laporan tidak valid',
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
            'end_date' => 'required|after_or_equal:start_date|before:today',
            'code_ga' => 'required|exists:magma.ga_dd,ga_code',
            'gempa' => 'nullable|array',
            'gempa.*' => 'in:*,lts,apl,apg,gug,hbs,tre,tor,lof,hyb,vtb,vta,vlp,tel,trs,tej,dev,gtb,hrm,dpt,mtr'
        ];
    }
}
