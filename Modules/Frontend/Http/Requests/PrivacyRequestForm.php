<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class PrivacyRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['details']        = ['nullable'];
        $this->rules['policy_id']        = ['required'];
        
        $this->messages['details']     = 'This Field Is Required';
        $this->messages['policy_id']   = 'This Field Is Required';

        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
