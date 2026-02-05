<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class BranchContactFormRequest extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules(){
        $this->rules['name']        = ['required'];
        $this->rules['address']        = ['required'];

        $this->messages['description']     = 'This Field Is Required';
        $this->messages['address']     = 'This Field Is Required';

        return $this->rules;
    }
    public function authorize(){
        return true;
    }
    public function messages(){
        return $this->messages;
    }
}
