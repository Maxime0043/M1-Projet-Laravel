<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormationStoreRequest extends FormRequest
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
            'title'                 => ['required', 'string', 'max:255'],
            'description'           => ['required', 'string', 'min:10'],
            'price'                 => ['required', 'regex:/^[1-9]\d{0,2}((\.|,)\d{2})?$/'],
            'picture'               => ['required', 'image'],
            'checkboxTypes'         => ['nullable'],
            'checkboxCategories'    => ['nullable'],
        ];
    }
}
