<?php

namespace Modules\Crm\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Mail\MisingDocMail;
use App\Mail\TestEmail;
use App\Models\Country;
use App\Models\User;
use App\Models\VisaType;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Checklist\Entities\Checklist;
use Modules\Checklist\Entities\ChecklistDetailes;
use Modules\Crm\Entities\Collector;
use Modules\WalkIn\Entities\WalkinAppChecklist;
use Modules\WalkIn\Entities\WalkinAppDetails;
use Modules\WalkIn\Entities\WalkinAppDetailsChecklist;
use Modules\WalkIn\Entities\WalkinAppInfo;

class AssignCollectorController extends BaseController
{
    public function __construct(WalkinAppInfo $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        $setTitle = __('Corporate Application list');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $visa_type = VisaType::all();
        $employee = User::where('employee_type', 4)->get();
        return view('crm::list', compact('visa_type', 'employee'));
    }
    public function edit(int $id)
    {
        $setTitle = __('Visa Application');
        $setSubTitle = __('Edit Visa Application');
        $this->setPageData(
            $setSubTitle,
            $setSubTitle,
            'fas fa-edit',
            [['name' => $setTitle], ['name' => $setSubTitle]]
        );
        $visa_info = $this->model->find($id);
        $visa_cat = Checklist::where('id', $visa_info->visa_category)->first();
        $visa_checklist = ChecklistDetailes::where('checklist_id', $visa_cat->id)->get();
        $visa_app_details = WalkinAppDetails::where(
            'walkin_app_info_id',
            $visa_info->id
        )->with(
            'walkInAppCheckDetaillists',
            'checklistCat:id,title',
            'checklistCat.checklistDetailes:id,checklist_id,checklist_name'
        )->get();
        $data = [
            'visa_info' => $visa_info,
            'visa_cat' => $visa_cat,
            'visa_checklist' => $visa_checklist,
            'visa_app_details' => $visa_app_details,
            'visa_type' => VisaType::all(),
            'visa_category' => Checklist::where('id', $visa_cat->id)->get(),
            'user' => Auth::user()->id,
            'countries' => Country::all()
        ];
        return view('crm::edit', $data);
    }

