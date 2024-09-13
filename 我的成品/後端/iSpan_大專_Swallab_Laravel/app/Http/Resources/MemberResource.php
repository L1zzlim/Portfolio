<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => optional($this)->id,
            'user_id' => optional($this)->user_id,
            'sum_tracking' => optional($this)->sum_tracking,
            'sum_fans' => optional($this)->sum_fans,
            'thr_link' => optional($this)->thr_link,
            'ins_link' => optional($this)->ins_link,
            'fb_link' => optional($this)->fb_link,
            'bio' => optional($this)->bio,
            'user' => [
                'name' => optional(optional($this)->users)->name,
                'email' => optional(optional($this)->users)->email,
                'avatar' => optional(optional($this)->users)->avatar,
            ],
            'created_at' => optional($this)->created_at,
            'updated_at' => optional($this)->updated_at,
        ];
    }
}