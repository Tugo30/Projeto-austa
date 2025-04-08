<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // atributes that are hidden for serialzation
    protected $hidden = [
        'password',
        'token'
    ];
}
