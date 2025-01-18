<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseRefund extends Model
{
    protected $fillable = [
        'order_id',
        'refund_amount',
        'reason',
        'status',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime'
    ];

    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_REJECTED = 'REJECTED';

    public function order()
    {
        return $this->belongsTo(MerchandiseOrder::class, 'order_id');
    }
} 