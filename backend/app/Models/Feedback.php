<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'ticket_id',
        'name',
        'programme',
        'feedback_text',
        'rating',
    ];

    // Disable timestamps
    public $timestamps = false;
}
