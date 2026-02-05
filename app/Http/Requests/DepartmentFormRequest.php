<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;

class DepartmentFormRequest extends FormRequest
{
    protected $rules;

    public function rules()
    {
        $this->rules['department_name']      = ['required', 'string', 'max:255', 'unique:departments,department_name'];
        return $this->rules;
    }

    public function authorize()
    {
        return true;
    }
}
