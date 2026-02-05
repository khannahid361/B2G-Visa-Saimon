<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MissingFile extends BaseModel
{
    protected $table = 'missing_files';
    protected $fillable = ['walkin_app_info_id','missing_file'];
}
