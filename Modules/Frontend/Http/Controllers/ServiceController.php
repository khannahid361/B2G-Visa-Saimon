<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Frontend\Entities\Service;
use Modules\Frontend\Http\Requests\ServiceRequestForm;

class ServiceController extends BaseController
{
     public function __construct(Service $model)
     {
          $this->model = $model;
     }
     public function index()
     {
          if (permission('terms-access')) {
               $setTitle = __('Service');
               $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
               return view('frontend::service');
          } else {
               return $this->access_blocked();
          }
     }

     public function getDataTableData(Request $request)
     {
          if ($request->ajax() && permission('terms-access')) {
               $this->set_datatable_default_properties($request);
               $list = $this->model->getDatatableList();
               $data = [];
               $no   = $request->input('start');
               foreach ($list as $value) {
                    $no++;
                    $action = '';
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">' . $this->actionButton('Edit') . '</a>';

                    $action .= ' <a class="dropdown-item delete-data" data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';

                    $row    = [];
                    $row[]  = $no;
                    $row[]  = $value->name;
                    $row[]  = $value->details;
                    $row[]  = $value->created_by;
                    $row[]  = $value->modified_by != null ? $value->modified_by : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                    $row[]  = action_button($action);
                    $data[] = $row;
               }
               return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
          } else {
               return response()->json($this->unauthorized());
          }
     }

     public function storeOrUpdate(ServiceRequestForm $request)
     {
          if ($request->ajax() && permission('terms-access')) {
               $collection   = collect($request->validated());
               $collection   = $this->track_data($collection, $request->update_id);
               $result       = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
               $output       = $this->store_message($result, $request->update_id);
               return response()->json($output);
          } else {
               return response()->json($this->unauthorized());
          }
     }
     public function edit(Request $request)
     {
          if ($request->ajax()) {
               $data   = $this->model->findOrFail($request->id);
               $output = $this->data_message($data);
               return response()->json($output);
          } else {
               return response()->json($this->unauthorized());
          }
     }
     public function delete(Request $request)
     {
          if ($request->ajax()) {
               $service = $this->model->find($request->id);
               if ($service) {
                    $result = $service->delete();
                    $output = $this->delete_message($result);
                    return response()->json($output);
               }
               return response()->json(['status' => 'error', 'message' => 'Record not found']);
          } else {
               return response()->json($this->unauthorized());
          }
     }
}
