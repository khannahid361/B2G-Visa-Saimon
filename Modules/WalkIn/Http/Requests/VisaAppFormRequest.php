<?php

namespace Modules\WalkIn\Http\Requests;

use App\Http\Requests\FormRequest;


class VisaAppFormRequest extends FormRequest
{
//    protected $rules = [];
    protected $rules;
    protected $messages;

    public function rules()
    {
        $this->rules['p_name']              = ['required'];
        $this->rules['passport_no']         = ['required'];
        $this->rules['phone']               = ['required'];
        $this->rules['email']               = ['required','email'];
        $this->rules['visaType_id']         = ['required'];
//        $this->rules['receiver_0']       = ['required'];
//        $this->rules['check_list_id']       = ['required'];

//        if(request()->has('visa')) {
//            foreach (request()->visa as $key => $value) {
//                $this->rules['visa'.$key.'_c_name']     = ['required'.$value['c_name']];
//                $this->rules['visaType_id_'.$key]       = ['required'.$value['c_visaType_id']];
////                $this->rules['checklistchild_'.$key]          = ['required'.$value['c_check_list_id']];
//            }
//        }
//        if(request()->has('c_visa_category')) {
//            if(empty(request()->visa)){
//                foreach (request()->visa as $key => $value) {
//                    $this->rules['checklistchild_'.$key] = ['required'.$value['c_check_list_id']];
//                }
//        }


        if(empty(request()->visa_category)){
            $this->rules['receiver_0']       = ['required'];
        }
//        if(empty(request()->visaType_id2)){
//            $this->rules['visaType_id2']       = ['required'];
//        }

        if(request()->has('c_visaType_id')) {
//            foreach (request()->c_visaType_id as $key => $value) {
                $this->rules['visaType_id_2']             = ['required'];
//            }
        }

//      if(request()->has('check_list_id'))
//          if(empty(request()->check_list_id))
//        {
//            $this->rules['checklist_1']       = ['required'];
//        }

        return $this->rules;
    }
//    public function messages()
//    {
//        return $this->messages;
//    }

    public function authorize()
    {
        return true;
    }

}
