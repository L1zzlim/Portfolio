<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuitableFor extends Model
{
    use HasFactory;
    protected $table = 'SuitableFor';
    function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'id');
    }
    function FiltPurposes()
    {
        return $this->belongsTo(FiltPurposes::class, 'f_p_id', 'id');
    }
}
