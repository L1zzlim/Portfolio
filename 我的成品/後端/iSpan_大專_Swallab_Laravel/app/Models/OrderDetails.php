<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $table = 'OrderDetails';
    protected $fillable = [
        'item_name',
        'item_price',
        'item_qty',
    ];
    public function OrderInfos()
    {
        return $this->belongsTo(OrderInfos::class, 'o_id', 'id');
    }
}
