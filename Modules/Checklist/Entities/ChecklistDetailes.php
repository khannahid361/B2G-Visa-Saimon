<?php

namespace Modules\Checklist\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistDetailes extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'checklist_id','checklist_name','created_by', 'updated_at'];

    public function checklist(){
        return $this->belongsTo(Checklist::class,'checklist_id','id');
    }

}
