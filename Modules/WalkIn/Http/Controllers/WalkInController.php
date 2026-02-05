<?php

namespace Modules\WalkIn\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Mail\DeliveredMail;
use App\Mail\DeliveryMail;
use App\Mail\HoldMail;
use App\Mail\StatusMail;
use App\Mail\TestEmail;
use App\Mail\WalkinMail;
use App\Models\Country;
use App\Models\User;
use App\Models\VisaType;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Bank\Entities\Bank;
use Modules\Bank\Entities\ManageBank;
use Modules\CashInHand\Entities\CashInHand;
use Modules\Checklist\Entities\Checklist;
use Modules\Checklist\Entities\ChecklistDetailes;
use Modules\MobileBank\Entities\MobileBank;
use Modules\WalkIn\Entities\Payment;
use Modules\WalkIn\Entities\PoInvoice;
use Modules\WalkIn\Entities\Passport;
use Modules\WalkIn\Entities\StatusLog;
use Modules\WalkIn\Entities\WalkinApp;
use Modules\WalkIn\Entities\WalkinAppChecklist;
use Modules\WalkIn\Entities\WalkinAppDetails;
use Modules\WalkIn\Entities\WalkinAppDetailsChecklist;
use Modules\WalkIn\Entities\WalkinAppInfo;
use Modules\WalkIn\Http\Requests\VisaAppFormRequest;
use Modules\WalkIn\Http\Requests\VisaPaymentFormRequest;
use Pusher\Pusher;

class WalkInController extends BaseController
{
    public function __construct(WalkinAppInfo $model)
    {
        $this->model = $model;
    }

    public function agentCorporeteCustomer($id)
    {
        $data = [];
        $cat = User::where('application_type', '!=', 1)->where('application_type', $id)->latest()->get();
        foreach ($cat as $row) {
            $data[] = [
                'visaType_id' => $row->id,
                'title' => $row->name,
            ];
        }
        return response()->json($data);
    }

    public function VisaTypeWiseCat($id)
    {
        $data = [];
        $cat = Checklist::where('status', 1)->where('visaType_id', $id)->get();
        foreach ($cat as $row) {
            $data[] = [
                'visaType_id' => $row->id,
                'title' => $row->title,
                'price' => $row->price,
                'others_price' => $row->others_price,
            ];
        }
        return response()->json($data);
    }

    public function catWiseChecklist($id)
    {
        $data = [];
        $cat = ChecklistDetailes::where('checklist_id', $id)->get();
        foreach ($cat as $row) {
            $data[] = [
                'visaType_id' => $row->id,
                'title' => $row->checklist_name,
            ];
        }
        return response()->json($data);
    }

    public function catWiseChecklistChild($id)
    {
        $data = [];
        $cat = ChecklistDetailes::where('checklist_id', $id)->get();
        foreach ($cat as $row) {
            $data[] = [
                'visaType_id' => $row->id,
                'title' => $row->checklist_name,
            ];
        }
        return response()->json($data);
    }



    public function index(Request $request)
    {
        if (permission('walk-application-add')) {
            $setTitle = __('Walk In application');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            $visa_type = VisaType::all();
            $user = Auth::user()->id;
            $countries = Country::all();
            $customers = User::where('application_type', '!=', 1)->get();
            return view('walkin::index', compact('visa_type', 'user', 'countries', 'customers'));
        } else {
            return $this->access_blocked();
        }
    }

    //all application
    public function allApps()
    {
        if (permission('manage-all-application')) {
            $setTitle = __('All application List');
            $visa_type = VisaType::all();
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            return view('walkin::list', compact('visa_type'));
        } else {
            return $this->access_blocked();
        }
    }

    public function walkInApps()
    {
        if (permission('manage-walk-application')) {
            $setTitle = __('Walk In application List');
            $visa_type = VisaType::all();
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            return view('walkin::walkin_list', compact('visa_type'));
        } else {
            return $this->access_blocked();
        }
    }

    public function onlineApps()
    {
        if (permission('manage-online-application')) {
            $setTitle = __('Online application List');
            $visa_type = VisaType::all();
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            return view('walkin::online_list', compact('visa_type'));
        } else {
            return $this->access_blocked();
        }
    }

