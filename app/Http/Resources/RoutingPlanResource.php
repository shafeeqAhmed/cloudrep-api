<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoutingPlanResource extends JsonResource
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
            'priority' => $this->priority,
            'weight' => $this->weight,
            'name' => $this->name,
            'duplicate_conversation_type' => $this->duplicate_conversation_type,
            'revenue' => $this->revenue,
            'status' => $this->status,
            'convert_on'=> $this->convert_on,
            'created_at' => $this->created_at
        ];
    }
}
