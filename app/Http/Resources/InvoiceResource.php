<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'uuid' => $this->resource->uuid,
            'invoice_number' => $this->resource->invoice_number,
            'date' => Carbon::parse($this->date)->format('Y-m-d'),
            'terms' => $this->resource->terms,
            'due_date' => Carbon::parse($this->resource->due_date)->format('Y-m-d'),
            'description' => $this->resource->description,
            'rate' => $this->resource->rate,
            'quantity' => $this->resource->quantity,
            'amount' => $this->resource->amount,
            'tax' => $this->resource->tax,
            'discount' => $this->resource->discount,
            'additional_detail' => $this->resource->additional_detail,
            'note' => $this->resource->note,
            'user_id' => $this->resource->user_id,
            'user' => new UserResource($this->user),
            'order' => new CourseOrderResource($this->order),
            'order_id' => $this->resource->order_id,
            'campaign_id' => $this->resource->campaign_id
        ];
    }
}
