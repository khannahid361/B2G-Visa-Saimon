<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class VisaTypeFormRequest extends FormRequest
{
    protected $rules;

    public function rules()
    {
        $this->rules['visa_type'] = ['required', 'string', 'max:255', 'unique:visa_types,visa_type'];
        return $this->rules;
    }

    public function authorize()
    {
        return true;
    }
}
