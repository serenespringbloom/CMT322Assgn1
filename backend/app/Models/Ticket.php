<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'event_id',
        'seat_id',
        'transaction_id',
        'ticket_type_id',
    ];
}
