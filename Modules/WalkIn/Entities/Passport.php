<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passport extends BaseModel
{
    protected $table = 'passports';
    protected $fillable = ['walkin_app_info_id','passport_no','ready_for_delivery_status','deliverd_status','ready_for_delivery_date','ready_for_delivery_time','ready_for_delivery_by','delivered_date','delivered_time','delivered_by'];

    public function readyForDeliveryBy()
    {
        return $this->belongsTo(User::class,'ready_for_delivery_by','id');
    }
    public function deliveredBy()
    {
        return $this->belongsTo(User::class,'delivered_by','id');
    }
}
