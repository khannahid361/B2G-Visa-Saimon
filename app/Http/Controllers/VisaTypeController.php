<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisaTypeFormRequest;
use App\Models\VisaType;
use Illuminate\Http\Request;

class VisaTypeController extends BaseController
{
    public function __construct(VisaType $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('visatype-access')){
            $this->setPageData('Destination','Destination','fas fa-bars',[['name' => 'Visa Type']]);
            $deletable = self::DELETABLE;
            return view('visaType.index',compact('deletable'));
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {

        if($request->ajax()){
            if(permission('visatype-access')){
//                if (!empty($request->name)) {
//                    $this->model->setName($request->name);
//                }

                $this->set_datatable_default_properties($request);//set datatable default properties
                $list = $this->model->getDatatableList();//get table data

                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
                    if(permission('visa-type-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                    }
//                    if(permission('visa-type-delete')){
//                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->visa_type . '">'.$this->actionButton('Delete').'</a>';
//                    }

                    $row = [];
                    $row[] = $no;
                    $row[] = $value->visa_type;
                    $row[] = action_button($action);//custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                    $this->model->count_filtered(), $data);
            }
        }else{
            return response()->json($this->unauthorized());
        }
    }


    public function store_or_update_data(VisaTypeFormRequest $request)
    {
        if($request->ajax()){
            if(permission('visa-type-add') || permission('visa-type-edit')){
                $collection = collect($request->all());
                $role       = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output     = $this->store_message($role, $request->update_id);
            }else{
                $output     = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }

    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('visa-type-edit')){
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

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('visa-type-delete')){
                $result   = $this->model->find($request->id)->delete();
                $output   = $this->delete_message($result);
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

}
