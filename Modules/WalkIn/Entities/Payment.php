<?php
namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Bank\Entities\ManageBank;

class Payment extends BaseModel
{
    protected $table = 'payments';
    protected $fillable = ['walkin_app_info_id','account_type','account_id','payment_status','payment','paid_amount', 'due_amount','discount','payment_note','payment_date','payment_time','payment_by', 'service_charge', 'visa_fee'];

    public function payBy()
    {
        return $this->belongsTo(User::class,'payment_by','id');
    }
    public function appInfo()
    {
        return $this->belongsTo(WalkinAppInfo::class,'walkin_app_info_id','id');
    }
    public function accounts()
    {
        return $this->belongsTo(ManageBank::class,'account_id','id');
    }
}
