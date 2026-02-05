<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Mail\Accept;
use App\Mail\CustomercredentialMail;
use App\Mail\TestEmail;
use App\Models\User;
use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\CustomerGroup;
use Modules\Customer\Http\Requests\GroupFormRequest;

class CustomerController extends BaseController
{
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('customer-access')) {
            $setTitle = __('Customer');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            $data = [
                'customer_groups' => User::where('role_id', 3)->get(),
            ];
            return view('customer::index', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function create()
    {
        if (permission('customer-add')) {
            $setTitle = __('Customer Add');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            $data = [
                'customer_groups' => User::where('role_id', 3)->get(),
            ];
            return view('customer::create', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function store(Request $request)
    {
        if (permission('customer-access')) {
            $collection   = collect($request->all())->merge(['role_id' => 3])->except('password', 'password_confirmation');
            $collection   = $this->track_data($collection, $request->update_id);
            if (!empty($request->password)) {
                $collection   = $collection->merge(['password' => $request->password]);
            }
            $result       = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());

            $output       = $this->store_message($result);

            // for send Email -----------------------------------------------------------
            $data = [
                'email'    => $request->email,
                'password'     => $request->password,
                'name'        => $request->name,
            ];
            Mail::to($request->email)->send(new CustomercredentialMail($data));
            // for sms ------------------------------------------------------------------
            $url = "https://bulksmsbd.net/api/smsapi";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $request->phone,
                "message" => "You have been registered as a customer.Your email: $request->email and Password: $request->password"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return redirect()->route('customer');
        } else {
            return $this->access_blocked();
        }
    }

    public function inactiveCustomer()
    {
        if (permission('customer-access')) {
            $setTitle = __('Inactive Customer');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            $data = [
                'customer_groups' => User::where('role_id', 3)->get(),
            ];
            return view('customer::inactive_index', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if ($request->ajax()) {

            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            if (!empty($request->phone)) {
                $this->model->setPhone($request->phone);
            }
            if (!empty($request->email)) {
                $this->model->setEmail($request->email);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }

            $this->set_datatable_default_properties($request); //set datatable default properties
            $list = $this->model->getDatatableList(); //get table data
            //            return Response()->json($list);

            $data = [];
            $no = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';

                if (permission('customer-view')) {
                    $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '">' . self::ACTION_BUTTON['View'] . '</a>';
                }
                if (permission('customer-status')) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"  data-status="' . $value->status . '" data-email="' . $value->email . '" data-phone="' . $value->phone . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('customer-delete')) {
                    if ($value->deletable == 2) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . self::ACTION_BUTTON['Delete'] . '</a>';
                    }
                }
                $row = [];
                $row[] = $no;
                $row[] = $this->table_image(MATERIAL_IMAGE_PATH, $value->avatar, $value->name, $value->gender);
                $row[] = $value->application_type ? APPLICATION_TYPE[$value->application_type] : '';
                $row[] = $value->name;
                $row[] = $value->phone;
                $row[] = $value->email ? $value->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>';
                $row[] = $value->gender ? GENDER_LABEL[$value->gender] : '';
                $row[] = CUSTOMER_STATUS_VALUE[$value->status];
                $row[] = action_button($action); //custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw(
                $request->input('draw'),
                $this->model->count_all(),
                $this->model->count_filtered(),
                $data
            );
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function get_datatable_inactive_data(Request $request)
    {
        if ($request->ajax()) {

            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            if (!empty($request->phone)) {
                $this->model->setPhone($request->phone);
            }
            if (!empty($request->email)) {
                $this->model->setEmail($request->email);
            }
            if (!empty($request->status)) {
                $this->model->setStatus($request->status);
            }

            $this->set_datatable_default_properties($request); //set datatable default properties
            $inactive_list = $this->model->getInactiveDatatableList(); //get table data
            //            return Response()->json($list);
            $data = [];
            $no = $request->input('start');
            foreach ($inactive_list as $value) {
                $no++;
                $action = '';

                if (permission('customer-view')) {
                    $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '">' . self::ACTION_BUTTON['View'] . '</a>';
                }
                if (permission('customer-status')) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '"  data-status="' . $value->status . '" data-email="' . $value->email . '" data-phone="' . $value->phone . '"><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                }
                if (permission('customer-delete')) {
                    if ($value->deletable == 2) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . self::ACTION_BUTTON['Delete'] . '</a>';
                    }
                }
                $row = [];
                $row[] = $no;
                $row[] = $this->table_image(MATERIAL_IMAGE_PATH, $value->avatar, $value->name, $value->gender);
                $row[] = APPLICATION_TYPE[$value->application_type];
                $row[] = $value->name;
                $row[] = $value->phone;
                $row[] = $value->email ? $value->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>';
                $row[] = $value->gender ? GENDER_LABEL[$value->gender] : '';
                $row[] = action_button($action); //custom helper function for action button
                $data[] = $row;
            }
            return $this->datatable_draw(
                $request->input('draw'),
                $this->model->count_all(),
                $this->model->count_inactive_filtered(),
                $data
            );
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            if (permission('customer-view')) {
                $user   = $this->model->findOrFail($request->id);
                return view('customer::view-data', compact('user'));
            }
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('customer-delete')) {
                $result   = $this->model->find($request->id)->delete();
                $output   = $this->delete_message($result);
            } else {
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            $user_data   = User::where('id', $request->status_id)->first();
            if ($request->status == 1) {
                $data = [
                    'status' => $request->status,
                ];
            } elseif ($request->status == 2) {
                $data = [
                    'status' => $request->status,
                ];
            }
            $result   = $this->model->find($request->status_id)->update($data);
            // for send Email -----------------------------------------------------------
            //            .CUSTOMER_STATUS_VALUES[$request->status]
            $datas = [
                'message'  => $request->status_id,
                'user_data' => $user_data->name
            ];
            Mail::to($request->email)->send(new Accept($datas));
            // start mobile sms -----------------------------------------------------------
            $url = "https://sms.solutionsclan.com/api/sms/send";
            $textBody = "Your Saimon Visa Account is " . CUSTOMER_STATUS_VALUES[$request->status];
            $data = [
                "apiKey" => "A000476e3844b7c-93dd-49fe-b2d5-d43793586877",
                "contactNumbers" => $request->phone,
                "senderId" => "8809612441055",
                "textBody" => $textBody
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $response = curl_exec($ch);
            // end mobile sms -----------------------------------------------------------
            $output = $this->status_message($result, $request->status_id);
            return response()->json($output);
        }
    }
}
