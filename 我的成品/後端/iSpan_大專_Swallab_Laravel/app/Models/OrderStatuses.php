<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{
    use HasFactory;
    public function OrderInfos()
    {
        return $this->hasMany(OrderInfos::class, 'o_s_id', 'id');
    }
    protected $table = 'OrderStatuses';
    protected $fillable = [
        'status',
    ];
}
