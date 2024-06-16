<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'access', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_profile')->withTimestamps();
    }
}
