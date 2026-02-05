<?php

namespace Modules\WalkIn\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PoInvoice extends Model
{
    protected $table = 'poivoices';
    protected $fillable = ['walkin_app_info_id', 'po_invoice'];
}
