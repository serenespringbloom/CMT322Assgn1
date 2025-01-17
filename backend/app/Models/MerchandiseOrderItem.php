<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseOrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'merchandise_id',
        'size',
        'quantity',
        'unit_price',
        'total_amount'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function order()
    {
        return $this->belongsTo(MerchandiseOrder::class, 'order_id');
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, 'merchandise_id');
    }
} 