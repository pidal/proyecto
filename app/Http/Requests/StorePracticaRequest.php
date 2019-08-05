<?php

namespace pfg\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePracticaRequest extends FormRequest
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
            'name' => 'required|max: 10',
            'expired_date' => 'required',
            'weight' => 'required',
            'intentos' => 'required',
            'file' => 'required'

        ];
    }
}
