<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CampaignEnrollment extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'campaign_id', 'client_id', 'publisher_id', 'publisher_DID', 'status', 'start_date_time', 'end_date_time', 'publisher_timezone', 'client_timezone'
    ];

    public static function boot(){
        
        parent::boot();
        self::creating( function ($model){
            $model->uuid=Str::uuid()->toString();
      
       });

    }

    public static function getIdByUuid($uuid)
    {
        return self::where('uuid', $uuid)->value('id');
    }

}
