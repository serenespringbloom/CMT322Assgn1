<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $table = 'admins'; // Explicitly define the table
    protected $primaryKey = 'admin_id'; // Primary key
    public $timestamps = true;

    protected $fillable = [
        'username',
        'email',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];
}
