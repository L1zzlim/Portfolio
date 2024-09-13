<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
use HasFactory;
public function Members(){
    return $this->hasMany(Members::class, 'user_id', 'id');
}
public function RestInfos(){
    return $this->hasMany(RestInfos::class, 'user_id', 'id');
}
    protected $fillable = [
        'role',
        'email',
        'password',
        'name',
        'avatar',
        'phone',
    ];
}