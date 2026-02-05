<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Frontend\Entities\Term;
use Modules\Frontend\Http\Requests\TermRequestForm;

class TermsController extends BaseController
{
    public function __construct(Term $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('terms-access')) {
            $setTitle = __('Terms & Condition');
            $this->setPageData($setTitle, $setTitle, 'fas fa-th-list', [['name' => $setTitle]]);
            return view('frontend::Terms');
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
                if (permission('terms-access')) {
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '" data-type="' . $value->details . '">' . $this->actionButton('Edit') . '</a>';
                }

                $row    = [];
                $row[]  = $no;
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
    public function storeOrUpdate(TermRequestForm $request)
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
}
