<?php

namespace Modules\WalkIn\Entities;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{

    protected $fillable = [
        'walkin_app_info_id',
        'refund_date',
        'amount',
        'reason',
        'bank_account_id',
        'created_by',
        'account_type'
    ];
    


}
