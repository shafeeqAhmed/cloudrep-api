<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;
use App\Models\DropDown;

class PublisherProfileItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_website',
        'belongs_to',
        'user_id',
    ];

    public function dropdown()
    {
        return $this->belongsTo(DropDown::class);
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
        return PublisherProfileItem::whereUuid($uuid)->value('id');
    }
}
