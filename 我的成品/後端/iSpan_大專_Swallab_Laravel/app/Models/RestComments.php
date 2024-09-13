<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestComments extends Model
{
    use HasFactory;
    protected $table = 'RestComments';
    protected $fillable = [
        'content',
    ];
    public function Members()
    {
        return $this->belongsTo(Members::class, 'm_id', 'id');
    }
    public function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'id');
    }
}
