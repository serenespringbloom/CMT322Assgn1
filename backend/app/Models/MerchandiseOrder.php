<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseOrder extends Model
{
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'payment_status',
        'order_status'
    ];

    public function items()
    {
        return $this->hasMany(MerchandiseOrderItem::class, 'order_id');
    }

    public function refund()
    {
        return $this->hasOne(MerchandiseRefund::class, 'order_id');
    }
} 