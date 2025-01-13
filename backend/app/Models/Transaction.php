<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
 
    use HasFactory;

    protected $fillable = [
        'cust_name',
        'cust_email',
        'cust_phone',
        'ticket_category',
        'total_price',
        'status',
    ];

    public function seats():HasMany
{
    return $this->hasMany(Seat::class);
}
}