<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerInformationResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'customer_type' => $this->customer_type,
            'customer_notes' => $this->customer_notes,
            'orders' => !empty($this->productOrders) ? ProductOrderResource::collection($this->productOrders) : [],
        ];
    }
}
