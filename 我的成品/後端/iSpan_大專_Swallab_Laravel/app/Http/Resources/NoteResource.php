<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => optional($this)->id,
            'title' => optional($this)->title,
            'content' => optional($this)->content,
            'main_photo' => optional($this)->main_photo,
            'restaurant' => new RestaurantResource(optional($this)->restInfo),
            'member' => new MemberResource(optional($this)->member),
            'comments' => NotesCommentResource::collection(optional($this)->notesComments),
            'favorites' => NotesFavoriteResource::collection(optional($this)->notesFavorites),
            'visited_date' => optional($this)->visited_date,
            'created_at' => optional(optional($this)->created_at)->toDateTimeString(),
            'updated_at' => optional(optional($this)->updated_at)->toDateTimeString(),
        ];
    }
}