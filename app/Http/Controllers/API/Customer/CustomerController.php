<?php

namespace App\Http\Controllers\API\Customer;

use App\Mail\CorporateTravelMail;
use App\Mail\GenerialMail;
use App\Mail\TestEmail;
use App\Mail\WalkinMail;
use App\Models\Image;
use Exception;
use App\Traits\UploadAble;
use App\Models\Country;
use App\Models\VisaType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Checklist\Entities\Checklist;
use Modules\Checklist\Entities\ChecklistDetailes;
use Modules\Frontend\Entities\About;
use Modules\Frontend\Entities\Contact;
use Modules\Frontend\Entities\Destination;
use Modules\Frontend\Entities\Faq;
use Modules\Frontend\Entities\Privacy;
use Modules\Frontend\Entities\Service;
use Modules\Frontend\Entities\Slider;
use Modules\Frontend\Entities\Term;
use Modules\Frontend\Entities\Testimonial;
use Modules\WalkIn\Entities\MissingFile;
use Modules\WalkIn\Entities\Passport;
use Modules\WalkIn\Entities\WalkinAppChecklist;
use Modules\WalkIn\Entities\WalkinAppDetails;
use Modules\WalkIn\Entities\WalkinAppInfo;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    use UploadAble;
    protected $model;

    public function __construct(WalkinAppInfo $model)
    {
        $this->model = $model;
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6|max:50',
        ]);
        if ($validator->fails()) {
            $msg = $validator->messages()->first();
        } else {
            if ($request->application_type == 1) {
                $user = User::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'email' => $request->email,
                    'password' => $request->password,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'department_id' => 3,
                    'status' => 1,
                    'type' => 1,
                    'application_type' => $request->application_type,
                    'deletable' => 1,
                    'role_id' => 3,
                ]);
                // for send Email -----------------------------------------------------------
                $data = [
                    'c_name'            => $request->name,
                ];
                Mail::to($request->email)->send(new GenerialMail($data));
                // for sms ------------------------
                $url = "https://bulksmsbd.net/api/smsapi";
                $data = [
                    "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                    "senderid" => "SaimonGroup",
                    "number" => $request->phone,
                    "message" => "Your Registration Successfully Completed !"
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                return response()->json([
                    'success' => true,
                    'message' => 'User Registration successfully',
                    'user' => $user,
                    'sms_response' => $response
                ], Response::HTTP_OK);
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'email' => $request->email,
                    'password' => $request->password,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'department_id' => 3,
                    'status' => 2,
                    'type' => 1,
                    'application_type' => $request->application_type,
                    'deletable' => 1,
                    'role_id' => 3,
                ]);
                // for send Email -----------------------------------------------------------
                $data = [
                    'c_name'            => $request->name,
                    'application_type'  => $request->application_type,
                ];
                Mail::to($request->email)->send(new CorporateTravelMail($data));
                // for sms ------------------------
                $url = "https://bulksmsbd.net/api/smsapi";
                $data = [
                    "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                    "senderid" => "SaimonGroup",
                    "number" => $request->phone,
                    "message" => "Your Visa Account is Inactive, Please contact 01300000000 !"
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                return response()->json([
                    'success' => true,
                    'message' => 'User Registration successfully',
                    'user' => $user,
                    'sms_response' => $response
                ], Response::HTTP_OK);
            }
        }
        return response()->json([
            'success' => false,
            'message' => $msg,
        ], 400);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email'     => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }
        //Token created, return with success response and jwt token
        $row = Auth::user();
        if ($row->status == 1) {
            $temp_array = array();
            $temp_array['name'] = $row->name;
            $temp_array['email'] = $row->email;
            $temp_array['phone'] = $row->phone;
            $temp_array['gender'] = $row->gender;
            $temp_array['application_type'] = $row->application_type;
            $temp_array['information'] = $row->information;
            $temp_array['avatar'] = $row->avatar ? asset('storage/' . MATERIAL_IMAGE_PATH . $row->avatar) : asset('images/product.svg');
            $data = $temp_array;
            return response()->json([
                'success' => true,
                'message' => 'Successfully Login !',
                'token' => $token,
                'user' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Your account is Inactive, Please contact with Saimon Group !',
            ]);
        }
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'], 404);
        }

        // Create a reset token
        $token = Str::random(64);

        // Delete old tokens
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Insert new token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Prepare Mail Data
        // $resetUrl = url('/api/reset-password?token=' . $token . '&email=' . urlencode($request->email));

        $mailData = [
            'user_name' => $user->name ?? 'Customer',
            // 'reset_link' => $resetUrl,
            'token' => $token,
            'email' => $request->email
        ];
        Mail::to($request->email)->send(new PasswordResetMail($mailData));


        return response()->json([
            'success' => true,
            'message' => 'Password reset token generated.',
        ], 200);
    }

    /**
     * Reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token or email.'], 400);
        }
        // Check token expiration (optional, e.g., 5 mins)
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Token expired.'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,'message' => 'User not found.'], 404);
        }

        // return $user->password;
        $user->password = $request->password;
        $user->save();

        // Delete token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ], 200);
    }

    public function myProfile(Request $request)
    {
        $user = Auth::user();

        $data = [
            'name'             => $user->name,
            'email'            => $user->email,
            'phone'            => $user->phone,
            'gender'           => $user->gender,
            'application_type' => $user->application_type,
            'information'      => $user->information,
            'avatar'           => $user->avatar
                ? asset('storage/' . MATERIAL_IMAGE_PATH . $user->avatar)
                : asset('images/images.png'),
        ];

        return response()->json(['data' => $data]);
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $data = $request->only(['name', 'email', 'information', 'phone', 'gender']);

        // Handle avatar upload if provided
        if (!empty($request->avatar)) {
            $data['avatar'] = $this->upload_base64_image($request->avatar, MATERIAL_IMAGE_PATH);
        }

        // Update the user
        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
        ]);
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->update([
                'password' => $request->password
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Password successfully updated',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Current password does not matched',
            ], 400);
        }
    }

    //Search Country wise checklist
    public function get_checklist_data(Request $request)
    {
        $fromCountry_id = $request->fromCountry_id;
        $toCountry_id   = $request->toCountry_id;
        $result         = Checklist::select(
            'id',
            'title',
            'price',
            'description',
            'fromCountry_id',
            'toCountry_id',
            'image'
        )->where('appliactionType_id', 1)->where('status', 1)->with(
            'formCountry:id,country_name',
            'toCountry:id,country_name',
            'ChecklistDetailes:id,checklist_id,checklist_name'
        );

        if ($fromCountry_id) {
            $result = $result->where('fromCountry_id', $fromCountry_id);
        }
        if ($toCountry_id) {
            $result = $result->where('toCountry_id', $toCountry_id);
        }
        $result = $result->get();
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    // public function get_checklist_login_user_data(Request $request){
    //     $fromCountry_id = $request->fromCountry_id;
    //     $toCountry_id   = $request->toCountry_id;
    //     $auth_id        = Auth::id();
    //     $user_application_type  = User::where('id',$auth_id)->first();
    //   $result      = Checklist::select('id','appliactionType_id', 'title', 'price', 'description', 'fromCountry_id', 'toCountry_id','image')
    //                 ->where('status',1)
    //                 ->where('appliactionType_id',$user_application_type->application_type)
    //                 ->with('formCountry:id,country_name','toCountry:id,country_name','ChecklistDetailes:id,checklist_id,checklist_name');

    //     if ($fromCountry_id) {
    //         $result = $result->where('fromCountry_id', $fromCountry_id);
    //     }
    //     if ($toCountry_id){
    //         $result = $result->where('toCountry_id', $toCountry_id);
    //     }
    //     $result = $result->get();
    //     return response()->json([
    //         'success' => true,
    //         'data' => $result
    //     ]);
    // }

    public function get_checklist_login_user_data(Request $request)
    {
        $fromCountry_id = $request->fromCountry_id;
        $toCountry_id   = $request->toCountry_id;
        $auth_id        = Auth::id();
        $user_application_type  = User::where('id', $auth_id)->first();
        $result      = Checklist::select('checklists.id', 'checklists.appliactionType_id', 'checklists.title', 'checklists.price', 'checklists.service_charge', 'checklists.description', 'checklists.fromCountry_id', 'checklists.toCountry_id', 'checklists.image')
            ->where('checklists.status', 1)
            ->where('checklists.appliactionType_id', Auth::user()->application_type)
            ->with('formCountry:id,country_name', 'toCountry:id,country_name', 'ChecklistDetailes:id,checklist_id,checklist_name');

        if ($fromCountry_id) {
            $result = $result->where('fromCountry_id', $fromCountry_id);
        }
        if ($toCountry_id) {
            $result = $result->where('toCountry_id', $toCountry_id);
        }
        $result = $result->get();
        return response()->json([
            'success' => true,
            'data' => $result,
            'group_price' => $user_application_type
        ]);
    }

    //    get all Countries
    public function get_from_country(Request $request)
    {
        $data = Country::select('id', 'type', 'country_name')->where('type', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
    public function get_to_country(Request $request)
    {
        $data = Country::select('id', 'type', 'country_name')->where('type', 2)->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // Visa Application ------------------
    public function storeApp(Request $request)
    {
        $date = date('Y-m-d');
        if (!empty($request->po_file)) {
            $file = $this->upload_base64_image($request->po_file, MATERIAL_IMAGE_PATH);
        } else {
            $file = '';
        }
        $onlineApp = new WalkinAppInfo();
        if (!empty($onlineApp)) {
            $onlineApp->uniqueKey       = $request->uniqueKey;
            $onlineApp->walkIn_app_type = Auth::user()->application_type;
            $onlineApp->name            = Auth::user()->name;
            $onlineApp->information     = Auth::user()->information;
            $onlineApp->customer_id     = Auth::user()->id;
            $onlineApp->user_id         = Auth::user()->id;
            $onlineApp->group_price     = Auth::user()->group_price ?? 0;
            $onlineApp->customer_group_id = Auth::user()->customer_group_id;
            $onlineApp->p_name          = $request->p_name;
            $onlineApp->phone           = $request->phone;
            $onlineApp->email           = $request->email;
            $onlineApp->visaType_id     = $request->visaType_id;
            $onlineApp->visa_category   = $request->visa_category;
            $onlineApp->date            = $date;
            // $onlineApp->scheduleDate    = $request->scheduleDate; // check
            // $onlineApp->scheduleTime    = $request->scheduleTime; // check
            $onlineApp->status          = 1;
            $onlineApp->app_status      = 3;
            $onlineApp->po_file         = $file;
            $onlineApp->save();
            $id = $onlineApp->id;
            if ($request->has('walk_in_app_checklists')) {
                foreach ($request->walk_in_app_checklists as $val) {
                    $walkInAppCheckList = [
                        'walkin_app_info_id'    => $id,
                        'check_list_id'         => $val['check_list_id'],
                        'file'                  => $val['file']
                    ];
                    $walkInAppChecklistInfos = WalkinAppChecklist::create($walkInAppCheckList);
                }
            }
            if ($request->has('passport')) {
                foreach ($request->passport as $val) {
                    $walkInAppCheckList = [
                        'walkin_app_info_id'    => $id,
                        'passport_no'           => $val['passport_no'],
                    ];
                    $payment = Passport::create($walkInAppCheckList);
                }
            }
            // for send Email -----------------------------------------------------------
            $data = [
                'c_name'    => $request->p_name,
                'app_id'     => $onlineApp->uniqueKey,
            ];
            Mail::to($request->email)->send(new WalkinMail($data));
            // for sms ------------------------
            $url = "https://bulksmsbd.net/api/smsapi";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number" => $request->phone,
                "message" => "Your Application has Successfully Submitted !. Application Id: $onlineApp->uniqueKey"
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            return response()->json([
                'success' => true,
                'message' => 'Application saved Successfully !',
            ]);
        }
    }


    public function fileUpload(Request $request)
    {
        //     if(!empty($request->file)) {
        //            $file = $this->upload_base64_image(implode("[ ]", $request->file), MATERIAL_IMAGE_PATH);
        //        }
        if (!empty($request->file)) {
            $file = $this->upload_base64_image($request->file, MATERIAL_IMAGE_PATH);
        }
        $data = new Image();
        $data->check_list_id    = $request->check_list_id;
        $data->check_list_code  = $request->check_list_code;
        $data->file             = $file;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Image saved Successfully !',
        ]);
    }

    public function fileUpdate(Request $request, $fileCode)
    {
        if (!empty($request->file)) {
            $file = $this->upload_base64_image($request->file, MATERIAL_IMAGE_PATH);
        }
        $data = Image::where('check_list_code', $fileCode)->first();
        $data->check_list_id = $request->check_list_id;
        $data->check_list_code = $request->check_list_code;
        $data->file = $file;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Image Updated Successfully !',
        ]);
    }

    public function getFileData()
    {
        $data = Image::all();
        if (!$data->isEmpty()) {
            foreach ($data as $row) {
                $temp_array = array();
                $temp_array['check_list_id'] = $row->check_list_id;
                $temp_array['check_list_code'] = $row->check_list_code;
                $temp_array['file'] = $row->file ? asset('storage/' . MATERIAL_IMAGE_PATH . $row->file) : asset('images/product.svg');
                $data[] = $temp_array;
            }
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found!'
            ]);
        }
    }

    public function missingFile(Request $request)
    {
        if (!empty($request->missing_file)) {
            $file = $this->upload_base64_image($request->missing_file, MATERIAL_IMAGE_PATH);
        }
        $data = new MissingFile();
        $data->walkin_app_info_id = $request->walkin_app_info_id;
        $data->missing_file       = $file;
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Image saved Successfully !',
        ]);
    }

    public function missingFileList($id)
    {
        $datas = MissingFile::where('walkin_app_info_id', $id)->get();
        if (!$datas->isEmpty()) {
            foreach ($datas as $row) {
                $temp_array = array();
                $temp_array['missing_file'] = $row->missing_file ? asset('storage/' . MATERIAL_IMAGE_PATH . $row->missing_file) : '';
                $data[] = $temp_array;
            }
            return response()->json([
                'success'   => true,
                'data'      => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found!'
            ]);
        }
    }

    public function getAppData(Request $request)
    {
        $user = Auth::user()->id;
        $data = WalkinAppInfo::select(
            'id',
            'uniqueKey',
            'walkIn_app_type',
            'visa_category',
            'visaType_id',
            'user_id',
            'p_name',
            'date',
            'email',
            'phone',
            'passport_number',
            'pp_number_two',
            'pp_number_three',
            'status',
            'payment_status',
            'paid_amount',
            'po_invoice',
            'group_price'
        )
            ->with(
                'visaType:id,visa_type',
                'checklist:id,title,price,service_charge,fromCountry_id,toCountry_id',
                'checklist.formCountry:id,country_name',
                'checklist.toCountry:id,country_name',
                'walkInAppDetailes:id,walkin_app_info_id,c_visa_category,c_passport_number_two,c_passport_number_three',
                'walkInAppDetailes.checklistCat:id,title,price',
                'passports:id,walkin_app_info_id,passport_no'
            )
            ->where('user_id', $user)
            ->where('app_status', 3)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function viewAppData(Request $request, $id)
    {
        $data = WalkinAppInfo::select(
            'id',
            'uniqueKey',
            'walkIn_app_type',
            'name',
            'information',
            'visa_category',
            'visaType_id',
            'user_id',
            'p_name',
            'date',
            'email',
            'phone',
            'passport_number',
            'pp_number_two',
            'pp_number_three',
            'status',
            'payment_status',
            'paid_amount',
            'scheduleDate',
            'scheduleTime',
            'po_invoice',
            'group_price'
        )
            ->with(
                'visaType:id,visa_type',
                'checklist:id,title,price,service_charge,fromCountry_id,toCountry_id',
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
                'passports:id,walkin_app_info_id,passport_no'
            )
            ->where('id', $id)
            ->first();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        if (!WalkinAppInfo::where('id', $id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Application Id!',
            ]);
        }
        WalkinAppChecklist::where('walkin_app_info_id', $id)->delete();
        WalkinAppDetails::where('walkin_app_info_id', $id)->delete();
        $this->model->where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Application Deleted Successfully !',
        ]);
    }

    public function editAppData(Request $request, $id)
    {
        $data = WalkinAppInfo::where('id', $id)->with(
            'visaType',
            'checklist',
            'walkInAppDetailes'
        )->first();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function update_data(Request $request)
    {
        $collection     = collect($request->all())->merge(['app_status' => 3]);
        $walkInApp      = $this->model->find($request->update_id);;
        $ss             = $walkInApp->update($collection->all());
        $id             = $walkInApp->id;
        $w_checklist    = WalkinAppChecklist::where('walkin_app_info_id', $request->update_id)->delete();
        if ($request->has('walk_in_app_checklists')) {

            foreach ($request->walk_in_app_checklists as $val) {
                $walkInAppCheckList = [
                    'walkin_app_info_id'    => $id,
                    'check_list_id'         => $val['check_list_id'],
                    'file'                  => $val['file']
                ];
                $walkInAppChecklistInfos = WalkinAppChecklist::create($walkInAppCheckList);
            }
        }
        $w_checklist = Passport::where('walkin_app_info_id', $request->update_id)->delete();
        if ($request->has('passport')) {
            foreach ($request->passport as $val) {
                $walkInAppCheckList = [
                    'walkin_app_info_id'    => $id,
                    'passport_no'           => $val['passport_no'],
                ];
                $payment = Passport::create($walkInAppCheckList);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'Application Updated Successfully !',
        ]);
    }

    public function visaTypeWiseCatWithChecklist(Request $request)
    {
        $data = VisaType::select('id', 'visa_type')->with(
            'checklists:id,visaType_id,title',
            'checklists.checklistDetailes:id,checklist_id,checklist_name'
        )->get();
        return response()->json([
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function getVisaType(Request $request)
    {
        $data = VisaType::get(['id', 'visa_type']);
        return response()->json([
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function VisaTypeWiseCat($id)
    {
        $data = [];
        $cat = Checklist::select(
            'id',
            'user_id',
            'visaType_id',
            'price',
            'title',
            'fromCountry_id',
            'toCountry_id'
        )->where('status', 1)->with(
            'visaType:id,visa_type',
            'checklistDetailes:id,checklist_id,checklist_name'
        )
            ->where('visaType_id', $id)
            ->where('appliactionType_id', Auth::user()->application_type)->get();
        return response()->json($cat);
    }

    public function catWiseChecklist($id)
    {
        $data = [];
        $cat = ChecklistDetailes::where('checklist_id', $id)->get();
        foreach ($cat as $row) {
            $data[] = [
                'visaType_id'   => $row->id,
                'title'         => $row->checklist_name,
            ];
        }
        return response()->json($data);
    }

    public function statusChange(Request $request, $statusId)
    {
        $data = [
            'status'    => $request->visa_status,
            'note'      => $request->note,
        ];
        $result = WalkinAppInfo::where('id', $statusId)->update($data);
        return response()->json($result);
    }

    public function poInvoiceDownload($id)
    {
        $data           = WalkinAppInfo::find($id);
        if ($data->po_invoice) {
            $imagePath  = Storage::url($data->po_invoice);
            return response()->download(public_path($imagePath));
        }
    }

    //--------------------------------- Front All Api ----------------------------------

    public function fileDownload($id)
    {
        $data       = Checklist::find($id);
        if ($data->image) {
            $imagePath  = Storage::url($data->image);
            return response()->download(public_path($imagePath));
            //            return response()->download(storage_path($data->image));
            //            return response()->download("https://visasaimon.net/public_html/storage/".$data->image);
        } else {
            return redirect('https://visasaimon.com/fileNotFound');
        }
    }


    public function getAboutUs()
    {
        $aboutus = About::first();
        return response()->json([
            'success'   => true,
            'data'      => $aboutus
        ]);
    }

    public function getFaq()
    {
        $faq = Faq::get();
        return response()->json([
            'success'   => true,
            'data'      => $faq
        ]);
    }

    public function getService()
    {
        $service = Service::get();
        return response()->json([
            'success'   => true,
            'data'      => $service
        ]);
    }


    public function getSlider()
    {
        $slider = Slider::get();
        if (!$slider->isEmpty()) {
            foreach ($slider as $row) {
                $temp_array = array();
                $temp_array['message'] = $row->message;
                $temp_array['image'] = $row->image ? asset('storage/' . $row->image) : asset('images/product.svg');
                $data[] = $temp_array;
            }
            return response()->json([
                'success'   => true,
                'data'      => $data
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'data'      => 'No data found!'
            ]);
        }
    }

    public function getCondition()
    {
        $condition = Term::first();
        return response()->json([
            'success'   => true,
            'data'      => $condition
        ]);
    }

    public function getPrivacyPolicy()
    {
        $privacy = Privacy::where('policy_id', 1)->first();
        return response()->json([
            'success'   => true,
            'data'      => $privacy
        ]);
    }
    public function paymentRefundPolicy()
    {
        $privacy = Privacy::where('policy_id', 2)->first();
        return response()->json([
            'success'   => true,
            'data'      => $privacy
        ]);
    }

    public function contractUs(Request $request)
    {
        $data = new Contact();
        $data->name     = $request->name;
        $data->email    = $request->email;
        $data->phone    = $request->phone;
        $data->subject  = $request->subject;
        $data->message  = $request->message;

        $data = $data->save();
        // for send Email -----------------------------------------------------------
        $data = ['message' => "Customer Name: $request->name, Phone: $request->phone, Message: $request->message"];
        Mail::to($request->email)->send(new TestEmail($data));
        return response()->json([
            'success' => true,
            'message' => 'Successfully Submitted!'
        ]);
    }

    public function get_tracking_data(Request $request)
    {
        $uniqueKey = $request->uniqueKey;
        if ($uniqueKey) {
            $data = WalkinAppInfo::where('uniqueKey', $uniqueKey)->with(
                'visaType',
                'checklist',
                'walkInAppDetailes'
            )->first();
        }
        return response()->json([
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function getDestination()
    {
        return 'lol';
        $destination = Destination::get();
        if (!$destination->isEmpty()) {
            foreach ($destination as $row) {
                $temp_array                     = array();
                $temp_array['destination_id']   = $row->country->id;
                $temp_array['destination_name'] = $row->country->country_name;
                $temp_array['image']            = $row->image ? asset('storage/' . $row->image) : asset('images/product.svg');
                $data[]                         = $temp_array;
            }
            return response()->json([
                'success'   => true,
                'data'      => $data
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'data'      => 'No data found!'
            ]);
        }
    }

    public function getDestinationChicklist(Request $request)
    {
        $dstination_id = $request->destination_id;
        if ($dstination_id) {
            $data = Checklist::select('id', 'title', 'price', 'description', 'toCountry_id')->where('status', 1)->where('toCountry_id', $dstination_id)->with('toCountry:id,country_name', 'ChecklistDetailes:id,checklist_id,checklist_name')->get();
        }
        return response()->json([
            'success'   => true,
            'data'      => $data
        ]);
    }

    public function getTestimonials()
    {
        $testimonials = Testimonial::get();
        if (!$testimonials->isEmpty()) {
            foreach ($testimonials as $row) {
                $temp_array             = array();
                $temp_array['name']     = $row->name;
                $temp_array['details']  = $row->details;
                $temp_array['image']    = $row->image ? asset('storage/' . $row->image) : asset('images/product.svg');
                $data[]                 = $temp_array;
            }
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => 'No data found!'
            ]);
        }
    }
}
