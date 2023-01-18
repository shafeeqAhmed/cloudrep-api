<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class LmsLessonVideo extends Model
{
    use HasFactory;
    use SoftDeletes;
    // public function lesson() {
    //     return $this->belongsTo(LmsLesson::class);
    // }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->lms_lesson_video_uuid = Str::uuid()->toString();
        });
    }
}
