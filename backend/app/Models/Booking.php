<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
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