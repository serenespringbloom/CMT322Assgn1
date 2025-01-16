<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchandiseOrderItem extends Model
{
    protected $table = 'merchandise_order_items';
    
    protected $fillable = [
        'order_id',
        'merchandise_id',
        'size',
        'quantity',
        'unit_price'
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