<?php

namespace Modules\WalkIn\Http\Requests;

use App\Http\Requests\FormRequest;


class VisaPaymentFormRequest extends FormRequest
{
    protected $rules;
    protected $messages;

    public function rules()
    {
        $this->rules['account_type']        = ['required'];
        $this->rules['account_id']          = ['required'];
        $this->rules['visa_payment_status'] = ['required'];
        $this->rules['service_charge']      = ['required'];
        $this->rules['visa_fee']            = ['required'];

        return $this->rules;
    }


    public function authorize()
    {
        return true;
    }
}
