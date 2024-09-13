<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Members extends Model
{
    // use SoftDeletes;

    use HasFactory;
    public function Users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function MemberNotes()
    {
        return $this->hasMany(MemberNotes::class, 'm_id', 'id');
    }
    public function MemberCreditCards()
    {
        return $this->hasMany(MemberCreditCards::class, 'm_id', 'id');
    }
    public function OrderInfos(){
        return $this->hasMany(OrderInfos::class,'m_id','id');
    }
    public function NotesComments(){
        return $this->hasMany(NotesComments::class,'m_id','id');
    }
    public function NotesFavorites(){
        return $this->hasMany(NotesFavorites::class,'m_id','id');
    }
    public function RestComments(){
        return $this->hasMany(RestComments::class,'m_id','id');
    }
    public function RestFavorites(){
        return $this->hasMany(RestFavorites::class,'m_id','id');
    }
    public function MemberReviews(){
        return $this->hasMany(MemberReviews::class,'m_id','id');
    }
    protected $fillable = [
        'sum_tracking',
        'sum_fans',
        'thr_link',
        'ins_link',
        'fb_link',
        'bio',
    ];
}
