<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderInfos extends Model
{
    use HasFactory;
    protected $table = 'OrderInfos';
    protected $fillable = [
        'booking_date',
        'booking_time',
        'utensils',
    ];
    public function Members()
    {
        return $this->belongsTo(Members::class, 'm_id', 'id');
    }
    public function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'id');
    }
    public function OrderStatuses()
    {
        return $this->belongsTo(OrderStatuses::class, 'o_s_id', 'id');
    }
    public function OrderDetails()
    {
        return $this->hasMany(OrderDetails::class , 'o_id', 'id');
    }
}
