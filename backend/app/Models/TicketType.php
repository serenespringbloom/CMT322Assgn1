<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $table = 'ticket_types';


    protected $fillable = [
        'ticket_type_desc',
        'ticket_type_price',
        'created_by',
        'last_updated_by',
    ];
}
