<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class TermRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['details']        = ['nullable'];

        $this->messages['details']     = 'This Field Is Required';

        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
