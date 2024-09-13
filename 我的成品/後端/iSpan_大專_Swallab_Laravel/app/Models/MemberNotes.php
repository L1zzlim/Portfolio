<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberNotes extends Model
{
    use HasFactory;
    public function Members()
    {
        return $this->belongsTo(Members::class, 'm_id', 'id');
    }
    public function RestInfos()
    {
        return $this->belongsTo(RestInfos::class, 'r_id', 'id');
    }
    public function NotesComments()
    {
        return $this->hasMany(NotesComments::class, 'm_n_id', 'id');
    }
    public function NotesFavorites(){
        return $this->hasMany(NotesFavorites::class,'m_n_id','id');
    }
    protected $table = 'MemberNotes';
    protected $fillable = [
        'title',
        'main_photo',
        'per_cost',
        'content',
        'visited_date',
        'count',
    ];

}
