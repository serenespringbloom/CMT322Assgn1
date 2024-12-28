<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_title',
        'event_subtitle',
        'event_venue',
        'event_datetime',
        'event_about',
        'event_email',
        'event_phone',
        'created_by',
        'last_updated_by',
    ];
}
