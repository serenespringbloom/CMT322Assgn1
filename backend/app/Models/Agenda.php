<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agendas';

    protected $primaryKey = 'agenda_id';

    protected $fillable = [
        'event_id',
        'time_start',
        'time_end',
        'agenda_title',
        'agenda_description',
        'created_by',
        'last_updated_by',
    ];
}
