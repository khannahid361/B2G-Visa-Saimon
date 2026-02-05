<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\VisaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Checklist\Entities\Checklist;
use Modules\Crm\Entities\Collector;

class WalkinAppInfo extends BaseModel
{

    use HasFactory;
    protected $table = 'walkin_app_infos';

   protected $fillable = ['uniqueKey','walkIn_app_type','name','information','customer_id','user_id','p_name','passport_number','pp_number_status','pp_number_two','pp_number_two_status','pp_number_three','pp_number_three_status','phone','email','app_status','status','visaType_id','visa_category',
        'app_received_date','app_received_time','app_received_user','app_processing_date','app_processing_time','app_processing_user','app_missing_documents_date','app_missing_documents_time',
        'app_missing_documents_user','app_submitted_embassy_date','app_submitted_embassy_time','app_submitted_embassy_user','app_ready_for_delivery_date','app_ready_for_delivery_time','app_ready_for_delivery_user','app_delivered_date','app_delivered_time','app_delivered_user','app_reject_date',
        'app_reject_time','app_reject_user','note','payment_status','paid_amount','due_amount','discount','customer_group_id','group_price','payment_note','date','payment_by','payment_date','payment_time','scheduleDate','scheduleTime','barcode','po_file','po_invoice','screen_short','discounted_by'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function passports(){
        return $this->hasMany(Passport::class,'walkin_app_info_id','id');
    }
    public function statusLogs(){
        return $this->hasMany(StatusLog::class,'walkin_app_info_id','id');
    }
    public function missingFiles(){
        return $this->hasMany(MissingFile::class,'walkin_app_info_id','id');
    }
    public function paymentBy()
    {
        return $this->belongsTo(User::class,'payment_by','id');
    }
    public function userAppProcessingUser()
    {
        return $this->belongsTo(User::class,'app_processing_user','id');
    }
    public function appMissingDocuments()
    {
        return $this->belongsTo(User::class,'app_missing_documents_user','id');
    }
    public function appSubmittedEmbassyUser()
    {
        return $this->belongsTo(User::class,'app_submitted_embassy_user','id');
    }
    public function appReadyForDeliveryUser()
    {
        return $this->belongsTo(User::class,'app_ready_for_delivery_user','id');
    }
    public function appDeliveredUser()
    {
        return $this->belongsTo(User::class,'app_delivered_user','id');
    }
    public function collector(){
        return $this->hasOne(Collector::class,'wlkinAppInfo_id','id');
    }

    public function walkInAppChecklists(){
        return $this->hasMany(WalkinAppChecklist::class,'walkin_app_info_id','id');
    }

    public function walkInAppDetailes(){
        return $this->hasMany(WalkinAppDetails::class,'walkin_app_info_id','id');
    }
    public function walkApp()
    {
        return $this->belongsTo(WalkinApp::class,'walkIn_app_id','id');
    }
    public function visaType()
    {
        return $this->belongsTo(VisaType::class,'visaType_id','id');
    }
    public function checklist()
    {
        return $this->belongsTo(Checklist::class,'visa_category','id');
    }

    public function payments(){
        return $this->hasMany(Payment::class,'walkin_app_info_id','id');
    }

    protected $order = ['id' => 'desc'];
    protected $column_order;

    protected $orderValue;
    protected $dirValue;
    protected $startVlaue;
    protected $lengthVlaue;

    public function setOrderValue($orderValue)
    {
        $this->orderValue = $orderValue;
    }
    public function setDirValue($dirValue)
    {
        $this->dirValue = $dirValue;
    }
    public function setStartValue($startVlaue)
    {
        $this->startVlaue = $startVlaue;
    }
    public function setLengthValue($lengthVlaue)
    {
        $this->lengthVlaue = $lengthVlaue;
    }
    protected $visaType_id;
    protected $role_id;
    protected $phone;
    protected $uniqueKey;
    protected $passport_no;


    //methods to set custom search property value
    public function setVisa($visaType_id)
    {
        $this->visaType_id = $visaType_id;
    }
    public function setRoleID($role_id)
    {
        $this->role_id = $role_id;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function setPP($uniqueKey)
    {
        $this->uniqueKey = $uniqueKey;
    }
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
    }
    public function setPassport($passport_no)
    {
        $this->passport_no = $passport_no;
    }


    private function get_datatable_query()
    {
        $query = self::with('visaType','walkInAppDetailes','checklist');
        if (!empty($this->visaType_id)) {
            $query->where('visaType_id', $this->visaType_id);
        }

        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->uniqueKey)) {
            $query->where('uniqueKey', 'like', '%' . $this->uniqueKey . '%');
        }

        if (!empty($this->passport_no)) {
            $query->whereHas('passports', function($passportQuery) {
                $passportQuery->where('passport_no', $this->passport_no);
            });
        }

        if (!empty($this->barcode)) {
            $query->where('barcode', $this->barcode);
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    private function get_datatable_walkin_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        $query = self::with('walkApp:id,walkIn_app_type,name,information','visaType','walkInAppDetailes')->where('app_status','!=',3);
        if (!empty($this->visaType_id)) {
            $query->where('visaType_id', $this->visaType_id);
        }
        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->uniqueKey)) {
            $query->where('uniqueKey', $this->uniqueKey);
        }
        if (!empty($this->barcode)) {
            $query->where('barcode', $this->barcode);
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    private function get_datatable_online_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        $query = self::with('walkApp:id,walkIn_app_type,name,information','visaType','walkInAppDetailes')->where('app_status',3);
        if (!empty($this->visaType_id)) {
            $query->where('visaType_id', $this->visaType_id);
        }


        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->uniqueKey)) {
            $query->where('uniqueKey', 'like', '%' . $this->uniqueKey . '%');
        }
        if (!empty($this->barcode)) {
            $query->where('barcode', $this->barcode);
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }
    private function get_datatable_agent_company_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        $query = self::with('walkApp:id,walkIn_app_type,name,information','visaType','walkInAppDetailes','collector.user')
//                ->where('app_status',3)
//                ->where('status',2)
                ->where('walkIn_app_type', 3);
        if (!empty($this->visaType_id)) {
            $query->where('visaType_id', $this->visaType_id);
        }

        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->uniqueKey)) {
            $query->where('uniqueKey', 'like', '%' . $this->uniqueKey . '%');
        }
        if (!empty($this->barcode)) {
            $query->where('barcode', $this->barcode);
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function getDatatableWalkInList()
    {
        $query = $this->get_datatable_walkin_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function getDatatableOnlineList()
    {
        $query = $this->get_datatable_online_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }
    public function getDatatableAgentCompanyList()
    {
        $query = $this->get_datatable_agent_company_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        return $this->get_datatable_query()->count();
    }
    public function count_walkin_filtered()
    {
        return $this->get_datatable_walkin_query()->count();
    }
    public function count_online_filtered()
    {
        return $this->get_datatable_online_query()->count();
    }

    public function count_all()
    {
        return self::where('id','!=',1)->count();
    }

}
