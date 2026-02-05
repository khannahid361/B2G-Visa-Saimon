<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Frontend\Entities\BranchContact;
use Modules\Frontend\Http\Requests\BranchContactFormRequest;

class BranchContactController extends BaseController
{
    public function __construct(BranchContact $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('terms-access')) {
            $setTitle = __('Branch');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            return view('frontend::branch-contact');
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('terms-access')) {
            // if (!empty($request->type)) {
            //     $this->model->setLogoType($request->type);
            // }

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
                $row[]  = $value->address;
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

    public function storeOrUpdate(BranchContactFormRequest $request)
    {
        if ($request->ajax() && permission('privacy-access')) {
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
