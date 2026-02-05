<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusLog extends BaseModel
{
    protected $table = 'missing_status_logs';
    protected $fillable = ['walkin_app_info_id','status','application_status','changer_id', 'status_date','status_time','note'];

    public function changeBy()
    {
        return $this->belongsTo(User::class,'changer_id','id');
    }
}
