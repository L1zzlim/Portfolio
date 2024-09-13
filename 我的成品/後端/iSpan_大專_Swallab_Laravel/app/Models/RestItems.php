<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestItems extends Model
{
    use HasFactory;
    protected $table = 'RestItems';
    protected $fillable = [
        'item_name',
        'item_price',
        'item_photo',
    ];
    public function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'id');
    }
    public function FiltSectionDemos()
    {
        return $this->belongsTo(FiltSectionDemos::class, 'f_s_d_id', 'id');
    }
    public function RestDiscount()
    {
        return $this->belongsTo(RestDiscount::class,'r_i_id', 'id');
    }
}
