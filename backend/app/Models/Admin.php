<?php
// app/Models/Admin.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';
    protected $primaryKey = 'admin_id';
    
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'remember_token'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Override the password field name
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}