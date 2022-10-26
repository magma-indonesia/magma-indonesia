<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VonaSubscriberUpdateRequest extends FormRequest
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
            'email.required' => 'Email belum diisi',
            'email.email' => 'Email tidak valid',
            'groups.required' => 'Grup notifikasi belum dipilih',
            'groups.in' => 'Group notifikasi email tidak terdaftar',
            'status.required' => 'Status notifikasi belum dipilih',
            'status.boolean' => 'Status notifikasi hanya tersedia Ya atau Tidak',
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
            'email' => 'required|email|unique:vona_subscribers,email,'. $this->route('subscriber').',id',
            'groups' => 'required|array',
            'groups.*' => 'required|in:real,exercise,pvmbg',
            'status' => 'required|boolean',
        ];
    }
}
