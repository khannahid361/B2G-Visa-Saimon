<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\CustomerAssignGroup;
use Modules\Customer\Entities\CustomerGroup;
use Modules\Customer\Http\Requests\GroupAssignFormRequest;

class CustomerAssignGroupController extends BaseController
{
    public function __construct(CustomerAssignGroup $model)
    {
        $this->model = $model;
    }

    public function groupAssignIndex(){
        if(permission('customer-group-assign-access')){
            $setTitle = __('Customer Group Assign');
            $this->setPageData($setTitle,$setTitle,'far fa-handshake',[['name'=> $setTitle]]);
            $customer = User::where('role_id',3)->where('application_type',3)->get();
            $group   = CustomerGroup::where('status',1)->get();
            return view('customer::assign.index',compact('customer','group'));
        }else{
            return $this->access_blocked();
        }
    }

    public function geoupAssignStore(GroupAssignFormRequest $request){
        if($request->ajax()){
            if(permission('customer-group-assign-access')){
                $group_price = CustomerGroup::where('id',$request->customer_group_id)->first();
                $data = [
                    'customer_group_id' =>$request->customer_group_id,
                    'group_price'       =>$group_price->price
                ];
                $role       = User::where('id',$request->customer_id)->update($data);
                $output     = $this->store_message($role, $request->update_id);
            }else{
                $output     = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function get_group_assign_datatable_data(Request $request)
    {
        if($request->ajax()){

            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }

            $this->set_datatable_default_properties($request);//set datatable default properties
            $list = $this->model->getDatatableList();//get table data
//            return Response()->json($list);

            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';

                if(permission('customer-group-assign-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['Edit'].'</a>';
                }

                $row = [];
                $row[] = $no;
                $row[] = $value->customerGroup ? $value->customerGroup->name : '' ;
                $row[] = $value->customerGroup ? $value->customerGroup->price .' /-Tk' : '' ;
                $row[] = $value->name;
                $row[] = $value->phone;
                $row[] = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function groupAssignEdit(Request $request)
    {
        if($request->ajax()){
            if(permission('customer-group-assign-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

}
