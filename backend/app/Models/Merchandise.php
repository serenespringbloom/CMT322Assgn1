<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    use HasFactory;

    protected $table = 'merchandise';

    protected $primaryKey = 'merch_id';

    protected $fillable = [
        'name',
        'size',
        'price',
        'stock_quantity',
        'event_id',
        'created_by',
        'last_updated_by',
    ];
}
