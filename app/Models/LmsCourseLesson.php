<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Str;

class LmsCourseLesson extends Model
{
    use HasFactory;

    // public static function boot()
    // {
    //     parent::boot();
    //     self::creating(function ($model) {
    //         $model->lms_course_lesson_uuid = Str::uuid()->toString();
    //     });
    // }
}
