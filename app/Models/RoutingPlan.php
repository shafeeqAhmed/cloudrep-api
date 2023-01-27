<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\support\Str;
use App\Models\TargetListing;

class RoutingPlan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'priority',
        'weight',
        'name',
        'destination',
        'duplicate_conversation_type',
        'revenue',
        'target_id',
        'client_id',
        'time_limit_days',
        'time_limit_hours',
        'convert_on',
        'status'
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
        return RoutingPlan::whereUuid($uuid)->value('id');
    }

    public static function getRoutingPlanByUuid($col, $val)
    {
        $record =  self::where($col, $val)->first();
        return $record;
    }

    public static function getRoutingPlan(Request $request)
    {

        $routingPlan = RoutingPlan::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->soryBy, function ($query, $sortBy) {
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
            ->orderBy('id', 'DESC')->with(['target' => function ($query) {
                $query->select('name', 'destination', 'id', 'uuid');
            }])->paginate($request->perPage);

        return $routingPlan;
    }

    public function target()
    {
        return $this->hasOne(TargetListing::class, 'id', 'target_id');
    }
    public static function getRouting($routingPlanId)
    {
        // self::where('id',$routingPlan)->
    }
}
