<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOrderResource extends JsonResource
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
            'category' => $this->category,
            'product' => $this->product,
            'product_specification' => $this->product_specification == 1 ? true : false,
            'brand' => $this->brand,
            'capacity' => $this->capacity,
            'color' => $this->color,
            'price' => $this->product_price,
            'delivery' => $this->delivery == 1 ? true : false,
            'delivery_date' => $this->delivery_date,
            'delivery_start_time' => $this->delivery_start_time,
            'delivery_end_time' => $this->delivery_end_time,
            'pickup' => $this->pickup == 1 ? true : false,
            'pickup_date' => $this->pickup_date,
            'pickup_start_time' => $this->pickup_start_time,
            'pickup_end_time' => $this->pickup_end_time,
            'order_notes' => $this->order_notes,
            'shipping' => !empty($this->shipping) ? new ShippingAddressResource($this->shipping) : [],
            'payment' => !empty($this->payment) ? new PaymentResource($this->payment) : [],
            'created_at' => $this->created_at
        ];
    }
}
