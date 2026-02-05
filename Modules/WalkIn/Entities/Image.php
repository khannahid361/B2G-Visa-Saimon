<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends BaseModel
{
    protected $table = 'images';

    protected $fillable = ['check_list_id', 'check_list_code', 'file'];
}
