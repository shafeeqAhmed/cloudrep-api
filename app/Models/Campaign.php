<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\support\Str;
use Illuminate\Http\Request;

class Campaign extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'step',
        'campaign_name',
        'name',
        'client_id',
        'name',
        'phone_no',
        'title',
        'email',
        'address',
        'country',
        'state',
        'city',
        'zipcode',
        'is_published',
        'category_id',
        'vertical_id',
        'language',
        'currency',
        'time_zone',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'description',
        'website_url',
        'deeplink',
        'blog_url',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'cost_per_call',
        'client_per_call_duration',
        'payout_per_call',
        'campaign_rate',
        'air_time',
        'paid_air_time_by',
        'air_time_price',
        'recording',
        'call_recording_price',
        'transcripts',
        'transcript_price',
        'call_storage',
        'call_storage_price',
        'client_image',
        'agent_image',
        'publisher_image',
        'routing',
        'ivr_id'
    ];

    //start relationship =======================================================
    public function category()
    {
        return $this->belongsTo(BussinesCategory::class, 'category_id', 'id');
    }

    public function vertical()
    {
        return $this->belongsTo(CompanyVertical::class, 'vertical_id', 'id');
    }

    public function campaignLocations()
    {
        return $this->hasMany(CampaignGeoLocation::class, 'campaign_id', 'id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function campaignLms()
    {
        return $this->hasMany(CampaignLms::class, 'campaign_id', 'id');
    }
    public function targets()
    {
        return $this->hasMany(TargetListing::class, 'campaign_id', 'id');
    }

    public function twillioNumbers()
    {
        return $this->hasMany(TwillioNumber::class, 'campaign_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function agentPayoutSetting()
    {
        return $this->belongsTo(CampaignAgentPayoutSetting::class, 'id', 'campaign_id');
    }
    public function publisherPayoutSetting()
    {
        return $this->belongsTo(CampaignPublisherPayoutSetting::class, 'id', 'campaign_id');
    }

    //end relationship =======================================================
    public static function updateRecord($col, $val, $data)
    {
        return self::where($col, $val)->update($data);
    }
    public static function getRecord($col, $val)
    {
        return self::where($col, $val)->first();
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }

    public static function getCampaignByUuid($col, $val)
    {
        $record =  Campaign::where($col, $val)->first();
        return $record;
    }

    public static function getIdByUuid($uuid)
    {
        return Campaign::where('uuid', $uuid)->value('id');
    }
    public static function getPublisher()
    {
        $publishers = TwillioNumber::join('users', 'users.id', '=', 'twillio_numbers.publisher_id')
            ->join('campaigns', 'campaigns.id', '=', 'twillio_numbers.campaign_id')
            ->when(request('q'), function ($query, $q) {
                return $query->where('users.name', 'LIKE', "%{$q}%");
            })
            ->when(request('soryBy'), function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when(request('uuid'), function ($query, $uuid) {
                return  $query->where('campaign_id', Campaign::getIdByUuid($uuid));
            })
            ->when(request('page'), function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->select('twillio_numbers.status', 'twillio_numbers.status', 'users.name as publisher_name', 'twillio_numbers.number', 'campaigns.campaign_name', 'users.user_uuid as publisher_uuid')->orderBy('users.id', 'DESC')->paginate(request('perPage'));

        return $publishers;
    }
    public static function updateAddressTypeOfCampaign()
    {
        // $data['addressType'] = request('address_type');
        // $data['step'] = request('step');
        // $campaign_id = self::getIdByUuid(request('campaign_uuid'));
        // return self::where('id', $campaign_id)->update($data);
        $campaign =  self::where('uuid', request('campaign_uuid'))->first();
        $campaign->addressType = request('address_type');
        if (request('step') > $campaign->step) {
            $campaign->step = request('step');
        }
        $campaign->update();
        return $campaign;
    }
    public static function assignIvrToCampaign()
    {
        $data['ivr_id'] = Ivr::getIdByUuid(request('ivr_uuid'));
        // $data['routing'] = 'ivr';
        // $data['step'] = 13;
        $campaign_id = self::getIdByUuid(request('campaign_uuid'));
        return self::where('id', $campaign_id)->update($data);

        // $ivr_id = Ivr::getIdByUuid(request('ivr_uuid'));
        // $campaign =  self::where('uuid', request('campaign_uuid'))->first();
        // // $campaign->routing = request('routing_type');
        // $campaign->ivr_id = $ivr_id;
        // if(request('step') > $campaign->step){
        //     $campaign->step = request('step');
        // }
        // $campaign->update();
        // return $campaign;
    }
    public static function updateRoutingTypeOfCampaign()
    {
        // $data['routing'] = request('routing_type');
        // $data['step'] = 13;
        // $campaign_id = self::getIdByUuid(request('campaign_uuid'));
        // return self::where('id', $campaign_id)->update($data);

        $campaign =  self::where('uuid', request('campaign_uuid'))->first();
        $campaign->routing = request('routing_type');
        if (request('step') > $campaign->step) {
            $campaign->step = request('step');
        }
        $campaign->update();
        return $campaign;
    }
}
