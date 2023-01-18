<?php

namespace App\Http\Resources;

use App\Models\TagOperators;
use App\Models\States;

use Illuminate\Http\Resources\Json\JsonResource;

class IvrNodeFiltersResource extends JsonResource
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
            'tag_uuid' => $this->resource->tag->uuid,
            'type' => $this->resource->type,
            'tag_operator_uuid' => $this->resource->tag_operator->uuid,
            'tag_operator_value' => $this->resource->filter_condition_values->pluck('tag_operator_value'),
            'operators' => TagOperators::where('tag_id', $this->resource->tag_id)->get(['name', 'uuid']),
            'states' => $this->resource->tag->value === 'state' ?  States::select('name')->get() : null
        ];
    }
}
