<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotesFavorites extends Model
{
    use HasFactory;
    protected $table = 'NotesFavorites';
    public function Members()
    {
        return $this->belongsTo(Members::class, 'm_id', 'id');
    }
    public function MemberNotes()
    {
        return $this->belongsTo(MemberNotes::class, 'm_n_id', 'id');
    }
}
