<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;
use App\Services\TwilioService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\TwilioNumberTag;

class TwillioNumber extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'number_sid',
        'number',
        'country',
        'bill_card',
        'type',
        'name',
        'allocated',
        'renews',
        'last_call_date',
        'campaign_name',
        'campaign_id',
        'number_pool',
        'amount',
        'publisher_id',
        'status'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function allocated(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M, Y'),
        );
    }
    protected function renews(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d M, Y'),
        );
    }
    public function tags()
    {
        return $this->hasMany(TwilioNumberTag::class, 'twilio_number_id', 'id')->select('tag_name as key', 'tag_value as value', 'twilio_number_id');
    }
    public static function getIdByUuid($uuid)
    {
        return TwillioNumber::whereUuid($uuid)->value('id');
    }
    public static function getRecord($col, $val)
    {
        return self::where($col, $val)->first();
    }

    public static function getTwillioNumberByUuid($col, $val)
    {
        $record =  TwillioNumber::where($col, $val)->first();
        return $record;
    }

    public static function getTwilioNumber(Request $request)
    {
        // $role = 'publisher';
        $twillioNumber = TwillioNumber::leftjoin('users', 'users.id', '=', 'twillio_numbers.publisher_id')
            ->leftJoin('campaigns', 'campaigns.id', '=', 'twillio_numbers.campaign_id')
            ->when($request->q, function ($query, $q) {
                return $query->where('name', 'LIKE', "%{$q}%");
            })
            ->when($request->role == 'publisher', function ($query) {
                return $query->where('publisher_id', request()->user()->id);
            })
            ->when($request->soryBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->with('tags')
            ->select('twillio_numbers.*', 'campaigns.campaign_name', 'users.user_uuid as publisher_uuid', 'users.name as publisher_name')->orderBy('id', 'DESC')->paginate($request->perPage);

        return $twillioNumber;
    }

    public static function buyAndStoreTwilioNumber($numbers, $data)
    {
        $record = null;
        if (!empty($numbers)) {
            $number = (new TwilioService())->buyTwilioNumber($numbers[0]['phoneNumber']);
            $data['uuid'] = generateUuid();
            $data['number_sid'] = $number->sid;
            $data['number'] = $number->phoneNumber;
            $data['allocated'] = Carbon::parse($number->dateCreated)->format('Y-m-d');
            $data['renews'] =  Carbon::parse($number->dateCreated)->addMonth()->format('Y-m-d');
            // $data['status'] = $number->boolean('status');
            // $data['bill_card'] = $number->boolean('bill_card');

            if (request('publisher_uuid')) {
                $data['publisher_id'] = User::getIdByUuid(request('publisher_uuid'));
            }
            $record = self::create($data);
        }
        return $record;
    }
    public static function getNumberDetails($number)
    {
        return  self::where('number', $number)
            ->join('campaigns as c', 'c.id', '=', 'twillio_numbers.campaign_id')
            ->join('users as u', 'u.id', '=', 'twillio_numbers.publisher_id')
            ->leftJoin('ivrs', 'ivrs.id', '=', 'c.ivr_id')
            ->leftJoin('users as client', 'client.id', '=', 'c.user_id')
            ->select('u.id as publisher_id', 'client.id as client_id', 'c.id as campaign_id', 'c.routing', 'c.ivr_id')
            ->first();
    }
    public static function getDetail($number)
    {
        // $record = self::where('number', $number)
        //     ->join('campaigns as c', 'c.id', '=', 'twillio_numbers.campaign_id')
        //     ->join('users as u', 'u.id', '=', 'twillio_numbers.publisher_id')
        //     ->leftJoin('ivrs', 'ivrs.id', '=', 'c.ivr_id')
        //     ->leftJoin('users as client', 'client.id', '=', 'c.user_id')
        //     ->select('u.id as publisher_id', 'client.id as client_id', 'c.id as campaign_id', 'c.routing', 'c.ivr_id')
        //     ->first();
        // $data['records']  = $record;

        // $targets = TargetListing::where('campaign_id', $record->campaign_id)
        //     ->where('client_id', $record->client_id)
        //     ->where('status', 'active')
        //     ->select('destination', 'uuid', 'is_primary', 'type', 'id')
        //     ->orderBy('is_primary', 'desc')
        //     ->get();

        // $data['targets'] = $targets;

        // $data['routing_plan']  = RoutingPlan::whereIn('target_id', $targets->pluck('id'))
        // ->where('routing_plans.status', 'inactive')

        // ->join('target_listings as tl', 'tl.id', '=', 'routing_plans.target_id')
        // ->select(
        //     'routing_plans.uuid',
        //     'routing_plans.priority',
        //     'routing_plans.weight',
        //     'routing_plans.revenue',
        //     'routing_plans.duplicate_conversation_type',
        //     'routing_plans.convert_on',
        //     'tl.destination'
        // )
        //     ->orderBy('routing_plans.priority', 'asc')
        //     ->get();
        // return $data;


        $record = self::where('number', $number)
            ->join('campaigns as c', 'c.id', '=', 'twillio_numbers.campaign_id')
            ->join('users as u', 'u.id', '=', 'twillio_numbers.publisher_id')
            ->leftJoin('ivrs', 'ivrs.id', '=', 'c.ivr_id')
            ->leftJoin('users as client', 'client.id', '=', 'c.user_id')
            ->select('u.id as publisher_id', 'client.id as client_id', 'c.id as campaign_id', 'c.routing', 'c.ivr_id')
            ->first();


        // $data['records']  = $record;

        $targets = TargetListing::where('campaign_id', $record->campaign_id)
            ->where('client_id', $record->client_id)
            ->where('status', 'active')
            ->select('destination', 'uuid', 'is_primary', 'type', 'id')
            ->orderBy('is_primary', 'desc')
            ->get();

        $routingPlans  = RoutingPlan::whereIn('target_id', $targets->pluck('id'))
            ->where('routing_plans.campaign_id', $record->campaign_id)
            ->where('routing_plans.status', 'inactive')

            ->join('target_listings as tl', 'tl.id', '=', 'routing_plans.target_id')
            ->select(
                'tl.id',
                'routing_plans.uuid',
                'routing_plans.priority',
                'routing_plans.weight',
                'routing_plans.revenue',
                'routing_plans.duplicate_conversation_type',
                'routing_plans.convert_on',
                'tl.destination'
            )
            ->orderBy('routing_plans.priority', 'asc')
            ->get();

        return count($routingPlans) ? $routingPlans : $targets;
    }
}
