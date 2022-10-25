<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VonaCreateRequest extends FormRequest
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
            'code.exists' => 'Kode gunung api tidak terdaftar',
            'code.size'  => 'Kode gunung api tidak terdaftar',
            'type.required' => 'Jenis VONA tidak ditemukan',
            'type.in' => 'Jenis VONA tidak valid (Real atau Exercise?)',
            'color.required' => 'Color Code tidak ditemukan',
            'color.in' => 'Warna Color Code tidak valid',
            'erupsi_berlangsung.required' => 'Informasi erupsi sedang berlangsung belum dipilih',
            'durasi.required_if' => 'Durasi erupsi perlu diisi jika erupsi telah selesai',
            'visibility.required' => 'Visibility belum diisi',
            'visibility.boolean' => 'Visibility hanya memiliki nilai Teramati atau Tidak Teramati',
            'date.date_format' => 'Format tanggal tidak sesuai (Y-m-d H:i:s, contoh: 2017-10-02 23:20:20)',
            'height.between' => 'Tinggi kolom letusan minimum :min dan maksimum :max meter',
            'warna_asap.*.in' => 'Data Warna Abu invalid',
            'intensitas.*.in' => 'Data Intensitas invalid',
            'arah_asap.*.in' => 'Arah abu invalid',
            '*.required' => 'Data harus diisi jika Visual Letusan atau Guguran teramati',
            '*.numeric' => 'Data harus dalam bentuk Numeric',
            '*.between' => 'Nilai :attribute Minimum :min dan maksimum :max',
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

    protected function greenConditional()
    {
        return [
            'amplitudo' => request()->color == 'green' ? 'nullable|numeric|between:0,240' : 'required|numeric|between:0,240',
            'durasi' => request()->color == 'green' ? 'nullable|numeric|between:0,10000' : 'required_if:erupsi_berlangsung,0|nullable|numeric|between:0,10000',
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
            'code' => 'required|size:3|exists:ga_dd,code',
            'type' => 'required|in:real,exercise',
            'color' => 'required|in:auto,red,orange,yellow,green',
            'date' => 'required|date_format:Y-m-d H:i:s|before:tomorrow',
            'erupsi_berlangsung' => 'required|boolean',
            'visibility' => 'required|boolean',
            'height' => 'required_if:visibility,1|nullable|numeric|between:0,20000',
            'warna_asap' => 'required_if:visibility,1|array',
            'warna_asap.*' => 'required_if:visibility,1|in:Putih,Kelabu,Coklat,Hitam',
            'intensitas' => 'required_if:visibility,1|array',
            'intensitas.*' => 'required_if:visibility,1|in:Tipis,Sedang,Tebal',
            'arah_abu' => 'required_if:visibility,1|array',
            'arah_abu.*' => 'required_if:visibility,1|in:Utara,Timur Laut,Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut',
            'lainnya' => 'nullable',
        ];

        $rules = array_merge($rules, $this->greenConditional());

        return $rules;
    }
}
