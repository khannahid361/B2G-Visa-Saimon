<?php

namespace App\Http\Controllers;

use App\Http\Requests\CountryFormRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    public function index(){
        if(permission('manage-country')){
            $this->setPageData('Country','Country','fas fa-bars',[['name' => 'Country']]);
            return view('country.index');
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request) {

        if($request->ajax()){
            if(permission('manage-country')){
                if (!empty($request->type)) {
                    $this->model->setName($request->type);
                }

                $this->set_datatable_default_properties($request);//set datatable default properties
                $list = $this->model->getDatatableList();//get table data

                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
                    if(permission('edit-country')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                    }
                    // if(permission('delete-country')){
                    //     $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->country_name . '">'.$this->actionButton('Delete').'</a>';
                    // }

                    $row = [];
                    $row[] = $no;
                    $row[] = $value->type == 1 ? 'From Country' : 'To Country';
                    $row[] = $value->country_name ? $value->country_name : '';
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

    public function store_or_update_data(CountryFormRequest $request) {
        if($request->ajax()){
            if(permission('add-country') || permission('edit-country')){
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

    public function edit(Request $request) {
        if($request->ajax()){
            if(permission('edit-country')){
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

    public function delete(Request $request) {
        if($request->ajax()){
            if(permission('delete-country')){
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
