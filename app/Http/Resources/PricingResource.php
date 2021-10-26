<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'pricing_id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'label' => $this->label,
            // 'description' => $this->description,
            'price' => $this->price,
            'list_price' => $this->list_price,
            'coin' => $this->coin,
            'gift_coin' => $this->gift_coin,
            'days' => $this->days,
            'gift_days' => $this->gift_days,
            'target' => $this->target,
        ];
    }
}
