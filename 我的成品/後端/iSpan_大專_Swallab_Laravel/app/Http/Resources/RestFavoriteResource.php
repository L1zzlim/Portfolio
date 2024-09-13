<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFavoriteResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => 'restFavorite',
            'restaurant' => [
                'name' => $this->RestInfos->Users->name,
                'avatar' => $this->RestInfos->Users->avatar,
                'address' => $this->RestInfos->address,
                'avg_price' => $this->RestInfos->avg_price,
                'wd_operating' => $this->RestInfos->wd_operating,
                'purposes' => $this->RestInfos->SuitableFor->map(function ($suitableFor) {
                    return $suitableFor->FiltPurposes->purpose;
                }),
                'avg_score' => $this->RestInfos->MemberReviews->avg('score'), 
            ],
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}

