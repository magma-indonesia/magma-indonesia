<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateVarKlimatologi extends FormRequest
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
            'cuaca.required' => 'Cuaca harus dipilih minimal satu',
            'kecepatan_angin.required' => 'Kecepatan Angin harus dipilih minimal satu',
            'arah_angin.required' => 'Arah Angin harus dipilih minimal satu',
            'array' => 'Nilai tidak dalam format array',
            'in' => 'Nilai tidak ada dalam list',
            'numeric' => 'Nilai harus dalam format numeric',
            'curah_hujan.min' => 'Nilai curah hujan minimal 1',
            'suhu_min.between' => 'Nilai suhu udara minimum harus dalam range 0-45°C',
            'suhu_min.lte' => 'Nilai suhu udara minimum harus lebih kecil dari Suhu Maximum',
            'suhu_max.between' => 'Nilai suhu udara maximum harus dalam range 0-45°C',
            'suhu_max.gte' => 'Nilai suhu udara maximum harus lebih besar dari Suhu Minimum',
            'kelembaban_min.between' => 'Nilai Kelembaban minimum harus dalam range 0-100%',
            'kelembaban_min.lte' => 'Nilai Kelembaban minimum harus lebih kecil dari Kelembanan Maximum',
            'kelembaban_min.between' => 'Nilai Kelembaban minimum harus dalam range 0-100%',
            'kelembaban_max.gte' => 'Nilai Kelembaban maximum harus lebih besar dari Kelembanan Minimum',
            'tekanan_min.between' => 'Nilai Tekanan Udara harus dalam range 0-1000 mmHg',
            'tekanan_min.lte' => 'Nilai Tekanan Udara harus lebih kecil dari Tekanan Maximum',
            'tekanan_max.between' => 'Nilai Tekanan Udara harus dalam range 0-1000 mmHg',
            'tekanan_max.gte' => 'Nilai Tekanan Udara harus lebih besar dari Tekanan Minimum',
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

        $rules = [
            'cuaca' => 'required|array',
            'cuaca.*' => 'required|in:Cerah,Berawan,Mendung,Hujan,Badai',
            'kecepatan_angin' => 'required|array',
            'kecepatan_angin.*' => 'required|in:Lemah,Sedang,Kencang',
            'arah_angin' => 'required|array',
            'arah_angin.*' => 'required|in:Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut,Utara,Timur Laut',
            'curah_hujan' => 'required|numeric|min:0',
            'suhu_min' => 'required|numeric|between:0,45|lte:suhu_max',
            'suhu_max' => 'required|numeric|between:0,45|gte:suhu_min',
            'kelembaban_min' => 'required|numeric|between:0,100|lte:kelembaban_max',
            'kelembaban_max' => 'required|numeric|between:0,100|gte:kelembaban_min',
            'tekanan_min' => 'required|numeric|between:0,1000|lte:tekanan_max',
            'tekanan_max' => 'required|numeric|between:0,1000|gte:tekanan_min'
        ];

        return $rules;
    }
}
