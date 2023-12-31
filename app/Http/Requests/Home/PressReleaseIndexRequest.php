<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PressReleaseIndexRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tag' => 'sometimes|exists:tags,slug',
            'volcano' => 'sometimes|exists:ga_dd,code',
            'category' => 'sometimes|in:gunung-api,gerakan-tanah,gempa-bumi,tsunami,lainnya',
            'value' => 'required_if:category,lainnya',
        ];
    }

    /**
     * Disable redirecttion
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException(response()->json($validator->errors(), 422));
        abort(404);
    }
}
