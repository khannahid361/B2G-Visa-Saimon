<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class AboutusRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['description']        = ['required'];

        $this->messages['description']     = 'This Field Is Required';

        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
