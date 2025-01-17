<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseOrder extends Model
{
    protected $fillable = [
        'merchandise_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'size',
        'quantity',
        'unit_price',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity' => 'integer'
    ];

    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_REFUNDED = 'REFUNDED';
    const STATUS_REFUND_PENDING = 'REFUND_PENDING';

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }

    public function refunds()
    {
        return $this->hasMany(MerchandiseRefund::class, 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(MerchandiseOrderItem::class, 'order_id');
    }
} 