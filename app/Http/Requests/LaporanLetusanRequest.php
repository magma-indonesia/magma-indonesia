<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanLetusanRequest extends FormRequest
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required' => 'Kode gunung api tidak terdaftar',
            'code.size'  => 'Kode gunung api tidak terdaftar',
            'jenis.required' => 'Jenis erupsi yang dipilih',
            'jenis.in'  => 'Pilih antara Erupsi atau Awan Panas Guguran',
            'visibility.required' => 'Visibility belum diisi',
            'visibility.boolean' => 'Visibility hanya memiliki nilai Teramati atau Tidak Teramati',
            'date.date_format' => 'Format tanggal tidak sesuai (Y-m-d H:i, contoh: 2017-10-02 23:20)',
            'height.between' => 'Tinggi kolom letusan minimum :min dan maksimum :max meter',
            'warna_asap.*.in' => 'Data Warna Abu invalid',
            'intensitas.*.in' => 'Data Intensitas invalid',
            'arah_asap.*.in' => 'Arah abu invalid',
            '*.required' => 'Data harus diisi jika Visual Letusan atau Guguran teramati.',
            '*.numeric' => 'Data harus dalam bentuk Numeric',
            '*.between' => 'Nilai :attribute Minimum :min dan maksimum :max',
            'rekomendasi_text.required_if' => 'Rekomendasi baru harus diisi'
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

    protected function letusan()
    {
        return [
            'intensitas' => 'required_if:visibility,1|array',
            'intensitas.*' => 'required_if:visibility,1|in:Tipis,Sedang,Tebal',
        ];
    }

    protected function awanPanasGuguran()
    {
        return [
            'jarak' => 'required_if:visibility_apg,1|nullable|numeric|between:0,25000',
            'arah_guguran' => 'required_if:visibility_apg,1|array',
            'arah_guguran.*' => 'required_if:visibility_apg,1|in:Utara,Timur Laut,Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required|size:3',
            'jenis' => 'required|in:apg,lts',
            'status' => 'required|in:1,2,3,4',
            'date' => 'required|date_format:Y-m-d H:i:s|before:tomorrow',
            'visibility' => 'required|boolean',
            'height' => 'required_if:visibility,1|nullable|numeric|between:0,20000',
            'warna_asap' => 'required_if:visibility,1|array',
            'warna_asap.*' => 'required_if:visibility,1|in:Putih,Kelabu,Coklat,Hitam',
            'arah_abu' => 'required_if:visibility,1|array',
            'arah_abu.*' => 'required_if:visibility,1|in:Utara,Timur Laut,Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut',
            'amplitudo' => 'required|numeric|between:0,240',
            'durasi' => 'required|numeric|between:0,10000',
            'seismometer_id' => 'required|exists:seismometers,id',
            'rekomendasi' => request()->rekomendasi == '9999' ? 'required' : 'required|exists:var_rekomendasis,id',
            'rekomendasi_text' => 'required_if:rekomendasi,9999',
            'lainnya' => 'nullable',
            'foto_letusan' => 'nullable',
            'draft' => 'required|boolean',
            'is_blasted' => 'required|boolean',
        ];

        $rules = request()->jenis == 'apg' ? array_merge($rules, $this->awanPanasGuguran()) : array_merge($rules, $this->letusan());

        return $rules;
    }
}
