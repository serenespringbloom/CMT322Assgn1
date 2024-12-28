<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchTransactionItem extends Model
{
    use HasFactory;

    protected $table = 'merch_transaction_items';

    protected $primaryKey = 'items_id';

    protected $fillable = [
        'transaction_id',
        'merch_id',
        'quantity',
        'price',
    ];
}
