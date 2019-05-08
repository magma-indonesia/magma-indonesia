<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StakeholderRequest extends FormRequest
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
            'app_name.required' => 'Nama Aplikasi wajib diisi',
            'instansi.required' => 'Nama Instansi wajib diisi',
            'nama.required' => 'Nama Pemohon wajib diisi',
            'email.required' => 'Email wajib diisi',
            'type.required' => 'Type API wajib diisi',
            'phone.numeric' => 'No Handphone harus berformat angka',
            'type.in' => 'Pilihan API tidak valid',
            'date.required' => 'Tanggal wajib diisi',
            'date' => 'Format tanggal yang digunakan yyyy-mm-dd',
            'email' => 'Format email tidak sesuai',
            'boolean' => 'Status hanya memiliki opsi Aktif atau Tidak Aktif'
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
            'app_name' => 'required',
            'instansi' => 'required',
            'nama' =>'required',
            'phone' => 'nullable|numeric',
            'email' => 'required|email',
            'type' => 'required|in:private,public,internal,internal-mga,internal-mgt,internal-mgb',
            'status' => 'required|boolean',
            'date' => 'required|date:Y-m-d'
        ];
    }
}
