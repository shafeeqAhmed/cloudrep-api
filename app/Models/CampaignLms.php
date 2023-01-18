<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;

class CampaignLms extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'campaign_lms';

    protected $fillable = [
        'type',
        'category_id',
        'course_id',
        'campaign_id'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(LmsCourse::class, 'course_id', 'id');
    }

    public static function getIdByUuid($uuid) {
        return CampaignLms::whereUuid($uuid)->value('id');
    }
}
