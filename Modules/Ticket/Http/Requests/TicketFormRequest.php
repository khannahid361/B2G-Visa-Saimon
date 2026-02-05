<?php

namespace Modules\Ticket\Http\Requests;

use App\Http\Requests\FormRequest;

class TicketFormRequest extends FormRequest
{
    protected $rules;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(request()->update_id){

            $rules['name'] = ['required'];
            $rules['phone'] = ['required'];

        }else{
            $rules['ticket_code'] = ['required'];
            $rules['name'] = ['required'];
            $rules['phone'] = ['required'];
            $rules['department_id'] = ['required'];
            $rules['receiver_id'] = ['required'];
            $rules['purpose'] = ['required'];
        }
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
