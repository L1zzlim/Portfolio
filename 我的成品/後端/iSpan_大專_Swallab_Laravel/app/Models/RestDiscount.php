<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestDiscount extends Model
{
    use HasFactory;
    public function RestItems()
    {
        return $this->hasMany(RestItems::class, 'r_d_id', 'id');
    }
    protected $table = 'RestDiscount';
    protected $fillable = [
        'discount_price',
        'start_time',
    ];
}
