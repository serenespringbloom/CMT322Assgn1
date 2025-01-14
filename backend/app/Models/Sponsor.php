<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $table = 'sponsors';

    protected $primaryKey = 'sponsor_id';

    protected $fillable = [
        'event_id',
        'sponsor_logo',
        'created_by',
        'last_updated_by',
    ];

    // Accessor to return the full URL for sponsor_logo
    public function getSponsorLogoAttribute($value)
    {
        // Generate the full URL for the sponsor logo based on the new directory
        return $value ? url('images/' . $value) : null;
    }

}