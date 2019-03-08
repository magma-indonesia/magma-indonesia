<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateVarVisual extends FormRequest
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
        $messages = [
            'required' => 'Semua form harus diisi',
            'in' => 'Nilai dari form tidak ada terdaftar dalam list',
            'tasap_max.required_if' => 'Tinggi Asap Maximum <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'tasap_min.required_if' => 'Tinggi Asap Minimum <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'boolean' => 'Parameter salah',
            'mimes' => 'Format file tidak sesuai',
            'image' => 'File gambar tidak sesuai',
            'max' => 'Maximum ukuran file 2MB',
            'base64image' => 'Format gambar harus <b>JPG</b> atau <b>JPEG</b>',
            'lte' => 'Tinggi Asap Minimum <b>harus kurang dari</b> Tinggi Asap Maksimum',
            'tasap_min.between' => 'Tinggi Asap Minimum <b>minimal</b> 50 meter dan <b>maksimum</b> 10.000 meter',
            'tasap_max.between' => 'Tinggi Asap Maximum <b>minimal</b> 50 meter dan <b>maksimum</b> 10.000 meter',
            'foto.required_if' => 'File Foto <b>wajib disertakan</b> jika pilihan Foto Visual (Ada)',
            'hapus_foto_lainnya.boolean' => 'Parameter Hapus Foto Tambahan tidak valid',
            'wasap.required_if' => 'Warna Asap <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'intasap.required_if' => 'Intensitas Asap <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
            'tekasap.required_if' => 'Tekanan Asap <b>wajib diisi</b> jika Visual Asap Kawah <b>Teramati</b>',
        ];

        return $messages; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $tasap_max = $request->visual_asap == 'Teramati' ? '|numeric|between:50,10000' : '';
        $tasap_min = $request->visual_asap == 'Teramati' ? '|numeric|between:50,10000|lte:tasap_max' : '';
        $foto = $request->hasfoto ? '|image|mimes:jpeg,jpg|max:2048' : '';

        $foto = session('var_visual')['foto'] ?
            'nullable'.$foto :
            'required_if:hasfoto,1'.$foto;

        $rules = [
            'visibility' => 'required|array',
            'visibility.*' => 'required|in:Jelas,Kabut 0-I,Kabut 0-II,Kabut 0-III',
            'visual_asap' => 'required|in:Nihil,Teramati,Tidak Teramati',
            'visual_kawah' => 'nullable',
            'hasfoto' => 'required|boolean',
            'foto' => $foto,
            'foto_lainnya.*' => 'nullable|image|mimes:jpeg,jpg|max:2048',
            'hapus_foto_lainnya' => 'required|boolean',
            'tasap_max' => 'required_if:visual_asap,Teramati'.$tasap_max,
            'tasap_min' => 'required_if:visual_asap,Teramati'.$tasap_min,
            'wasap' => 'required_if:visual_asap,Teramati|array',
            'wasap.*' => 'in:Putih,Kelabu,Coklat,Hitam',
            'intasap'  => 'required_if:visual_asap,Teramati|array',
            'intasap.*' => 'in:Tipis,Sedang,Tebal',
            'tekasap'  => 'required_if:visual_asap,Teramati|array',
            'tekasap.*' => 'in:Lemah,Sedang,Kuat',
        ];

        return $rules;
    }
}
