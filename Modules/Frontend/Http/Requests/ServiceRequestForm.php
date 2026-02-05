<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class ServiceRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules()
    {
        $this->rules['details']        = ['nullable'];
        $this->rules['name']        = ['required'];

        $this->messages['details']     = 'This Field Is Required';
        $this->messages['name']     = 'This Field Is Required';

        return $this->rules;
    }
    public function authorize()
    {
        return true;
    }
    public function messages()
    {
        return $this->messages;
    }
}
