<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;

class Userint extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'db_centros_mac.users';

    protected $fillable = [
        'name', 
        'email', 
        'idpersonal',
        'password',
        'idcentro_mac',
        'flag',
    ];
}
