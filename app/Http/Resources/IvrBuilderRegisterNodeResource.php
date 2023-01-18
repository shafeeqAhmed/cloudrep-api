<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IvrBuilderRegisterNodeResource extends JsonResource
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
            'parent_uuid' => $request->parent_uuid,
            'node_type' => $this->resource->node_type,
            'parent_type' => $this->resource->parent_type,
            'type' => $this->getType(),
            'filters' => $this->getFilters()
        ];
    }

    public function getFilters()
    {
        if ($this->resource->filters) {
            return  RouterNodeFilterResource::collection($this->resource->filters);
        }
        return [];
    }
    public function getType()
    {
        if ($this->resource->parent_type == 'router' && $this->resource->nodeParentFilter) {
            return $this->resource->nodeParentFilter->priority;
        } else {
            return $this->resource->type;
        }
    }
}
