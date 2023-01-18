<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'card_number' => $this->card_number,
            'expiry_date' => $this->expiry_date,
            'cvv' => $this->cvv,
            'card_notes' => $this->card_notes,
            'is_saved' => $this->is_saved == 1 ? true : false,
            'created_at' => $this->created_at
        ];
    }
}
