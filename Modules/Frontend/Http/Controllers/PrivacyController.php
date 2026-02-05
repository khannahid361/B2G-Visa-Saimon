<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Frontend\Entities\Privacy;
use Modules\Frontend\Http\Requests\PrivacyRequestForm;

class PrivacyController extends BaseController
{
    public function __construct(Privacy $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('privacy-access')) {
            $setTitle = __('Privacy Policy');
            $this->setPageData($setTitle, $setTitle, 'fas fa-th-list', [['name' => $setTitle]]);
            return view('frontend::privacy');
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('privacy-access')) {
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                $action .= '<a class="dropdown-item edit_data" data-id="' . $value->id . '">' . $this->actionButton('Edit') . '</a>';
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->details;
                $row[]  = $value->policy_id == 1 ? 'Privacy Policy' : 'Payment & Refund policy';
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
    public function storeOrUpdate(PrivacyRequestForm $request)
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
}
