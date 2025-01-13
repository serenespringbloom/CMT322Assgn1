<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $primaryKey = 'seat_id'; // If your primary key is seat_id instead of id
    public $timestamps = false;
    protected $fillable = [
        'seat_number',
        'is_booked',
        'seat_id',
        'transaction_id',
    ];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // public function bookings()
    // {
    //     return $this->belongsTo(Booking::class);
    // }
}
