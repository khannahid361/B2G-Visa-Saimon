<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class TestimonialRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules()
    {
        $this->rules['details']        = ['required'];
        $this->rules['name']        = ['required'];
        $this->rules['image']        = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];

        $this->messages['details']     = 'This Field Is Required';
        $this->messages['name']        = 'This Field Is Required';

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
