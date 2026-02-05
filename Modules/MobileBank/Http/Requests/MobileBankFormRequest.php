<?php

namespace Modules\MobileBank\Http\Requests;

use App\Http\Requests\FormRequest;

class MobileBankFormRequest extends FormRequest
{
    protected $rules;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->rules['bank_name']      = ['required','string',];
//        $this->rules['account_name']   = ['required','string'];
//        $this->rules['account_number'] = ['required','string'];
//        $this->rules['showroom_id']     = ['required'];
        // $this->rules['warehouse_id'] = ['required'];
        if(request()->update_id){
            $this->rules['bank_name'][2] = 'bank_name,'.request()->update_id;
        }
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
