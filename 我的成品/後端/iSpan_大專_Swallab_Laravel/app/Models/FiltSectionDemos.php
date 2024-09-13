<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiltSectionDemos extends Model
{
    use HasFactory;
    public function RestItems()
    {
        return $this->hasMany(RestItems::class, 'f_s_d_id', 'id');
    }
    protected $table = 'FiltSectionDemos';

    protected $fillable = [
        'section',
    ];
}
