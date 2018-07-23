<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateVarStep2 extends FormRequest
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
            'in' => 'Nilai dari form tidak ada terdaftar dalam list',
            'tasap_max.required_if' => 'Tinggi Asap Maximum <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'tasap_min.required_if' => 'Tinggi Asap Minimum <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'lte' => 'Tinggi Asap Minimum <b>harus kurang dari</b> Tinggi Asap Maksimum',
            'digits_between' => 'Tinggi Asap <b>minimal</b> 100 dan <b>maksimum</b> 10000 meter',
            'wasap.required_if' => 'Warna Asap <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'intasap.required_if' => 'Intensitas Asap <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'tekasap.required_if' => 'Tekanan Asap <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {

        $tasap_max = $request->visual_asap == 'Teramati' ? '|digits_between:3,5' : '';
        $tasap_min = $request->visual_asap == 'Teramati' ? '|digits_between:3,5|lte:tasap_max' : '';

        return [
            'visibility' => 'required|array',
            'visibility.*' => 'required|in:Jelas,Kabut 0-I,Kabut 0-II,Kabut 0-III',
            'visual_asap' => 'required|in:Nihil,Teramati,Tidak Teramati',
            'visual_kawah' => 'nullable',
            'tasap_max' => 'required_if:visual_asap,Teramati'.$tasap_max,
            'tasap_min' => 'required_if:visual_asap,Teramati'.$tasap_min,
            'wasap' => 'required_if:visual_asap,Teramati|array',
            'wasap.*' => 'in:Putih,Kelabu,Coklat,Hitam',
            'intasap'  => 'required_if:visual_asap,Teramati|array',
            'intasap.*' => 'in:Tipis,Sedang,Tebal',
            'tekasap'  => 'required_if:visual_asap,Teramati|array',
            'tekasap.*' => 'in:Lemah,Sedang,Kuat',
        ];
    }
}
