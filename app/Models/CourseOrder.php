<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class CourseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'course_id',
        'campaign_id',
        'copon_id',
        'course_price',
        'course_quantity',
        'price_after_copon'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsTo(LmsCourse::class);
    }
    public function copon(){
        return $this->belongsTo(PromoCode::class);
    }
    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return CourseOrder::where('uuid', $uuid)->value('id');
    }
}
