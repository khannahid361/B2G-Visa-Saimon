<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use App\Models\VisaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Checklist\Entities\Checklist;

class WalkinAppDetails extends BaseModel
{
    protected $table = 'walkin_app_details';
    protected $fillable = ['walkin_app_info_id', 'c_name', 'c_passport_number','c_pp_number_status','c_passport_number_two','c_pp_number_two_status','c_passport_number_three','c_pp_number__three_status','c_phone','c_email','c_visaType_id','c_visa_category'];

    public function walkInAppCheckDetaillists(){
        return $this->hasMany(WalkinAppDetailsChecklist::class,'walkin_app_details_id','id');
    }
    public function cVisaType()
    {
        return $this->belongsTo(VisaType::class,'c_visaType_id','id');
    }
    public function cChecklist()
    {
        return $this->belongsTo(Checklist::class,'c_check_list_id','id');
    }
    public function cChecklists()
    {
        return $this->hasMany(Checklist::class,'c_check_list_id','id');
    }
    public function checklistCat()
    {
        return $this->belongsTo(Checklist::class,'c_visa_category','id');
    }


}
