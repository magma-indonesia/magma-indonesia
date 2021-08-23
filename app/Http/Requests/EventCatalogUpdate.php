<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCatalogUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user();
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
            'seismometer_id' => 'required|exists:seismometers,scnl',
            'event' => 'required|exists:event_types,code',
            'p_time' => 'required|date_format:Y-m-d H:i:s.v',
            's_time' => 'nullable|date_format:Y-m-d H:i:s.v|after:p_time',
            'zone' => 'required|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura,UTC',
            'duration' => 'required|numeric|min:0',
            'amplitude' => 'required|numeric|between:0,240',
        ];
    }
}
