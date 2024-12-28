<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $table = 'seats';

    protected $primaryKey = ['seat_id', 'event_id']; // Composite Key

    public $incrementing = false;

    protected $fillable = [
        'event_id',
        'is_booked',
        'ticket_id',
        'enabled_flag',
        'disabled_by',
        'disabled_at',
    ];
}
