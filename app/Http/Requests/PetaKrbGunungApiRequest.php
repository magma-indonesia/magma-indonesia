<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetaKrbGunungApiRequest extends FormRequest
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
            'code.required' => 'Gunung Api belum dipilih',
            'code.exists' => 'Gunung Api tidak ditemukan',
            'krb.required' => 'File KRB tidak ditemukan',
            'krb.max' => 'File KRB harus kurang dari <80MB',
            'krb.mimes' => 'File KRB harus berformat JPG',
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
            'code' => 'required|exists:ga_dd,code',
            'tahun' => 'nullable|integer',
            'krb' => 'required|mimes:jpeg,png,jpg|max:80000',
            'keterangan' => 'nullable',
            'published' => 'required|boolean',
        ];
    }
}
