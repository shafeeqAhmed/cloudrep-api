<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressResource extends JsonResource
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
            'shipping_address' => $this->shipping_address,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'shipping_notes' => $this->shipping_notes,
            'is_saved' => $this->is_saved == 1 ? true : false,
            // 'customer' => new CustomerInformationResource($request->customer)
        ];
    }
}
