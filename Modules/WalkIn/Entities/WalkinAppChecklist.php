<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Checklist\Entities\Checklist;
use Modules\Checklist\Entities\ChecklistDetailes;

class WalkinAppChecklist extends BaseModel
{
    protected $table = 'walkin_app_checklists';
    protected $fillable = ['walkin_app_info_id', 'check_list_id','file'];

    public function walkinchecklistCat()
    {
        return $this->belongsTo(ChecklistDetailes::class,'check_list_id','id');
    }
    public function walkinAppFiles(){
        return $this->hasOne(Image::class,'check_list_code','file');
    }

}
