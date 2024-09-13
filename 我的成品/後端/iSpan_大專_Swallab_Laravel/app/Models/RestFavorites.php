<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestFavorites extends Model
{
    use HasFactory;
    protected $table = 'RestFavorites';
    public function Members()
    {
        return $this->belongsTo(Members::class, 'm_id', 'id');
    }
    public function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'id');
    }
}
