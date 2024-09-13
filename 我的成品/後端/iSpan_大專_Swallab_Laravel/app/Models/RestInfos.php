<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestInfos extends Model
{
    // use SoftDeletes;

    use HasFactory;

    protected $table = 'RestInfos';
    protected $fillable = [
        'href',
        'avg_price',
        'address',
        'status',
        'weekday',
        'weekend',
        'wd_operating',
        'wd_close_1',
        'wd_close_2',
        'we_operating',
        'we_close_1',
        'we_close_2',
    ];
    public function Users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function FiltClasses()
    {
        return $this->belongsTo(FiltClasses::class, 'f_c_id', 'id');
    }
    public function FiltLocations()
    {
        return $this->belongsTo(FiltLocations::class, 'f_l_id', 'id');
    }
    public function OrderInfos()
    {
        return $this->hasMany(OrderInfos::class,'r_id','id');
    }
    public function RestItems()
    {
        return $this->hasMany(RestItems::class,'r_id','id');
    }
    public function MemberNotes()
    {
        return $this->hasMany(MemberNotes::class,'r_id','id');
    }
    public function MemberReviews()
    {
        return $this->hasMany(MemberReviews::class,'r_id','id');
    }
    public function RestFavorites(){
        return $this->hasMany(RestFavorites::class,'r_id','id');
    }
    public function RestComments(){
        return $this->hasMany(RestComments::class,'r_id','id');
    }
    public function SuitableFor(){
        return $this->hasMany(SuitableFor::class,'r_id','id');
    }
}
