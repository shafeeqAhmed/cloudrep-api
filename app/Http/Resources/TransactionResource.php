<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'payable_type' => $this->payable_type,
            'payable_d' => $this->payable_id,
            'wallet_id' => $this->wallet_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'confirmed' => $this->confirmed,
            'meta' => $this->meta,
            'created_at' => $this->created_at
        ];
    }
}
