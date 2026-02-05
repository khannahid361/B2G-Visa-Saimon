<?php

namespace Modules\WalkIn\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalkinApp extends BaseModel
{
    protected $table = 'walkin_apps';

    protected $fillable = ['walkIn_app_type', 'name', 'information'];

}
