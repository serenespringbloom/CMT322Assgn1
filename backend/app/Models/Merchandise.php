<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $table = 'merchandises';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'price',
        'description',
        'image_url',
        'sizes_inventory',
        'is_active'
    ];

    protected $casts = [
        'sizes_inventory' => 'array',
        'is_active' => 'boolean'
    ];

    public function orderItems()
    {
        return $this->hasMany(MerchandiseOrderItem::class, 'id');
    }
}
