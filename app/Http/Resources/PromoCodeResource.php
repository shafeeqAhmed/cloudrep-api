<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'amount' => $this->amount,
            'is_applied' => $this->is_applied == 1 ? true : false,
            'start_date' => $this->start_date,
            'type' => $this->type,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at
        ];
    }
}
