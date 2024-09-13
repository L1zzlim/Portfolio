<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class FiltLocations extends Model
{
    use HasFactory;
    public function RestInfos()
    {
        return $this->hasMany(RestInfos::class, 'f_l_id', 'id');
    }
    protected $table = 'FiltLocations';
    protected $fillable = [
        'location',
    ];

}