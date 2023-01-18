<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class TwilioNumberTag extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'tag_name',
        'tag_value',
        'twilio_number_id',
    ];

    public function twilioNumber() {
        return $this->belongsTo(TwillioNumber::class);
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
        return TwilioNumberTag::whereUuid($uuid)->value('id');
    }

    public static function getTwilioNumberTagByUuid($col, $val)
    {
        $record =  TwilioNumberTag::where($col, $val)->first();
        return $record;
    }

    public static function getTwilioNumberTag(Request $request) {
        
        $twilioNumberTag = TwilioNumberTag::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->soryBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->orderBy('id', 'DESC')->paginate($request->perPage);

        return $twilioNumberTag;
    }

}
