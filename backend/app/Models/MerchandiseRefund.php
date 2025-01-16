<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseRefund extends Model
{
    protected $fillable = [
        'order_id',
        'reason',
        'refund_amount',
        'status',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'refund_amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(MerchandiseOrder::class, 'order_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
} 