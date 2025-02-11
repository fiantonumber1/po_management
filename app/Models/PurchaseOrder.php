<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'po_date',
        'invoice_number',
        'invoice_date',
        'due_date',
        'buyer',
        'grand_total',
        'status'
    ];
}
