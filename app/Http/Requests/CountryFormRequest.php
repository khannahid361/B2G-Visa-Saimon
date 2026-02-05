<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class CountryFormRequest extends FormRequest
{
    protected $rules;

    public function rules()
    {
        $this->rules['type']      = ['required'];
        $this->rules['country_name']      = ['required', 'string', 'max:255', 'unique:countries,country_name'];
        return $this->rules;
    }

    public function authorize()
    {
        return true;
    }
}
