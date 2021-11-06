<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserCreate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->hasRole('Super Admin');
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.string' => 'Nama harus berformat huruf',
            'nip.required' => 'NIP/NIK tidak boleh kosong',
            'nip.digits_between' => 'NIP/NIK minimal 16 dan maksimal 18 karakter numerik',
            'nip.unique' => 'NIP telah digunakan oleh orang lain',
            'password' => 'Password tidak boleh kosong',
            'password.min' => 'Password Minimal 6 karakter',
            'password.confirmed' => 'Password Konfirmasi tidak sama',
            'status.required' => 'Status harus dipilih',
            'status.boolean' => 'Tipe status tidak valid',
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
            'name' => 'required|string|max:255',
            'nip' => 'required|digits_between:16,18|unique:users,nip',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|boolean'
        ];
    }
}
