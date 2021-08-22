<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCatalogRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        $this->merge([
            'postcode' => mb_strtoupper($this->events),
            'reg_no' => mb_strtoupper($this->reg_no),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|size:3',
            'seismometer_id' => 'required|exists:seismometers,id',
            'events' => 'required|array',
            'p_times' => 'required|array',
            's_times' => 'required|array',
            'zones' => 'required|array',
            'durations' => 'required|array',
            'amplitudes' => 'required|array',
            'events.*' => 'required|exists:event_types,code',
            'p_times.*' => 'nullable|date_format:Y-m-d H:i:s',
            's_times.*' => 'nullable|date_format:Y-m-d H:i:s',
            'zones.*' => 'required|in:Asia/Jakarta, Asia/Makassar, Asia/Jayapura, UTC',
            'durations.*' => 'required|numeric|min:0',
            'amplitudes.*' => 'required|numeric|between:0,240',

        ];
    }
}
