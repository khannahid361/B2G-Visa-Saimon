<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Frontend\Entities\Contact;

class ContactusController extends BaseController
{
   public function __construct(Contact $model)
   {
      $this->model = $model;
   }
   public function index()
   {
      if (permission('privacy-access')) {
         $setTitle = __('Contact Us');
         $this->setPageData($setTitle, $setTitle, 'fas fa-th-list', [['name' => $setTitle]]);
         return view('frontend::contactus');
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
            $action .= '<a class="dropdown-item view-data" data-id="' . $value->id . '">' . $this->actionButton('View') . '</a>';

            $action .= ' <a class="dropdown-item delete-data" data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
            $row    = [];
            $row[]  = $no;
            $row[]  = $value->name;
            $row[]  = $value->phone;
            $row[]  = $value->email;
            $row[]  = $value->subject;
            $row[]  = $value->message;
            $row[]  = action_button($action);
            $data[] = $row;
         }
         return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
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

   public function view(Request $request)
   {
      if ($request->ajax()) {
         $data = $this->model->find($request->id);

         if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Data not found!']);
         }

         return response()->json([
            'status' => 'success',
            'id' => $data->id,
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'subject' => $data->subject,
            'message' => $data->message,
         ]);
      } else {
         return response()->json($this->unauthorized());
      }
   }
}
