<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCreditCards extends Model
{
    use HasFactory;

    public function Members()
    {
        return $this->belongsTo(Members::class, 'm_id', 'id');
    }
    protected $table = 'MemberCreditCards';
    protected $fillable = [
        'infos',
    ];
}
