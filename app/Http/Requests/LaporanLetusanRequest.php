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
            'wasap.*.in' => 'Data Warna Abu invalid',
            'intensitas.*.in' => 'Data Intensitas invalid',
            'arah.*.in' => 'Arah abu invalid',
            '*.required' => 'Data harus diisi jika Visual Kolom Abu Teramati',
            '*.numeric' => 'Data harus dalam bentuk numeric',
            '*.between' => 'Nilai :attribute Minimum :min dan maksimum :max',
        ];
    }

    /**
     * $option
     *
     * @var array
     */
    protected $option = [];

    /**
     * $teramati
     *
     * @var array
     */
    protected $teramati = [
        'code' => 'required|size:3',
        'visibility' => 'required|boolean',
        'draft' => 'required|boolean',
        'date' => 'required|date_format:Y-m-d H:i|before:tomorrow',
        'height' => 'required|numeric|between:100,20000',
        'wasap' => 'required|array',
        'wasap.*' => 'in:Putih,Kelabu,Coklat,Hitam',
        'intensitas' => 'required|array',
        'intensitas.*' => 'required|in:Tipis,Sedang,Tebal',
        'arah' => 'required|array',                
        'arah.*' => 'required|in:Utara,Timur Laut,Timur,Tenggara,Selatan,Barat Daya,Barat,Barat Laut',
        'amplitudo' => 'required|numeric|between:0.5,120',
        'durasi' => 'required|numeric|between:0.5,600',
        'status' => 'required|in:1,2,3,4',
        'rekomendasi' => 'nullable',
        'lainnya' => 'nullable',
    ];

    /**
     * $tidakTeramati
     *
     * @var array
     */
    protected $tidakTeramati = [
        'code' => 'required|size:3',
        'visibility' => 'required|boolean',
        'date' => 'required|date_format:Y-m-d H:i|before:tomorrow',
        'draft' => 'required|boolean',
        'status' => 'required|in:1,2,3,4',
        'amplitudo' => 'required|numeric|between:0,120',
        'durasi' => 'required|numeric|between:0,600',
        'rekomendasi' => 'nullable',
        'lainnya' => 'nullable',
    ];

    /**
     * setOption
     *
     * @param mixed $visibility
     * @return void
     */
    public function setOption($visibility)
    {
        $option = $visibility == '1' ? $this->teramati : $this->tidakTeramati;
        $this->option = $option;
        return $this;
    }

    /**
     * getOption
     *
     * @return void
     */
    public function getOption()
    {
        return $this->option;
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
        return $this->setOption($this->request->get('visibility'))->getOption();
    }
}
