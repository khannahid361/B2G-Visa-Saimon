<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\CustomerGroup;
use Modules\Customer\Http\Requests\GroupFormRequest;

class CustomerGroupController extends BaseController
{
    public function __construct(CustomerGroup $model)
    {
        $this->model = $model;
    }

    public function groupIndex(){
        if(permission('customer-group-access')){
            $setTitle = __('Customer Group');
            $this->setPageData($setTitle,$setTitle,'far fa-handshake',[['name'=> $setTitle]]);
            return view('customer::group.index');
        }else{
            return $this->access_blocked();
        }
    }

    public function geoupStore(GroupFormRequest $request){
        if($request->ajax()){
            if(permission('customer-group-access')){
                $collection = collect($request->all());
//                return response()->json($collection);
                $role       = CustomerGroup::updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output     = $this->store_message($role, $request->update_id);
            }else{
                $output     = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function get_group_datatable_data(Request $request)
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

                if(permission('customer-group-edit')){
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['Edit'].'</a>';
                }

                $row = [];
                $row[] = $no;
                $row[] = $value->name;
                $row[] = $value->price . ' /-Tk';
                $row[] = CUSTOMER_STATUS_VALUE[$value->status];
                $row[] = action_button($action);//custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                $this->model->count_filtered(), $data);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function groupEdit(Request $request)
    {
        if($request->ajax()){
            if(permission('customer-group-edit')){
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
