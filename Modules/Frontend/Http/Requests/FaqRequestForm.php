<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class FaqRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['question']        = ['required'];
        $this->rules['answer']        = ['required'];

        $this->messages['question']     = 'This Field Is Required';
        $this->messages['answer']   = 'This Field Is Required';

        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
