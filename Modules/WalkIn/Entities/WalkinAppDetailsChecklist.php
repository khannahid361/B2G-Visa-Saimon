<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Checklist\Entities\Checklist;
use Modules\Checklist\Entities\ChecklistDetailes;

class WalkinAppDetailsChecklist extends BaseModel
{
    protected $table = 'walkin_app_details_checklests';

    protected $fillable = ['walkin_app_details_id', 'c_check_list_id','file'];

    public function checklistDetailCat()
    {
        return $this->belongsTo(ChecklistDetailes::class,'c_check_list_id','id');
    }
    public function walkinAppDetailsFiles(){
        return $this->hasOne(Image::class,'check_list_code','file');
    }
}
