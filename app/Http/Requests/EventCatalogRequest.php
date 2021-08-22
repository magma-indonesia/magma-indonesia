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
        // foreach ($this->p_times as $key => $time) {
        //     $times[] = [
        //         'p_time' => $time.'.'.$this->p_milidetik[$key],
        //         's_time' => $this->s_times[$key] == null ? null : $this->s_times[$key] . '.' . $this->s_milidetik[$key],
        //     ];
        // }

        // $this->merge([
        //     'times' => $times,
        // ]);
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
            'seismometer_id' => 'required|array',
            'seismometer_id.*' => 'required|exists:seismometers,scnl',
            'events' => 'required|array',
            'p_times' => 'required|array',
            's_times' => 'required|array',
            'zones' => 'required|array',
            'durations' => 'required|array',
            'amplitudes' => 'required|array',
            'events.*' => 'required|exists:event_types,code',
            'p_times.*' => 'required|date_format:Y-m-d H:i:s.v',
            's_times.*' => 'nullable|date_format:Y-m-d H:i:s.v|after:p_times.*',
            'zones.*' => 'required|in:Asia/Jakarta, Asia/Makassar, Asia/Jayapura, UTC',
            'durations.*' => 'required|numeric|min:0',
            'amplitudes.*' => 'required|numeric|between:0,240',
        ];
    }
}
