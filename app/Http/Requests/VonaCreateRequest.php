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
            'amplitudo.required' => 'Amplitudo Gempa Letusan belum diisi',
            'amplitudo.required_if' => 'Amplitudo Gempa Letusan harus diisi jika gempa letusan terekam di seismogram',
            'amplitudo.numeric' => 'Amplitudo Gempa Letusan harus berisi angka',
            'amplitudo_tremor.numeric' => 'Amplitudo Tremor harus berisi angka',
            'amplitudo_tremor.required_if' => 'Amplitudo Tremor harus diisi jika tremor terekam di seismogram',
            'amplitudo_tremor.required' => 'Amplitudo Maksimum Tremor Menerus belum diisi',
            'durasi.required_if' => 'Durasi erupsi perlu diisi jika gempa letusan terekam',
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

    protected function durasiCondition()
    {
        if (request()->color == 'green') {
            return 'nullable';
        }

        if (request()->erupsi_berlangsung) {
            return 'nullable';
        }

        return 'required_if:terjadi_gempa_letusan,1|nullable|numeric|between:0,10000';
    }

    protected function greenConditional()
    {
        return [
            'amplitudo' => request()->color == 'green' ?
                'nullable|numeric|between:0,240' : 'required_if:terjadi_gempa_letusan,1|nullable|numeric|between:0,240',
            'amplitudo_tremor' => request()->color == 'green' ?
                'nullable|numeric|between:0,240' : 'required_if:terjadi_tremor,1|nullable|numeric|between:0,240',
            'durasi' => $this->durasiCondition(),
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
            'terjadi_gempa_letusan' => 'required|boolean',
            'terjadi_tremor' => 'required|boolean',
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
