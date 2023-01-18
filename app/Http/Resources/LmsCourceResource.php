<?php

namespace App\Http\Resources;

use App\Models\LmsCategory;
use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;

class LmsCourceResource extends JsonResource
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
            'uuid' => $this->resource->lms_course_uuid,
            'title' => $this->resource->title,
            'tag_line' => $this->resource->tag_line,
            'description' => $this->resource->description,
            'price' => $this->resource->price,
            'course_duration' => $this->convertSeconds($this->resource->durations($this->resource->id)),
            'is_copon_applied' => $this->is_copon_applied == 1 ? true : false,
            'videos_count' => $this->resource->videos_count ?? '',
            'lessons_count' => $this->resource->lessons_count ?? '',
            'lesson_count' => count($this->resource->lessons),
            'thumbnail' => !empty($this->resource->course_image) ? url($this->resource->course_image) : '',
            'categories' => LmsCategoryResource::collection($this->categories),
            'lessons' => LmsLessonResource::collection($this->resource->lessons),
            'created_at' => $this->created_at,
        ];
    }
    public function convertSeconds($seconds) {
        if(!empty($seconds)) {
            return CarbonInterval::seconds($seconds)->cascade()->forHumans();
        } else {
            return "";
        }

    }
}
