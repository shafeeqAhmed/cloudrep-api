<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;
use App\Models\RoutingPlan;
use App\Models\User;

class TargetListing extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'destination',
        'client_id',
        'campaign_id',
        'routing_id',
        'status',
        'is_primary'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return TargetListing::whereUuid($uuid)->value('id');
    }

    public static function getTargetListingByUuid($col, $val)
    {
        $record =  self::where($col, $val)
            ->join('users as c', 'c.id', '=', 'target_listings.client_id')
            ->select(
                'c.user_uuid as client_uuid',
                'target_listings.uuid as target_uuid',
                'target_listings.name',
                'target_listings.destination',
                'target_listings.uuid',
                'target_listings.is_primary',
                'target_listings.status'
            )
            ->first();
        return $record;
    }

    public static function getTargetListing(Request $request)
    {
        $targetListing = TargetListing::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })->when($request->soryBy, function ($query, $sortBy) {
            return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
        })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->when($request->uuid, function ($query, $uuid) {
                return $query->where('routing_id', Routing::getIdByUuid($uuid));
            })
            ->when($request->campaign_uuid, function ($query, $campaign_uuid) {
                return $query->where('campaign_id', Campaign::getIdByUuid($campaign_uuid));
            })
            ->whereDoesntHave('routingPlan')->orderBy('id', 'DESC')->paginate($request->perPage);

        return $targetListing;
    }

    public function routingPlan()
    {
        return  $this->belongsTo(RoutingPlan::class, 'id', 'target_id');
    }

    public function client()
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public static function storeTarget()
    {
        $data = request()->all();
        $data['status'] = request('status') ? request('status') : 'inactive';
        $data['is_primary'] = request('is_primary') ?  request()->boolean('is_primary') : false;
        $data['client_id'] =  User::getIdByUuid(request('client_uuid'));
        $data['campaign_id'] =  Campaign::getIdByUuid(request('campaign_uuid'));
        if (request()->has('route_uuid')) {
            $data['routing_id'] = Routing::getIdByUuid(request('route_uuid'));
        }
        if ($data['is_primary']) {
            self::where('client_id', $data['client_id'])->where('campaign_id', $data['campaign_id'])->update(['is_primary' => 0]);
        }
        return self::create($data);
    }

    public function updateTarget()
    {

        $data = request()->all();
        if (request()->has('status'))
            $data['status'] = request('status');
        if (request('is_primary')) {
            $client_id = User::getIdByUuid(request('client_uuid'));
            $campaign_id = Campaign::getIdByUuid(request('campaign_uuid'));
            self::where('client_id', $client_id)->where('campaign_id', $campaign_id)->update(['is_primary' => 0]);
            $data['is_primary'] = request('is_primary');
        }
        return  self::where('uuid', request('uuid'))->update($this->fillableFromArray($data));
    }
}
