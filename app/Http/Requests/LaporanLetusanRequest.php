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
            'rekomendasi_text.required_if' => 'Rekomendasi baru harus diisi.'
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
            'code' => 'required|size:3',
            'status' => 'required|in:1,2,3,4',
            'date' => 'required|date_format:Y-m-d H:i:s|before:tomorrow',
            'visibility' => 'required|boolean',
            'height' => 'required_if:visibility,1|nullable|numeric|between:100,20000',
            'warna_asap' => 'required_if:visibility,1|array',
            'warna_asap.*' => 'required_if:visibility,1|in:Putih,Kelabu,Coklat,Hitam',
            'intensitas' => 'required_if:visibility,1|array',
            'intensitas.*' => 'required_if:visibility,1|in:Tipis,Sedang,Tebal',
            'arah_abu' => 'required_if:visibility,1|array',
            'arah_abu.*' => 'required_if:visibility,1|in:Utara,Timur Laut,Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut',
            'visibility_apg' => 'required|boolean',
            'distance' => 'required_if:visibility_apg,1|nullable|numeric|between:100,25000',
            'height_guguran' => 'required_if:visibility_apg,1|nullable|numeric|between:0,25000',
            'arah_guguran' => 'required_if:visibility_apg,1|array',
            'arah_guguran.*' => 'required_if:visibility_apg,1|in:Utara,Timur Laut,Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut',
            'amplitudo' => 'required|numeric|between:0,240',
            'durasi' => 'required|numeric|between:0,10000',
            'seismometer_id' => 'required|exists:seismometers,id',
            'rekomendasi' => request()->rekomendasi == '9999' ? 'required' : 'required|exists:var_rekomendasis,id',
            'rekomendasi_text' => 'required_if:rekomendasi,9999',
            'lainnya' => 'nullable',
            'foto' => (request()->visibility == '1' OR request()->visibility_apg == '1') ? 'required|file|mimetypes:image/jpeg' : 'nullable',
            'draft' => 'required|boolean',
            'is_blasted' => 'required|boolean',
        ];
    }
}
