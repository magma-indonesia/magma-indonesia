<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchGunungApi extends FormRequest
{
    protected $redirectRoute = 'laporanga.index';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'gunungapi.required' => 'Pilih Gunung Api',
            'gunungapi.exists'  => 'Gunung api tidak ada di database',
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
            'gunungapi' => 'required|exists:ga_dd,code'
        ];
    }
}
