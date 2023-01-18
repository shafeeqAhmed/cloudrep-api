<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Routing extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public static function getRouting()
    {
        $routing = self::withCount(['targets', 'routingPlans'])->when(request('q'), function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when(request('soryBy'), function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when(request('page'), function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate(request('perPage'));

        return $routing;
    }
    public function targets()
    {
        return $this->hasMany(TargetListing::class, 'routing_id', 'id');
    }
    public function routingPlans()
    {
        return $this->hasMany(RoutingPlan::class, 'routing_id', 'id');
    }
    public static function updateRouting($condition, $data)
    {
        return self::where($condition)->update($data);
    }
    public static function getIdByUuid($uuid)
    {
        return self::where('uuid', $uuid)->value('id');
    }

    public static function getRoutingForAction($id)
    {
        // $data['routingPlans'] = RoutingPlan::where('r.id', $id)
        //     ->join('routings as r', 'r.id', '=', 'routing_plans.routing_id')
        //     ->join('target_listings as tl', 'tl.id', '=', 'routing_plans.target_id')
        //     ->select(
        //         'routing_plans.uuid',
        //         'routing_plans.priority',
        //         'routing_plans.weight',
        //         'routing_plans.revenue',
        //         'routing_plans.duplicate_conversation_type',
        //         'routing_plans.convert_on',
        //         'tl.destination'
        //     )
        //     ->orderBy('routing_plans.priority', 'asc')
        //     ->get();

        // $data['targets'] = TargetListing::where('routing_id', $id)
        //     ->where('status', 'active')
        //     ->select('destination', 'uuid', 'is_primary', 'type', 'id')
        //     ->orderBy('is_primary', 'desc')
        //     ->get();
        // return $data;


        $routingPlans = RoutingPlan::where('r.id', $id)
            ->join('routings as r', 'r.id', '=', 'routing_plans.routing_id')
            ->join('target_listings as tl', 'tl.id', '=', 'routing_plans.target_id')
            ->select(
                'routing_plans.uuid',
                'tl.destination'
            )
            ->orderBy('routing_plans.priority', 'asc')
            ->get();

        $targets = TargetListing::where('routing_id', $id)
            ->where('status', 'active')
            ->select('destination', 'uuid')
            ->orderBy('is_primary', 'desc')
            ->get();
        // $data['routingPlans'] = $routingPlans;
        // $data['targets'] = $targets;
        // return $data;
        return count($routingPlans) ? $routingPlans : $targets;
    }
}
