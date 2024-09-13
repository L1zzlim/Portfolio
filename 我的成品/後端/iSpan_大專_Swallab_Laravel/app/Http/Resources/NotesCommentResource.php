<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotesCommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => optional(optional($this))->id,
            'type' => 'noteComment',
            'content' => optional(optional($this))->content,
            'note' => [
                'title' => optional(optional($this)->MemberNotes)->title,
                'content' => optional(optional($this)->MemberNotes)->content,
                'main_photo' => optional(optional($this)->MemberNotes)->main_photo,
                'restaurant' => [
                    'name' => optional(optional($this)->MemberNotes)->RestInfos->Users->name,
                    'image' => optional(optional($this)->MemberNotes)->RestInfos->Users->avatar,
                    'address' => optional(optional($this)->MemberNotes)->RestInfos->address,
                    'avg_price' => optional(optional($this)->MemberNotes)->RestInfos->avg_price,
                    'wd_operating' => optional(optional($this)->MemberNotes)->RestInfos->wd_operating,
                ]
            ],
            'created_at' => optional($this)->created_at,
            'updated_at' => optional($this)->updated_at,
        ];
    }
}