    public function update_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('manage-collector')) {
                try {
                    foreach ($request->check_list_id as $value) {
                        $walkin_app_check = [
                            'walkin_app_info_id' => $request->update_id,
                            'check_list_id'      => $value,
                        ];
                        $walkInAppInfosChecklist = WalkinAppChecklist::where('walkin_app_info_id', $request->update_id)->update($walkin_app_check);
                    }
                    $output = $this->store_message($walkInAppInfosChecklist);
                } catch (Exception $e) {
                    $output = ['status' => 'error', 'message' => $e->getMessage()];
                }
            } else {
                $output = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
        return redirect()->route('index')->with($output);
    }

    //    All Applications ----------------------------
    public function get_datatable_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('manage-corporate-application')) {
                if (!empty($request->visaType_id)) {
                    $this->model->setVisa($request->visaType_id);
                }
                if (!empty($request->phone)) {
                    $this->model->setPhone($request->phone);
                }
                if (!empty($request->uniqueKey)) {
                    $this->model->setPP($request->uniqueKey);
                }
                if (!empty($request->barcode)) {
                    $this->model->setBarcode($request->barcode);
                }

                $this->set_datatable_default_properties($request); //set datatable default properties
                $list = $this->model->getDatatableAgentCompanyList();
                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    if ($value->group_price > 0) {
                        $val        = (($value->checklist->price - $value->checklist->service_charge) + $value->group_price) - $value->paid_amount;
                    } else {
                        $val        = ($value->checklist->price) - $value->paid_amount;
                    }
                    $passports = DB::table('passports')->where('passports.walkin_app_info_id', $value->id)->first();
                    $action = '';
                    if (permission('assign-collector')) {
                        if ($value->status == 3) {
                            $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">' . $this->actionButton('Assign Collector') . '</a>';
                        }
                    }

                    if (permission('corporate-app-details')) {
                        $action .= ' <a class="dropdown-item" href="' . url("walkIn/view/" . $value->id) . '">' . $this->actionButton('View') . '</a>';
                    }
                    if (permission('corporate-app-status')) {
                        $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"  data-visa_status="' . $value->status . '" data-note="' . $value->note . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                    }
                    if (permission('ready-for-delivery-corporate')) {
                        if ($value->status == 4 or $value->status == 5 or $value->status == 6) {
                            $action .= ' <a class="dropdown-item" href="' . route("walkIn.readyForDelivery", $value->id) . '">' . $this->actionButton('Ready for delivery') . '</a>';
                        }
                    }

                    if (permission('corporate-app-payment')) {
                        if ($value->status != 1 or $value->walkIn_app_type == 3) {
                            if ($value->payment_status != 2) {
                                $action .= ' <a class="dropdown-item change_payment_status"  data-id="' . $value->id . '"  data-payment_status="' . $value->payment_status . '" data-price="' . $val . '" data-due_amount="' . $value->due_amount . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-plus-square text-info mr-2"></i> Update Payment</a>';
                            }
                            if ($value->payment_status != 1 or $value->po_invoice) {
                                $action .= '<a class="dropdown-item" href="' . route("walkIn.money.receipt", $value->id) . '">' . $this->actionButton('Money Receipt') . '</a>';
                            }
                        }
                    }
                    if (permission('corporate-po-invoice-upload')) {
                        if ($value->payment_status == 1 and $value->walkIn_app_type == 3) {
                            $action .= '<a class="dropdown-item" href="' . route("walkIn.po.invoice", $value->id) . '">' . $this->actionButton('Po Invoice') . '</a>';
                        }
                    }
                    if (permission('corporate-app-generate-barcode')) {
                        if ($value->payment_status != 1 or $value->po_invoice) {
                            if (empty($value->barcode)) {
                                $action .= '<a class="dropdown-item" href="' . route(
                                    "walkIn.barcode",
                                    $value->id
                                ) . '">' . $this->actionButton('Generate Barcode') . '</a>';
                            }
                            if ($value->barcode) {
                                $action .= '<a class="dropdown-item" href="' . route(
                                    "walkIn.barcode.show",
                                    $value->id
                                ) . '">' . $this->actionButton('Barcode Show') . '</a>';
                            }
                        }
                    }
                    $row = [];
                    $row[] = $no;
                    $row[] = $value->uniqueKey;
                    $row[] = $value->created_at->format('Y-m-d');
                    $row[] = $value->name;
                    $row[] = WALKIN_APP_TYPE[$value->walkIn_app_type];
                    $row[] = '<span class="label label-info label-pill label-inline" style="width:100% !important;font-size:12px;padding: 19px 0px;text-align: center;">' . $value->visaType->visa_type . '</span>';
                    $row[] = $value->p_name;
                    $row[] = $value->email;
                    $row[] = $value->phone;
                    $row[] = $passports->passport_no ?? '';
                    $row[] = $value->collector ? '<span class="label label-success label-pill label-inline" style="height: 39px;font-weight: 700;font-size: 10px;}">' . $value->collector->user->name . '<span>' : '<span class="label label-light-danger label-pill label-inline" style="height: 39px;font-weight: 700;font-size: 10px;}">No Assign Collector</span>';
                    $row[] = $value->scheduleDate;
                    $row[] = $value->scheduleTime;
                    $row[] = $value->po_invoice ? '<span class="label label-danger label-pill label-inline" style="height: 39px;font-weight: 700;font-size: 10px;}">Uploaded P.O Invoice<span>' : '';
                    $row[] = WALKIN_APP_STATUS[$value->status];
                    $row[] = '<span>' . WALKIN_APP_PAYMENT[$value->payment_status] . '<span>' . '<br>' . '<span class="label label-success label-pill label-inline mt-1" style="min-width:70px !important;font-size:12px">' . $val . '/TK</span>';
                    $row[] = action_button($action); //custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw(
                    $request->input('draw'),
                    $this->model->count_all(),
                    $this->model->count_filtered(),
                    $data
                );
            }
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function assign(Request $request)
    {
        if ($request->ajax()) {
            $data   = $this->model->findOrFail($request->id);
            $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function assignCollector(Request $request)
    {
        $customer_info  =  $this->model->select('id', 'uniqueKey', 'user_id', 'visa_category', 'payment_status', 'paid_amount', 'p_name', 'email', 'phone', 'scheduleDate', 'scheduleTime')->where('id', $request->update_id)->first();
        $collector_info = User::select('id', 'name', 'phone', 'email')->where('id', $request->collector_id)->first();

        $data = new Collector();
        $data->wlkinAppInfo_id  = $request->update_id;
        $data->collector_id     = $request->collector_id;
        $data->sender_id        = Auth::user()->id;
        $datas =  $data->save();
        $customer_phone =  $customer_info->user->phone;
        // for send Email -----------------------------------------------------------

        $data = [
            'status_data'      => 3,
            'note'             => $request->note,
            'datas'            => $customer_info,
            'collector_info'   => $collector_info
        ];
        Mail::to($customer_info->email)->send(new MisingDocMail($data));
        //  For Sms --------------------------------------------------
        if ($collector_info->phone) {
            $url = "https://bulksmsbd.net/api/smsapi";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $collector_info->phone,
                "message" => "You have been assigned to a task contact with Supervisor"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        if ($customer_info->phone) {
            $url = "https://bulksmsbd.net/api/smsapi";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $customer_info->phone,
                "message" => "Dear Applicant, Some of your documents are missing. We are sending a collector to collect your documents. $customer_info->scheduleDate $customer_info->scheduleTime"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        $output     = $this->assign_collector_message($datas);
        return redirect()->back()->with($output);
    }

    public function statusChange(Request $request)
    {
        if ($request->ajax()) {
            $date = date('Y-m-d');
            if ($request->visa_status == 1) {
                $data = [
                    'status' => $request->visa_status,
                    'note' => $request->note,
                ];
            } elseif ($request->visa_status == 2) {
                $data = [
                    'status' => $request->visa_status,
                    'note' => $request->note,
                    'submitedonline_date' => $date,
                ];
            } elseif ($request->visa_status == 3) {
                $data = [
                    'status' => $request->visa_status,
                    'note' => $request->note,
                    'reject_date' => $date,
                ];
            } elseif ($request->visa_status == 4) {
                $data = [
                    'status' => $request->visa_status,
                    'note' => $request->note,
                    'approved_date' => $date,
                ];
            }
            $result = $this->model->find($request->status_id)->update($data);
            $output = $this->store_message($result, $request->status_id);
            return response()->json($output);
        }
    }
}
