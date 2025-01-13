<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'personal_access_tokens';

    // Define the fillable attributes
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    // Cast attributes to their respective data types
    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Define the relationship to the tokenable model (polymorphic).
     */
    public function tokenable()
    {
        return $this->morphTo();
    }
}
