<?php

namespace Modules\Frontend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequestForm extends FormRequest
{
        protected $rules    = [];
    protected $messages = [];
    public function rules()
    {
        $this->rules['message']        = ['required'];
        $this->rules['image']        = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];

        $this->messages['message']     = 'This Field Is Required';
        $this->messages['image']        = 'This Field Is Required';

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
