<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DppRequest extends FormRequest
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
            'dpp.name' => ['required','min:2','max:500'],
            'dpp.totalHours' => ['required','numeric','min:0']
        ];
    }
}