    //    All Applications datatable----------------------------
    public function get_datatable_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('manage-all-application')) {
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
                if (!empty($request->passport_no)) {
                    $this->model->setPassport($request->passport_no);
                }
                $this->set_datatable_default_properties($request); //set datatable default properties
                $list = $this->model->getDatatableList();

                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    if ($value->group_price > 0) {
                        $val        = ((($value->checklist->price - $value->checklist->service_charge) + $value->group_price) - $value->paid_amount) - $value->discount;
                    } else {
                        $val = (($value->checklist->price ? $value->checklist->price : '') - $value->paid_amount) - $value->discount;
                    }
                    $passportLabels = '<div class="test-width text-nowrap">';
                    $passports = DB::table('passports')->where('passports.walkin_app_info_id', $value->id)->get();
                    foreach ($passports as $index => $passport) {
                        $passportLabels .= '<span class="label label-success label-pill label-inline rounded" style="min-width:70px !important;margin:2px 0px">' . $passport->passport_no . '</span>' . "<br>";
                        $passportLabels .= ' ';
                    }
                    $passportLabels .= '</div>';

                    $balanceLabels = '<div class="test-width text-nowrap">';
                    $balances = Payment::where('walkin_app_info_id', $value->id)->with('accounts')->get();
                    foreach ($balances as $index => $balance) {
                        $bankName = isset($balance->accounts) ? $balance->accounts->bank_name : 'Unknown Bank';
                        $balanceLabels .= '<span class="label label-success label-pill label-inline rounded" style="min-width:70px !important;margin:2px 0px">' . $bankName . '(' . $balance->paid_amount . ' ) ' .  '</span>' . "<br>";
                        $balanceLabels .= ' ';
                    }
                    $balanceLabels .= '</div>';


                    $action = '';
                    if (permission('edit-all-application')) {
                        if ($value->app_status != 3 and $value->status == 1) {
                            $action .= ' <a class="dropdown-item" href="' . route("walkIn.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                        }
                    }
                    if (permission('all-app-details')) {
                        $action .= ' <a class="dropdown-item" href="' . url("walkIn/view/" . $value->id) . '">' . $this->actionButton('View') . '</a>';
                    }
                    if (permission('delete-all-application')) {
                        if ($value->status == 1) {
                            $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                        }
                    }
                    if (permission('all-app-status')) {
                        $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"  data-visa_status="' . $value->status . '" data-note="' . $value->note . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                    }
                    if (permission('ready-for-delivery-all')) {
                        if ($value->status == 4 or $value->status == 5 or $value->status == 6) {
                            $action .= ' <a class="dropdown-item" href="' . route("walkIn.readyForDelivery", $value->id) . '">' . $this->actionButton('Ready for delivery') . '</a>';
                        }
                    }
                    if (permission('all-application-payment')) {
                        if ($value->status != 1 or $value->walkIn_app_type == 3) {
                            $visaFee = $value->checklist->price - $value->checklist->service_charge;
                            if ($value->payment_status != 2) {
                                $action .= ' <a class="dropdown-item change_payment_status"  data-id="' . $value->id . '"  data-payment_status="' . $value->payment_status . '" data-price="' . $val . '" data-due_amount="' . $value->due_amount . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '" data-service_charge="' . $value->checklist->service_charge . '" data-visa_fee="' . $visaFee . '"><i class="fas fa-plus-square text-info mr-2"></i> Update Payment</a>';
                            }
                            if ($value->payment_status != 1 or $value->po_invoice) {
                                $action .= '<a class="dropdown-item" href="' . route("walkIn.money.receipt", $value->id) . '">' . $this->actionButton('Money Receipt') . '</a>';
                            }
                        }
                    }
                    if (permission('po-invoice-upload')) {
                        if ($value->payment_status == 1 and $value->walkIn_app_type == 3) {
                            $action .= '<a class="dropdown-item" href="' . route("walkIn.po.invoice", $value->id) . '">' . $this->actionButton('Po Invoice') . '</a>';
                        }
                    }
                    if (permission('all-app-generate-barcode')) {
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
                    $row[] = $value->name ? $value->name : '';
                    $row[] = APPLICATION_STATUS[$value->app_status];
                    $row[] = WALKIN_APP_TYPE[$value->walkIn_app_type];
                    $row[] = '<span class="label label-info label-pill label-inline" style="width:100% !important;font-size:12px;padding: 19px 0px;text-align: center;">' . $value->visaType ? $value->visaType->visa_type : "." . '</span>';
                    $row[] = $value->p_name;
                    $row[] = $value->email;
                    $row[] = $value->phone;
                    $row[] = $passportLabels;

                    //                    $row[] = $passports->passport_no ?? '';
                    $row[] = $value->po_invoice ? '<span class="label label-danger label-pill label-inline" style="height: 39px;font-weight: 700;font-size: 10px;}">Uploaded P.O Invoice<span>' : '';
                    $row[] = WALKIN_APP_STATUS[$value->status];
                    $row[] = '<span>' . WALKIN_APP_PAYMENT[$value->payment_status] . '<span>' . '<br>' . '<span class="label label-success label-pill label-inline mt-1" style="min-width:70px !important;font-size:12px">' . $val . '/TK</span>';
                    $row[] = $balanceLabels ?? 0;
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

    //    WalkIn Applications ------------------------
    public function get_walkin_datatable_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('manage-walk-application')) {
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
                $list = $this->model->getDatatableWalkInList();
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    if ($value->group_price > 0) {
                        $val        = ((($value->checklist->price - $value->checklist->service_charge) + $value->group_price) - $value->paid_amount) - $value->discount;
                    } else {
                        $val = (($value->checklist->price) - $value->paid_amount) - $value->discount;
                    }
                    $passports = DB::table('passports')->where('passports.walkin_app_info_id', $value->id)->first();
                    $action = '';
                    if (permission('edit-walk-application')) {
                        $action .= ' <a class="dropdown-item" href="' . route(
                            "walkIn.edit",
                            $value->id
                        ) . '">' . $this->actionButton('Edit') . '</a>';
                    }
                    if (permission('walkin-app-details')) {
                        $action .= ' <a class="dropdown-item" href="' . url("walkIn/view/" . $value->id) . '">' . $this->actionButton('View') . '</a>';
                    }
                    if (permission('delete-walk-application')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                    }
                    if (permission('walk-in-app-status')) {
                        $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"  data-visa_status="' . $value->status . '" data-note="' . $value->note . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                    }
                    if (permission('ready-for-delivery-walkin')) {
                        if ($value->status == 4 or $value->status == 5 or $value->status == 6) {
                            $action .= ' <a class="dropdown-item" href="' . route("walkIn.readyForDelivery", $value->id) . '">' . $this->actionButton('Ready for delivery') . '</a>';
                        }
                    }

                    if (permission('walkin-app-payment')) {
                        if ($value->status != 1 or $value->walkIn_app_type == 3) {
                            if ($value->payment_status != 2) {
                                $action .= ' <a class="dropdown-item change_payment_status"  data-id="' . $value->id . '"  data-payment_status="' . $value->payment_status . '" data-price="' . $val . '" data-due_amount="' . $value->due_amount . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-plus-square text-info mr-2"></i> Update Payment</a>';
                            }
                            if ($value->payment_status != 1) {
                                $action .= '<a class="dropdown-item" href="' . route("walkIn.money.receipt", $value->id) . '">' . $this->actionButton('Money Receipt') . '</a>';
                            }
                        }
                    }
                    if (permission('walkin-app-generate-barcode')) {
                        if ($value->payment_status != 1) {
                            $action .= '<a class="dropdown-item" href="' . route(
                                "walkIn.barcode",
                                $value->id
                            ) . '">' . $this->actionButton('Generate Barcode') . '</a>';
                            $action .= '<a class="dropdown-item" href="' . route(
                                "walkIn.barcode.show",
                                $value->id
                            ) . '">' . $this->actionButton('Barcode Show') . '</a>';
                        }
                    }
                    $row = [];
                    $row[] = $no;
                    $row[] = $value->uniqueKey;
                    $row[] = $value->created_at->format('Y-m-d');
                    $row[] = APPLICATION_STATUS[$value->app_status];
                    $row[] = WALKIN_APP_TYPE[$value->walkIn_app_type];
                    $row[] = '<span class="label label-info label-pill label-inline" style="width:100% !important;font-size:12px;padding: 19px 0px;text-align: center;">' . $value->visaType ? $value->visaType->visa_type : "." . '</span>';
                    $row[] = $value->p_name;
                    $row[] = $value->email;
                    $row[] = $value->phone;
                    $row[] = $passports->passport_no ?? '';
                    $row[] = WALKIN_APP_STATUS[$value->status];
                    $row[] = '<span>' . WALKIN_APP_PAYMENT[$value->payment_status] . '<span>' . '<br>' . '<span class="label label-success label-pill label-inline mt-1" style="min-width:70px !important;font-size:12px">' . $val . '/TK</span>';
                    $row[] = action_button($action); //custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw(
                    $request->input('draw'),
                    $this->model->count_all(),
                    $this->model->count_walkin_filtered(),
                    $data
                );
            }
        } else {
            return response()->json($this->unauthorized());
        }
    }

    //   Online Applications ------------------------
    public function get_online_datatable_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('manage-online-application')) {
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
                $list = $this->model->getDatatableOnlineList();
                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    if ($value->group_price > 0) {
                        $val        = ((($value->checklist->price - $value->checklist->service_charge) + $value->group_price) - $value->paid_amount) - $value->discount;
                    } else {
                        $val = (($value->checklist->price) - $value->paid_amount) - $value->discount;
                    }
                    $passports = DB::table('passports')->where('passports.walkin_app_info_id', $value->id)->first();
                    $action = '';

                    if (permission('edit-online-application')) {
                        if ($value->app_status != 3) {
                            $action .= ' <a class="dropdown-item" href="' . route("walkIn.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                        }
                    }
                    if (permission('online-app-details')) {
                        $action .= ' <a class="dropdown-item" href="' . url("walkIn/view/" . $value->id) . '">' . $this->actionButton('View') . '</a>';
                    }
                    if (permission('delete-online-application')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                    }
                    if (permission('online-app-status')) {
                        $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"  data-visa_status="' . $value->status . '" data-note="' . $value->note . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                    }
                    if (permission('ready-for-delivery-online')) {
                        if ($value->status == 4 or $value->status == 5 or $value->status == 6) {
                            $action .= ' <a class="dropdown-item" href="' . route("walkIn.readyForDelivery", $value->id) . '">' . $this->actionButton('Ready for delivery') . '</a>';
                        }
                    }

                    if (permission('online-app-payment')) {
                        if ($value->status != 1 or $value->walkIn_app_type == 3) {
                            if ($value->payment_status != 2) {
                                $action .= ' <a class="dropdown-item change_payment_status"  data-id="' . $value->id . '"  data-payment_status="' . $value->payment_status . '" data-price="' . $val . '" data-due_amount="' . $value->due_amount . '" data-phone="' . $value->phone . '" " data-email="' . $value->email . '"><i class="fas fa-plus-square text-info mr-2"></i> Update Payment</a>';
                            }
                            if ($value->payment_status != 1 or $value->po_invoice) {
                                $action .= '<a class="dropdown-item" href="' . route("walkIn.money.receipt", $value->id) . '">' . $this->actionButton('Money Receipt') . '</a>';
                            }
                        }
                    }
                    if (permission('po-invoice-upload-for-online')) {
                        if ($value->payment_status == 1 and $value->walkIn_app_type == 3) {
                            $action .= '<a class="dropdown-item" href="' . route("walkIn.po.invoice", $value->id) . '">' . $this->actionButton('Po Invoice') . '</a>';
                        }
                    }
                    if (permission('online-app-generate-barcode')) {
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
                    $row[] = APPLICATION_STATUS[$value->app_status];
                    $row[] = WALKIN_APP_TYPE[$value->walkIn_app_type];
                    $row[] = '<span class="label label-info label-pill label-inline" style="width:100% !important;font-size:12px;padding: 19px 0px;text-align: center;">' . $value->visaType ? $value->visaType->visa_type : "." . '</span>';
                    $row[] = $value->p_name;
                    $row[] = $value->email;
                    $row[] = $value->phone;
                    $row[] = $passports->passport_no ?? '';
                    $row[] = $value->po_invoice ? '<span class="label label-danger label-pill label-inline" style="height: 39px;font-weight: 700;font-size: 10px;}">Uploaded P.O Invoice<span>' : '';
                    $row[] = WALKIN_APP_STATUS[$value->status];
                    $row[] = '<span>' . WALKIN_APP_PAYMENT[$value->payment_status] . '<span>' . '<br>' . '<span class="label label-success label-pill label-inline mt-1" style="min-width:70px !important;font-size:12px">' . $val . '/TK</span>';
                    $row[] = action_button($action); //custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw(
                    $request->input('draw'),
                    $this->model->count_all(),
                    $this->model->count_online_filtered(),
                    $data
                );
            }
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(VisaAppFormRequest $request)
    {
        //          return $request->all();
        if ($request->ajax()) {
            if (permission('walk-application-add')) {
                try {

                    date_default_timezone_set('Asia/Dhaka');
                    $date           = date('Y-m-d');
                    $time           = date('h:i:s A');
                    $uniqueKey      = rand(100000, 999999);
                    $customer_data  = User::where('id', $request->agent_name)->first();
                    $user           = User::where('email', $request->email)->where('phone', $request->phone)->where('application_type', $request->walkIn_app_type)->first();
                    if (!empty($user)) {
                        $customer_id = $user->id;
                    } else {
                        // Customer Account Creation---------------------------------------------
                        $customer_id = User::create([
                            'name'              => $request->p_name,
                            'email'             => $request->email,
                            'password'          => $request->phone,
                            'phone'             => $request->phone,
                            'application_type'  => $request->walkIn_app_type,
                            'gender'            => 1,
                            'status'            => 1,
                            'type'              => 1,
                            'deletable'         => 1,
                            'role_id'           => 3,
                        ]);
                    }

                    if ($request->walkIn_app_type == 3) {
                        $collection = collect($request->all())->merge([
                            'date' => $date,
                            'uniqueKey' => $uniqueKey,
                            'name' => $customer_data->name ?? '',
                            'information' => $customer_data->information ?? '',
                            'customer_id' => $customer_id,
                            'group_price' => $customer_data->group_price ?? 0,
                            'customer_group_id' => $customer_data->customer_group_id ?? ''
                        ]);
                    } else {
                        $collection = collect($request->all())->merge([
                            'date' => $date,
                            'uniqueKey' => $uniqueKey,
                            'name' => $request->name ?? '',
                            'information' => $request->information ?? '',
                            'customer_id' => $customer_id,
                            'group_price' => $customer_data->group_price ?? 0,
                            'customer_group_id' => $customer_data->customer_group_id ?? ''
                        ]);
                    }
                    $walkInApp  = $this->model->create($collection->all());
                    $id         = $walkInApp->id;
                    foreach ($request->check_list_id as $value) {
                        $walkin_app_check = [
                            'walkin_app_info_id'    => $id,
                            'check_list_id'         => $value,
                        ];
                        $walkInAppInfosChecklist = WalkinAppChecklist::insert($walkin_app_check);
                    }
                    $passports = [];
                    foreach ($request->passport_no as $value) {
                        $passports[]    = [
                            'walkin_app_info_id'    =>  $id,
                            'passport_no'           =>  $value,
                        ];
                    }
                    $passport   = Passport::insert($passports);
                    $output     = $this->store_message($walkInApp);
                    $collect    = collect($walkInApp)->merge(['walkin_app_info_id' => $walkInApp->id, 'application_status' => 1, 'status_date' => $date, 'status_time' => $time, 'changer_id' => Auth::user()->id]);
                    $status     = StatusLog::create($collect->all());

                    // for send Email -----------------------------------------------------------
                    $data = [
                        'c_name'    => $request->p_name,
                        'app_id'     => $walkInApp->uniqueKey,
                    ];
                    Mail::to($request->email)->send(new WalkinMail($data));
                    // for sms ------------------------------------------------------------------
                    $visa_type = VisaType::where('id', $request->visaType_id)->first();
                    $url = "https://bulksmsbd.net/api/smsapi";
                    $data = [
                        "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                        "senderid" => "SaimonGroup",
                        "number" => $request->phone,
                        "message" => "Your $visa_type->visa_type File Received, Your Application Id: $walkInApp->uniqueKey"
                    ];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    curl_close($ch);
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

        return redirect()->back()->with($output);
    }


    //     public function store_or_update_data(VisaAppFormRequest $request) {
    //         if ($request->ajax()) {
    //             if (permission('walk-application-add')) {
    //                 try {
    //                     date_default_timezone_set('Asia/Dhaka');
    //                     $date       = date('Y-m-d');
    //                     $time       = date('h:i:s A');
    //                     $uniqueKey  = rand(100000,999999);
    //                     $collection = collect($request->all())->merge(['date' => $date,'uniqueKey' => $uniqueKey]);

    //                     $walkInApp  = $this->model->create($collection->all());
    //                     $id         = $walkInApp->id;
    //                     foreach ($request->check_list_id as $value){
    //                         $walkin_app_check = [
    //                             'walkin_app_info_id'    => $id,
    //                             'check_list_id'         => $value,
    //                         ];
    //                         $walkInAppInfosChecklist = WalkinAppChecklist::insert($walkin_app_check);
    //                     }
    //                     $passports = [];
    //                     foreach ($request->passport_no as $value){
    //                         $passports[]    = [
    //                             'walkin_app_info_id'    =>  $id,
    //                             'passport_no'           =>  $value,
    //                         ];
    //                     }
    //                     $passport   = Passport::insert($passports);
    //                     $output     = $this->store_message($walkInApp);
    //                     $collect    = collect($walkInApp)->merge(['walkin_app_info_id' => $walkInApp->id,'application_status'=>1,'status_date'=>$date,'status_time'=>$time,'changer_id'=>Auth::user()->id]);
    //                     $status     = StatusLog::create($collect->all());
    // // Customer Account Creation---------------------------------------------
    //                     $user = User::create([
    //                         'name'              => $request->p_name,
    //                         'email'             => $request->email,
    //                         'password'          => $request->phone,
    //                         'phone'             => $request->phone,
    //                         'application_type'  => $request->walkIn_app_type,
    //                         'gender'            => 1,
    //                         'status'            => 1,
    //                         'type'              => 1,
    //                         'deletable'         => 1,
    //                         'role_id'           => 3,
    //                     ]);
    // // for send Email -----------------------------------------------------------
    //                     $data = [
    //                         'c_name'    => $request->p_name,
    //                         'app_id'     => $walkInApp->uniqueKey,
    //                     ];
    //                     Mail::to($request->email)->send(new WalkinMail($data));
    // // for sms ------------------------------------------------------------------
    //                     $url = "https://sms.brainwavebd.com/api/sms/send";
    //                     $data = [
    //                         "apiKey"=> "A000050ebd75369-fec2-4d67-86ce-dc3f3d7e101f",
    //                         "contactNumbers" => $request->phone,
    //                         "senderId" => "8809612441286",
    //                         "textBody" => "Your Application has Successfully Submitted !. Application Id: $walkInApp->uniqueKey"
    //                     ];
    //                     $ch = curl_init();
    //                     curl_setopt($ch, CURLOPT_URL, $url);
    //                     curl_setopt($ch, CURLOPT_POST, 1);
    //                     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //                     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //                     $response = curl_exec($ch);
    //                 } catch (Exception $e) {
    //                     $output = ['status' => 'error', 'message' => $e->getMessage()];
    //                 }
    //             } else {
    //                 $output = $this->unauthorized();
    //             }
    //             return response()->json($output);
    //         } else {
    //             return response()->json($this->unauthorized());
    //         }

    //         return redirect()->back()->with($output);

    //     }

    public function hold_or_update_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('walk-application-add')) {
                try {
                    date_default_timezone_set('Asia/Dhaka');
                    $date       = date('Y-m-d');
                    $time       = date('h:i:s A');
                    $uniqueKey  = rand(100000, 999999);
                    $collection = collect($request->all())->merge(['app_status' => 2, 'date' => $date, 'uniqueKey' => $uniqueKey]);
                    $walkInApp  = $this->model->create($collection->all());
                    $id = $walkInApp->id;
                    foreach ($request->check_list_id as $value) {
                        $walkin_app_check = [
                            'walkin_app_info_id' => $id,
                            'check_list_id'      => $value,
                        ];
                        $walkInAppInfosChecklist = WalkinAppChecklist::insert($walkin_app_check);
                    }
                    $payments = [];
                    foreach ($request->passport_no as $value) {
                        $payments[]    = [
                            'walkin_app_info_id'    =>  $id,
                            'passport_no'           =>  $value,
                        ];
                    }
                    $payment    = Passport::insert($payments);
                    $output     = $this->store_message($walkInApp);
                    $collect    = collect($walkInApp)->merge(['walkin_app_info_id' => $walkInApp->id, 'application_status' => 2, 'status_date' => $date, 'status_time' => $time, 'changer_id' => Auth::user()->id]);
                    $status     = StatusLog::create($collect->all());
                    // for send Email -----------------------------------------------------------
                    $data = [
                        'c_name'    => $request->p_name,
                        'app_id'     => $walkInApp->uniqueKey,
                    ];
                    Mail::to($request->email)->send(new HoldMail($data));
                    // for sms ------------------------------------------------------------------
                    $url = "https://bulksmsbd.net/api/smsapi";
                    $data = [
                        "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                        "senderid" => "SaimonGroup",
                        "number" => $request->phone,
                        "message" => "$request->p_name, Your Application is on Hold . Please Provide Missing Document !."
                    ];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    curl_close($ch);
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
        return redirect()->back()->with($output);
    }

    public function show($id)
    {
        $setTitle = __('Visa Application Details');
        $this->setPageData($setTitle, $setTitle, 'far fa-file-alt', [['name' => $setTitle]]);
        $data = [
            'info' => $this->model->with(
                'visaType:id,visa_type',
                'checklist:id,title,price,fromCountry_id,toCountry_id',
                'checklist.formCountry:id,country_name',
                'checklist.toCountry:id,country_name',
                'walkInAppChecklists:id,walkin_app_info_id,check_list_id,file',
                'walkInAppChecklists.walkinAppFiles:check_list_id,check_list_code,file',
                'walkInAppChecklists.walkinchecklistCat:id,checklist_id,checklist_name',
                'walkInAppDetailes:id,walkin_app_info_id,c_visa_category,c_name,c_passport_number,c_passport_number_two,c_passport_number_three,c_phone,c_email,c_visaType_id,c_visa_category',
                'walkInAppDetailes.cVisaType:id,visa_type',
                'walkInAppDetailes.checklistCat:id,title,price',
                'walkInAppDetailes.walkInAppCheckDetaillists:id,walkin_app_details_id,c_check_list_id,file',
                'walkInAppDetailes.walkInAppCheckDetaillists.walkinAppDetailsFiles:check_list_id,check_list_code,file',
                'walkInAppDetailes.walkInAppCheckDetaillists.checklistDetailCat:id,checklist_id,checklist_name',
                'payments:id,walkin_app_info_id,payment_status,payment_date,payment_time,payment_by,paid_amount,due_amount',
                'payments.payBy:id,name',
                'passports:id,walkin_app_info_id,passport_no,ready_for_delivery_status,deliverd_status,ready_for_delivery_date,ready_for_delivery_time,ready_for_delivery_by,delivered_date,delivered_time,delivered_by',
                'missingFiles:id,walkin_app_info_id,missing_file',
                'statusLogs:id,walkin_app_info_id,changer_id,status,application_status,status_date,status_time'
            )->find($id),
        ];
        //            return $data;
        return view('walkin::application', $data);
    }

    public function barcodeShoe($id)
    {
        $setTitle = __('Visa Application Details');
        $this->setPageData($setTitle, $setTitle, 'far fa-file-alt', [['name' => $setTitle]]);
        $data = [
            'info' => $this->model->with('visaType')->find($id),
        ];
        return view('walkin::barcode_show', $data);
    }

    public function edit(int $id)
    {
        $setTitle = __('Visa Application');
        $setSubTitle = __('Edit Visa Application');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-edit', [['name' => $setTitle], ['name' => $setSubTitle]]);
        $visa_info          = $this->model->find($id);
        $visa_cat           = Checklist::where('id', $visa_info->visa_category)->first();
        $visa_checklist     = ChecklistDetailes::where('checklist_id', $visa_cat->id)->get();
        $visa_app_details   = WalkinAppDetails::where('walkin_app_info_id', $visa_info->id)->with('walkInAppCheckDetaillists', 'checklistCat:id,title', 'checklistCat.checklistDetailes:id,checklist_id,checklist_name')->get();
        $data = [
            'visa_info'         => $visa_info,
            'visa_cat'          => $visa_cat,
            'visa_checklist'    => $visa_checklist,
            'visa_app_details'  => $visa_app_details,
            'visa_type'         => VisaType::all(),
            'visa_category'     => Checklist::where('id', $visa_cat->id)->get(),
            'user'              => Auth::user()->id,
            'countries'         => Country::all(),
            'visa_passport'     => Passport::where('walkin_app_info_id', $visa_info->id)->get(),
        ];
        return view('walkin::edit', $data);
    }

    public function update_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('walk-application-add')) {
                try {
                    date_default_timezone_set('Asia/Dhaka');
                    $date       = date('Y-m-d');
                    $time       = date('h:i:s A');

                    $collection = collect($request->all())->merge(['app_status' => 1]);
                    $walkInApp = $this->model->find($request->update_id);;
                    $walkInApp->update($collection->all());
                    $id = $walkInApp->id;
                    $w_checklist = WalkinAppChecklist::where('walkin_app_info_id', $request->update_id)->delete();
                    foreach ($request->check_list_id as $value) {
                        $walkin_app_check = [
                            'walkin_app_info_id' => $id,
                            'check_list_id'     => $value,
                        ];
                        $walkInAppInfosChecklist = WalkinAppChecklist::insert($walkin_app_check);
                    }
                    $w_checklist = Passport::where('walkin_app_info_id', $request->update_id)->delete();
                    $payments = [];
                    foreach ($request->passport_no as $value) {
                        $payments[]    = [
                            'walkin_app_info_id'    =>  $id,
                            'passport_no'           =>  $value,
                        ];
                    }
                    $payment    = Passport::insert($payments);
                    $output     = $this->store_message($walkInApp);
                    $collect    = collect($walkInApp)->merge(['walkin_app_info_id' => $walkInApp->id, 'application_status' => 1, 'status_date' => $date, 'status_time' => $time, 'changer_id' => Auth::user()->id]);
                    $status     = StatusLog::create($collect->all());
                    // for send Email -----------------------------------------------------------
                    $data = [
                        'c_name'    => $request->p_name,
                        'app_id'     => $walkInApp->uniqueKey,
                    ];
                    Mail::to($request->email)->send(new WalkinMail($data));

                    // for sms ------------------------------------------------------------------
                    $url = "https://bulksmsbd.net/api/smsapi";
                    $data = [
                        "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                        "senderid" => "SaimonGroup",
                        "number" => $request->phone,
                        "message" => "Your Application has Successfully Submitted !. Application Id: $walkInApp->uniqueKey"
                    ];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    curl_close($ch);
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

    public function update_Hold_data(Request $request)
    {
        if ($request->ajax()) {
            date_default_timezone_set('Asia/Dhaka');
            $date       = date('Y-m-d');
            $time       = date('h:i:s A');

            if (permission('walk-application-add')) {
                try {
                    $collection = collect($request->all())->merge(['app_status' => 2]);
                    $walkInApp = $this->model->find($request->update_id);;
                    $walkInApp->update($collection->all());

                    $id = $walkInApp->id;

                    $w_checklist = WalkinAppChecklist::where('walkin_app_info_id', $request->update_id)->delete();
                    foreach ($request->check_list_id as $value) {
                        $walkin_app_check = [
                            'walkin_app_info_id'    => $id,
                            'check_list_id'         => $value,
                        ];
                        $walkInAppInfosChecklist = WalkinAppChecklist::insert($walkin_app_check);
                    }
                    $w_checklist = Passport::where('walkin_app_info_id', $request->update_id)->delete();
                    $payments = [];
                    foreach ($request->passport_no as $value) {
                        $payments[]    = [
                            'walkin_app_info_id'    =>  $id,
                            'passport_no'           =>  $value,
                        ];
                    }
                    $payment    = Passport::insert($payments);
                    $output     = $this->store_message($walkInApp);
                    $collect    = collect($walkInApp)->merge(['walkin_app_info_id' => $walkInApp->id, 'application_status' => 2, 'status_date' => $date, 'status_time' => $time, 'changer_id' => Auth::user()->id]);
                    $status     = StatusLog::create($collect->all());
                    // for send Email -----------------------------------------------------------
                    $data = [
                        'c_name'    => $request->p_name,
                        'app_id'     => $walkInApp->uniqueKey,
                    ];
                    Mail::to($request->email)->send(new HoldMail($data));
                    // for sms ------------------------------------------------------------------
                    $url = "https://bulksmsbd.net/api/smsapi";
                    $data = [
                        "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                        "senderid" => "SaimonGroup",
                        "number" => $request->phone,
                        "message" => "$request->p_name, Your Application is on Hold . Please Provide Missing Document !."
                    ];
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($ch);
                    curl_close($ch);
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

    public function statusChange(Request $request)
    {
        if ($request->ajax()) {
            date_default_timezone_set('Asia/Dhaka');
            $date = date('Y-m-d');
            $time = date('h:i:s A');
            $s_data = $this->model->where('id', $request->status_id)->first();
            $visa_type = VisaType::where('id', $s_data->visaType_id)->first();
            if ($request->visa_status == 1) {
                $data = [
                    'status'    => $request->visa_status,
                    'note'      => $request->note,
                ];
            } elseif ($request->visa_status == 2) {
                $data = [
                    'status'                => $request->visa_status,
                    'note'                  => $request->note,
                    'app_processing_date'   => $date,
                    'app_processing_user'   => Auth::user()->id,
                    'app_processing_time'   => $time,
                ];
            } elseif ($request->visa_status == 3) {
                $data = [
                    'status'                    => $request->visa_status,
                    'note'                      => $request->note,
                    'app_missing_documents_date' => $date,
                    'app_missing_documents_user' => Auth::user()->id,
                ];
            } elseif ($request->visa_status == 4) {
                $data = [
                    'status'                    => $request->visa_status,
                    'note'                      => $request->note,
                    'app_submitted_embassy_date' => $date,
                    'app_submitted_embassy_user' => Auth::user()->id,
                ];
            } elseif ($request->visa_status == 5) {
                $data = [
                    'status'                        => $request->visa_status,
                    'note'                          => $request->note,
                    'app_ready_for_delivery_date'   => $date,
                    'app_ready_for_delivery_user'   => Auth::user()->id,
                ];
            } elseif ($request->visa_status == 6) {
                $data = [
                    'status'                => $request->visa_status,
                    'note'                  => $request->note,
                    'app_delivered_date'    => $date,
                    'app_delivered_user'    => Auth::user()->id,
                ];
            } elseif ($request->visa_status == 7) {
                $data = [
                    'status'            => $request->visa_status,
                    'note'              => $request->note,
                    'app_reject_date'   => $date,
                    'app_reject_user'   => Auth::user()->id,
                ];
            }
            $result     = $this->model->find($request->status_id)->update($data);
            $collection = collect($data)->merge(['walkin_app_info_id' => $request->status_id, 'status_date' => $date, 'status_time' => $time, 'changer_id' => Auth::user()->id]);
            $status     = StatusLog::create($collection->all());
            // for send Email -----------------------------------------------------------
            $data = [
                'status_d'  =>  $request->visa_status,
                'payment'   => $request->paid_amount,
                'datas'     => $s_data
            ];

            Mail::to($request->email)->send(new StatusMail($data));
            // start mobile sms -----------------------------------------------------------
            $url = "https://bulksmsbd.net/api/smsapi";
            if ($request->visa_status == 2) {
                $textBody = "Your $visa_type->visa_type " . WALKIN_APP_STATUS_VALUE[$request->visa_status]  . ",  Note:" . $request->note;
            } elseif ($request->visa_status == 5) {
                $textBody = "Ready for delivery" . ",  Note:" . $request->note;
            } elseif ($request->visa_status == 6) {
                $textBody = "Delivered" . ",  Note:" . $request->note;
            } else {

                $textBody = "Your Visa " . WALKIN_APP_STATUS_VALUE[$request->visa_status]  . ",  Note:" . $request->note;
            }
            //            return response()->json();
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $request->phone,
                "message" => $textBody
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            // end mobile sms -----------------------------------------------------------
            $output = $this->store_message($result, $request->status_id);
            return response()->json($output);
        }
    }


    public function paymentStatusChange(VisaPaymentFormRequest $request)
    {
        if ($request->ajax()) {
            date_default_timezone_set('Asia/Dhaka');
            $date = date('Y-m-d');
            $time = date('h:i:s A');
            $get_paidAmount = $this->model->where('id', $request->payment_status_id)->first();
            if ($get_paidAmount->group_price > 0) {
                $p_price = ($get_paidAmount->checklist->price - $get_paidAmount->checklist->service_charge) + $get_paidAmount->group_price;
            } else {
                $p_price = $get_paidAmount->checklist->price;
            }

            $amount = $get_paidAmount->paid_amount;
            if ($request->visa_payment_status == 2) {
                $data = [
                    'payment_status'    => $request->visa_payment_status,
                    'paid_amount'       => $p_price - $request->discount,
                    'payment'           => $request->payment,
                    'due_amount'        => 0,
                    'discount'          => $request->discount,
                    'payment_note'      => $request->payment_note,
                    'payment_date'      => $date,
                    'payment_time'      => $time,
                    'payment_by'        => Auth::user()->id,
                    'visa_fee'          => $request->visa_fee,
                    'service_charge'    => $request->service_charge,
                    'discounted_by'     => $request->discounted_by,
                ];
                $result     = $this->model->find($request->payment_status_id)->update($data);
                $discount   = $request->net_total - $request->discount;
                if ($request->account_type == 1) {
                    $bank_data = Bank::where('account_id', $request->account_id)->where('employee_id', Auth::user()->id)->first();
                    if ($bank_data) {
                        $bank_data->balance += $discount;
                        $bank_data->update();
                    }
                } elseif ($request->account_type == 2) {
                    $mobile_bank_data = MobileBank::where('account_id', $request->account_id)->where('employee_id', Auth::user()->id)->first();
                    if ($mobile_bank_data) {
                        $mobile_bank_data->balance += $discount;
                        $mobile_bank_data->update();
                    }
                } elseif ($request->account_type == 4) {
                    $cashIn_hand_data = CashInHand::where('account_id', $request->account_id)->where('employee_id', Auth::user()->id)->first();
                    if ($cashIn_hand_data) {
                        $cashIn_hand_data->balance += $discount;
                        $cashIn_hand_data->update();
                    }
                }
                $collection = collect($data)->merge(['walkin_app_info_id' => $request->payment_status_id, 'account_type' => $request->account_type, 'account_id' => $request->account_id]);
                $payment    = Payment::create($collection->all());


                // for send Email -----------------------------------------------------------
                $data = [
                    'payment'           => $p_price - $request->discount,
                    'datas'             => $get_paidAmount,
                    'payment_status'    => $request->visa_payment_status
                ];
                Mail::to($request->email)->send(new TestEmail($data));
                // start mobile sms -----------------------------------------------------------
                $url = "https://bulksmsbd.net/api/smsapi";
                $textBody = "Your Payment has been " . WALKIN_APP_PAYMENT_STATUS[$request->visa_payment_status];
                $data = [
                    "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                    "senderid" => "SaimonGroup",
                    "number" => $request->phone,
                    "message" => $textBody
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                // end mobile sms -----------------------------------------------------------
            } elseif ($request->visa_payment_status == 3) {
                $data = [
                    'payment_status'    => $request->visa_payment_status,
                    'paid_amount'       => $request->payment + $amount,
                    'payment'           => $request->payment,
                    'due_amount'        => $request->due_amount,
                    'payment_note'      => $request->payment_note,
                    'payment_date'      => $date,
                    'payment_time'      => $time,
                    'payment_by'        => Auth::user()->id,
                    'visa_fee'          => $request->visa_fee,
                    'service_charge'    => $request->service_charge,
                    'discounted_by'     => $request->discounted_by,
                ];

                $result     = $this->model->find($request->payment_status_id)->update($data);
                if ($request->account_type == 1) {
                    $bank_data = Bank::where('account_id', $request->account_id)->where('employee_id', Auth::user()->id)->first();
                    if ($bank_data) {
                        $bank_data->balance += $request->paid_amount;
                        $bank_data->update();
                    }
                } elseif ($request->account_type == 2) {
                    $mobile_bank_data = MobileBank::where('account_id', $request->account_id)->where('employee_id', Auth::user()->id)->first();
                    if ($mobile_bank_data) {
                        $mobile_bank_data->balance += $request->paid_amount;
                        $mobile_bank_data->update();
                    }
                } elseif ($request->account_type == 4) {
                    $cashIn_hand_data = CashInHand::where('account_id', $request->account_id)->where('employee_id', Auth::user()->id)->first();
                    if ($cashIn_hand_data) {
                        $cashIn_hand_data->balance += $request->paid_amount;
                        $cashIn_hand_data->update();
                    }
                }
                $collection = collect($data)->merge(['walkin_app_info_id' => $request->payment_status_id, 'account_type' => $request->account_type, 'account_id' => $request->account_id]);
                $payment    = Payment::create($collection->all());

                // for send Email -----------------------------------------------------------
                $data = [
                    'payment'           => $request->paid_amount,
                    'datas'             => $get_paidAmount,
                    'payment_status'    => $request->visa_payment_status
                ];
                Mail::to($request->email)->send(new TestEmail($data));
                // start mobile sms -----------------------------------------------------------
                $url = "https://bulksmsbd.net/api/smsapi";
                $textBody = "Your Visa Payment is" . WALKIN_APP_PAYMENT_STATUS[$request->visa_payment_status] . ", Note:" . $request->payment_note;
                $data = [
                    "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                    "senderid" => "SaimonGroup",
                    "number" => $request->phone,
                    "message"       => $textBody
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                // end mobile sms -----------------------------------------------------------
            }
            $output = $this->store_message($result);
            return response()->json($output);
        }
    }




    //   public function paymentStatusChange(Request $request) {
    //         if ($request->ajax()) {
    //             date_default_timezone_set('Asia/Dhaka');
    //             $date = date('Y-m-d');
    //             $time = date('h:i:s A');
    //             $get_paidAmount = $this->model->where('id', $request->payment_status_id)->first();
    // //            return response()->json($get_paidAmount);
    //             if ($get_paidAmount->group_price > 0) {
    //                 $p_price = ($get_paidAmount->checklist->price - $get_paidAmount->checklist->service_charge) + $get_paidAmount->group_price;
    //             }else{
    //                 $p_price = $get_paidAmount->checklist->price;
    //             }

    //             $amount = $get_paidAmount->paid_amount;
    //             if ($request->visa_payment_status == 2) {
    //                 $data = [
    //                     'payment_status'    => $request->visa_payment_status,
    //                     'paid_amount'       => $p_price - $request->discount,
    //                     'due_amount'        => 0,
    //                     'discount'          => $request->discount,
    //                     'payment_note'      => $request->payment_note,
    //                     'payment_date'      => $date,
    //                     'payment_time'      => $time,
    //                     'payment_by'        => Auth::user()->id,
    //                 ];
    //                 $result     = $this->model->find($request->payment_status_id)->update($data);
    //                 $collection = collect($data)->merge(['walkin_app_info_id' => $request->payment_status_id]);
    //                 $payment    = Payment::create($collection->all());


    // // for send Email -----------------------------------------------------------
    //                 $data = [
    //                     'payment'           => $p_price - $request->discount,
    //                     'datas'             => $get_paidAmount,
    //                     'payment_status'    => $request->visa_payment_status
    //                 ];
    //                 Mail::to($request->email)->send(new TestEmail($data));
    // // start mobile sms -----------------------------------------------------------
    //                 $url = "https://sms.brainwavebd.com/api/sms/send";
    //                 $textBody = "Your Visa Payment is ".WALKIN_APP_PAYMENT_STATUS[$request->visa_payment_status];
    //                 $data = [
    //                     "apiKey"=> "A000050ebd75369-fec2-4d67-86ce-dc3f3d7e101f",
    //                     "contactNumbers"    => $request->phone,
    //                     "senderId"          => "8809612441286",
    //                     "textBody"          => $textBody
    //                 ];
    //                 $ch = curl_init();
    //                 curl_setopt($ch, CURLOPT_URL, $url);
    //                 curl_setopt($ch, CURLOPT_POST, 1);
    //                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //                 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //                 $response = curl_exec($ch);
    // // end mobile sms -----------------------------------------------------------
    //             }
    //             elseif ($request->visa_payment_status == 3){
    //                 $data = [
    //                     'payment_status'    => $request->visa_payment_status,
    //                     'paid_amount'       => $request->paid_amount + $amount,
    //                     'due_amount'        => $request->due_amount,
    //                     'payment_note'      => $request->payment_note,
    //                     'payment_date'      => $date,
    //                     'payment_time'      => $time,
    //                     'payment_by'        => Auth::user()->id,
    //                 ];
    //                 $result     = $this->model->find($request->payment_status_id)->update($data);
    //                 $collection = collect($data)->merge(['walkin_app_info_id' => $request->payment_status_id]);
    //                 $payment    = Payment::create($collection->all());

    // // for send Email -----------------------------------------------------------
    //                 $data = [
    //                     'payment'           => $request->paid_amount,
    //                     'datas'             => $get_paidAmount,
    //                     'payment_status'    => $request->visa_payment_status
    //                 ];
    //                 Mail::to($request->email)->send(new TestEmail($data));
    // // start mobile sms -----------------------------------------------------------
    //                 $url = "https://sms.brainwavebd.com/api/sms/send";
    //                 $textBody = "Your Visa Payment is ".WALKIN_APP_PAYMENT_STATUS[$request->visa_payment_status] .", Note:" .$request->payment_note;
    //                 $data = [
    //                     "apiKey"=> "A000050ebd75369-fec2-4d67-86ce-dc3f3d7e101f",
    //                     "contactNumbers"    => $request->phone,
    //                     "senderId"          => "8809612441286",
    //                     "textBody"          => $textBody
    //                 ];
    //                 $ch = curl_init();
    //                 curl_setopt($ch, CURLOPT_URL, $url);
    //                 curl_setopt($ch, CURLOPT_POST, 1);
    //                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //                 curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //                 $response = curl_exec($ch);
    // // end mobile sms -----------------------------------------------------------
    //             }
    //             $output = $this->store_message($result);
    //             return response()->json($output);
    //         }
    //     }

    public function barcode(Request $request, $id)
    {
        $dd     = $id;
        // $code   = strtoupper(int::random(13));
        $code   = rand(1000000000000, 9999999999999);
        $data   = [
            'barcode' => $code,
        ];
        $result = $this->model->where('id', $dd)->update($data);
        $output = $this->barcode_message($result);
        return redirect()->back()->with($output);
    }

    public function barcodeData(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = $this->model->select('walkin_app_infos.*', 'vt.id', 'vt.visa_type as type', 'pp.id', 'pp.passport_no as passport_number')->where('barcode', $id)
                ->join('visa_types as vt', 'vt.id', '=', 'walkin_app_infos.visaType_id')
                ->join('passports as pp', 'pp.walkin_app_info_id', '=', 'walkin_app_infos.id')
                ->first();
            return response()->json($data);
        }
    }

    public function delete(Request $request)
    {
        WalkinAppChecklist::where('walkin_app_info_id', $request->id)->delete();
        WalkinAppDetails::where('walkin_app_info_id', $request->id)->delete();
        Passport::where('walkin_app_info_id', $request->id)->delete();
        $data   = $this->model->where('id', $request->id)->delete();
        $output = $this->delete_message($data);
        return response()->json($output);
    }

    public function readyForDelivery($id)
    {
        $setTitle = __('Visa Ready For Delivery');
        $this->setPageData($setTitle, $setTitle, 'far fa-file-alt', [['name' => $setTitle]]);
        $visa_info          = $this->model->find($id);
        $all_passport       = Passport::where('walkin_app_info_id', $visa_info->id)->get();
        $data = [
            'visa_info'         => $visa_info,
            'all_passport'      => $all_passport,
            'visa_type'         => VisaType::all(),
            'user'              => Auth::user()->id,
        ];
        return view('walkin::ready_for_delivery', $data);
    }

    public function readyForDelivered(Request $request)
    {
        date_default_timezone_set('Asia/Dhaka');
        $date   = date('Y-m-d');
        $time   = date('h:i:s A');
        $p_data = $this->model->where('id', $request->id)->first();

        if ($request->passport_no) {
            $data = [
                'ready_for_delivery_status' => 1,
                'ready_for_delivery_date'   => $date,
                'ready_for_delivery_time'   => $time,
                'ready_for_delivery_by'     => Auth::user()->id,
            ];
            $result     = Passport::where('passport_no', $request->passport_no)->update($data);
            // for send Email -----------------------------------------------------------
            $data = [
                'passport' => $request->passport_no,
                'datas'     => $p_data
            ];
            Mail::to($request->email)->send(new DeliveryMail($data));
            // start mobile sms -----------------------------------------------------------
            $url = "https://bulksmsbd.net/api/smsapi";
            $textBody = "Your passport no $request->passport_no is Ready for pickup !";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $request->phone,
                "message" => $textBody
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            // end mobile sms -----------------------------------------------------------
            $output     = $this->store_message($result);
            return redirect()->back()->with($output);
        }
        if ($request->passport_no_d) {
            $data = [
                'deliverd_status'   => 1,
                'delivered_date'    => $date,
                'delivered_time'    => $time,
                'delivered_by'      => Auth::user()->id,
            ];
            $result     = Passport::where('passport_no', $request->passport_no_d)->update($data);
            // for send Email -----------------------------------------------------------
            $data = [
                'passport' => $request->passport_no_d,
                'datas'    => $p_data
            ];
            Mail::to($request->email)->send(new DeliveredMail($data));
            // start mobile sms -----------------------------------------------------------
            $url = "https://bulksmsbd.net/api/smsapi";
            $textBody = "Your passport no $request->passport_no_d has been Delivered !.";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $request->phone,
                "message" => $textBody
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            // end mobile sms -----------------------------------------------------------
            $output     = $this->store_message($result);
            return redirect()->back()->with($output);
        }
    }

    public function moneyReceipt($id)
    {
        $setTitle = __('Payment Receipt');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $payment_info = $this->model->with('payments', 'checklist')->find($id);
        // return $payment_info;
        $val        = ($payment_info->checklist->price  - $payment_info->checklist->service_charge);
        $service    = (($payment_info->checklist->service_charge));
        $passport   = Passport::where('walkin_app_info_id', $payment_info->id)->get();
        return view('walkin::receipt', compact('payment_info', 'val', 'passport', 'service'));
    }

    public function poInvoice($id)
    {
        $setTitle = __('P.O Invoice Upload');
        $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
        $payment_info = $this->model->find($id);
        $purchases = DB::table('walkin_app_details')
            ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
            ->where('walkin_app_details.walkin_app_info_id', $payment_info->id)
            ->sum('checklists.price');
        $val = (($payment_info->checklist->price + $purchases) - $payment_info->paid_amount) - $payment_info->discount;
        return view('walkin::po_invoice', compact('payment_info', 'val'));
    }

    public function poInvoiceSave(Request $request)
    {
        $data = $this->model->find($request->id);
        if ($request->File('po_invoice')) {
            $file       = $request->file('po_invoice');
            $extension  = $file->getClientOriginalExtension();
            $filename   = time() . '.' . $extension;
            $file->move('storage', $filename);
            $data->po_invoice = $filename;
        } else {
            $data->po_invoice = $data->po_invoice;
        }
        $data->update();
        return redirect()->route('index');
        $output = $this->store_message($data);
    }


    public function getAccount($id)
    {
        $data = [];
        if ($id == 1) {
            $accounts = Bank::where('employee_id', Auth::user()->id)
                ->get();
            if (!empty($accounts)) {
                foreach ($accounts as $row) {
                    $data[] = [
                        'account_id' => $row->account_id,
                        'name'       => $row->bank_name,
                    ];
                }
            } else {
                "sss";
            }
        } elseif ($id == 2) {
            $accounts = MobileBank::where('employee_id', Auth::user()->id)
                ->get();
            if (!empty($accounts)) {
                foreach ($accounts as $row) {
                    $data[] = [
                        'account_id' => $row->account_id,
                        'name'       => $row->bank_name,
                    ];
                }
            } else {
                "sss";
            }
        } elseif ($id == 4) {
            $accounts = CashInHand::where('employee_id', Auth::user()->id)
                ->get();
            if (!empty($accounts)) {
                foreach ($accounts as $row) {
                    $data[] = [
                        'account_id' => $row->account_id,
                        'name'       => $row->bank_name,
                    ];
                }
            } else {
                "sss";
            }
        }
        return response()->json($data);
    }

    public function getMaxDiscount(Request $request)
    {
        $paymentData = Payment::where('walkin_app_info_id', $request->id)->selectRaw('SUM(service_charge) as total_service_charge, SUM(visa_fee) as total_visa_fee')->first();
        return response()->json($paymentData);
    }
}
