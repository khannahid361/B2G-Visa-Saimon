<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentFormRequest;
use App\Models\Department;
use App\Models\Module;
use Illuminate\Http\Request;

class DepartmentController extends BaseController
{
    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('department-access')){
            $this->setPageData('Department','Department Manage','fas fa-bars',[['name' => 'Department']]);
            $deletable = self::DELETABLE;
            return view('department.index',compact('deletable'));
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if(permission('department-access')){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }

                $this->set_datatable_default_properties($request);//set datatable default properties
                 $list = $this->model->getDatatableList();//get table data

                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
                    if(permission('department-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                    }
//                    if(permission('department-delete')){
//                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->department_name . '">'.$this->actionButton('Delete').'</a>';
//                    }

                    $row = [];
//                    if(permission('department-bulk-delete')){
//                        $row[] = row_checkbox($value->id);
//                    }
                    $row[] = $no;
                    $row[] = $value->department_name;
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

    public function create()
    {
        if(permission('department-access')){
            $this->setPageData('Department Create','Department Create','fas fa-bars',[['name' => 'Department Create']]);
            return view('department.form');
        }else{
            return $this->access_blocked();
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('department-edit')){
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
            if(permission('department-delete')){
                $checklist  = $this->model->find($request->id);
                $result    = $checklist->delete();

                $output   = $this->delete_message($result);
            }else{
                $output   = $this->unauthorized();

            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('department-bulk-delete')){
                $result   = $this->model->destroy($request->ids);
                $output   = $this->bulk_delete_message($result);
            }else{
                $output   = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(DepartmentFormRequest $request)
    {
        if($request->ajax()){
            if(permission('department-add') || permission('department-edit')){
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

}
