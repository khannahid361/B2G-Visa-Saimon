<?php

namespace Modules\Frontend\Http\Requests;

use App\Http\Requests\FormRequest;

class DestinationRequestForm extends FormRequest
{
    protected $rules    = [];
    protected $messages = [];
    public function rules()
    {
        $this->rules['country_id']        = ['required'];
        $this->rules['image']        = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];

        $this->messages['image']     = 'This Field Is Required';
        $this->messages['country_id']        = 'This Field Is Required';

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
