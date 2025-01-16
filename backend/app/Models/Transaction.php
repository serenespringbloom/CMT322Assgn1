<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'total_price',
        'transaction_type',
        'payment_status',
        'payment_method',
        'cust_email',
        'cust_name',
        'cust_phone',
        'status',
        'description'
    ];

    protected $casts = [
        'total_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
