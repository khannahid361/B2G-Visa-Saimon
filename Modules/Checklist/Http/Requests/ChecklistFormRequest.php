<?php

namespace Modules\Checklist\Http\Requests;

use App\Http\Requests\FormRequest;

class ChecklistFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['appliactionType_id']   = ['required'];
        $rules['visaType_id']   = ['required'];
        $rules['price']          = ['required'];
        $rules['title'] = ['required'];
        $rules['fromCountry_id']   = ['required'];
        $rules['toCountry_id']   = ['required'];
        $rules['checklist_name']   = ['required'];
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
