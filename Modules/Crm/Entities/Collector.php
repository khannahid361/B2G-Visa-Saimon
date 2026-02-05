<?php

namespace Modules\Crm\Entities;

use App\Models\BaseModel;
use App\Models\User;
use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collector extends BaseModel
{
    use HasFactory;

    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class,'collector_id','id');
    }
}
