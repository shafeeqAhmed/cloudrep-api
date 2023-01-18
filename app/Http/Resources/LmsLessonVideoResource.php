<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;

class LmsLessonVideoResource extends JsonResource
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
            'id' => $this->resource->id,
            'uuid' => $this->resource->lms_lesson_video_uuid,
            'lesson_id' => $this->resource->lesson_id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'duration' => $this->convertSeconds($this->resource->duration),
            'video_url' => $this->resource->video_url,
            'video_thumbnail' => $this->resource->video_thumbnail,
            'is_free' => $this->is_free == 1 ? true : false,
            'created_at' => $this->created_at,
        ];
    }
    public function convertSeconds($seconds)
    {
        if ($seconds) {
            return CarbonInterval::seconds($seconds)->cascade()->forHumans();
        } else {
            return "";
        }
    }
}
