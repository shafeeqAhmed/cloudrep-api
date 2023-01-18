<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\support\Str;

class LmsCourse extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $dateFormat = 'Y-m-d';

    public function categories()
    {
        return $this->belongsToMany(LmsCategory::class, 'lms_course_categories', 'course_id', 'category_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function lessons()
    {
        return $this->hasMany(LmsLesson::class, 'course_id', 'id');
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->lms_course_uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return LmsCourse::where('lms_course_uuid', $uuid)->value('id');
    }

    public function getCreatedAtAttribute()
    {
        // return (int) $value->format('d/m/Y');
        return date('m/d/Y', strtotime($this->attributes['created_at']));
    }

    public function videos()
    {
        return $this->hasManyThrough(LmsLessonVideo::class, LmsLesson::class, 'course_id', 'lesson_id', 'id', 'id');
    }
    public function scopeDurations($query, $course_id)
    {
        return $query->join('lms_lessons as ls', 'ls.course_id', '=', 'lms_courses.id')
            ->join('lms_lesson_videos as llv', 'llv.lesson_id', '=', 'ls.id')
            ->select('lms_courses.id as course_id', 'ls.id as lesson_id', 'llv.id as lms_lesson_video_id')
            ->where('ls.deleted_at', null)
            ->where('lms_courses.id', $course_id)
            ->sum('llv.duration');
    }
}
