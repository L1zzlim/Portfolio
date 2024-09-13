<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class FiltPurposes extends Model
{
use HasFactory;
public function SuitableFor()
{
    return $this->hasMany(SuitableFor::class, 'f_p_id', 'id');
}
    protected $table = 'FiltPurposes';
    protected $fillable = [
        'purpose',
    ];
}