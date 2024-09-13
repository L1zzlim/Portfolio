<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DateTime;

class NotesFavoriteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => optional($this)->id,
            'type' => 'noteFavorite',
            'note' => [
                'title' => optional(optional($this)->MemberNotes)->title,
                'main_photo' => optional(optional($this)->MemberNotes)->main_photo,
                'content' => optional(optional($this)->MemberNotes)->content,
                'visit_date' => optional(optional($this)->MemberNotes)->visited_at_datetime ? (new DateTime(optional($this)->MemberNotes->visited_at_datetime))->format('Y-m-d') : null,
                'count' => optional(optional($this)->MemberNotes)->count,
                'restaurant' => [
                    'name' => optional(optional(optional($this)->MemberNotes)->RestInfos)->Users ? optional($this->MemberNotes->RestInfos->Users)->name : null,
                    'address' => optional(optional($this->MemberNotes)->RestInfos)->address,
                    'avg_price' => optional(optional($this->MemberNotes)->RestInfos)->avg_price,
                    'wd_operating' => optional(optional($this->MemberNotes)->RestInfos)->wd_operating,
                ],
            ],
            'created_at' => optional(optional($this)->created_at)->toDateTimeString(),
        ];
    }
}