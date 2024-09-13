<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberReviews extends Model
{
    public function Members()
    {
        return $this->belongsTo(Members::class , 'm_id', 'id');
    }
    public function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'r_id');
    }
    use HasFactory;
    protected $table = 'MemberReviews';
    protected $fillable = [
        'score',
    ];

}
