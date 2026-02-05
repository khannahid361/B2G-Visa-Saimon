<?php

namespace Modules\Bank\Http\Requests;

use App\Http\Requests\FormRequest;

class AccountFormRequest extends FormRequest
{
    protected $rules;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['bank_type']      = ['required'];
        $this->rules['bank_name']      = ['required'];
        $this->rules['account_name']   = ['required'];
        return $this->rules;
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
