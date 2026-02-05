<?php

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\FormRequest;

class GroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['name']    = ['required'];
        $rules['price']   = ['required'];
        $rules['status']  = ['required'];
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
