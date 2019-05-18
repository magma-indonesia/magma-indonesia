<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class EvaluasiRequest extends FormRequest
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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Semua form harus diisi',
            'date_format' => 'Format tanggal tidak sesuai (Y-m-d)',
            'before' => 'Tanggal awal harus sebelum tanggal akhir',
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
            'code' => 'required|size:3',
            'jenis' => 'required|in:0,1,2',
            'start' => 'required_if:jenis,0|date_format:Y-m-d|before:end',
            'end' => 'required_if:jenis,2|date_format:Y-m-d',
            'gempa' => 'required|array',
            'gempa.*' => 'in:lts,apl,apg,gug,hbs,tre,tor,lof,hyb,vtb,vta,vlp,tel,trs,tej,dev,gtb,hrm,dpt,mtr'
        ];
    }
}
