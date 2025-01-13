<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
 
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'ticket_category',
        'total_amount',
        'status'
    ];

    public function seats()
{
    return $this->hasMany(Seat::class);
}
}