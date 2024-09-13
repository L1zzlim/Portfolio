<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class FiltClasses extends Model
{
    use HasFactory;
    public function RestInfos()
    {
        return $this->hasMany(RestInfos::class, 'r_c_id', 'id');
    }
    protected $table = 'FiltClasses';
    protected $fillable = [
        'restclass',
    ];
    // 時間戳是否啟用
    // public $timestamps = true;


}