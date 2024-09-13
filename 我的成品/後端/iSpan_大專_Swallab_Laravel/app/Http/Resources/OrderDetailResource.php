<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => optional($this)->id,
            'item_name' => optional($this)->item_name,
            'item_price' => optional($this)->item_price,
            'item_qty' => optional($this)->item_qty,
            'created_at' => optional(optional($this)->created_at)->toDateTimeString(),
            'updated_at' => optional(optional($this)->updated_at)->toDateTimeString(),
        ];
    }
}