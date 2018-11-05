<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchAbsensiRequest extends FormRequest
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
            'start.date_format' => 'Tanggal awal harus memiliki format YYYY-MM-DD',
            'end.date_format' => 'Tanggal akhir harus memiliki format YYYY-MM-DD',
            'start.before_or_equal' => 'Tanggal akhir harus harus lebih dari tanggal awal'
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
            'start' => 'date_format:Y-m-d|before_or_equal:end',
            'end' => 'date_format:Y-m-d'
        ];
    }
}
