<?php

namespace Modules\Customer\Http\Requests;

use App\Http\Requests\FormRequest;

class GroupAssignFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['customer_group_id'] = ['required'];
        $rules['customer_id']       = ['required'];
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
