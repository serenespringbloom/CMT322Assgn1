<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'seat_number',
        'is_booked',
       
        'booking_id'
    ];
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}