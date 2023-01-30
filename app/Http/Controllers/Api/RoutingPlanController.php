<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RoutingPlanResource;
use App\Models\Campaign;
use App\Models\Routing;
use App\Models\RoutingPlan;
use App\Models\TargetListing;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\IvrBuilderFilterConditions;

class RoutingPlanController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/get-routing-plan",
     * summary="Get Routing Plan",
     * description="Get Routing Plan",
     * operationId="getRoutingPlan",
     * tags={"Routing Plan"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sorte routing plan by name param",
     *    in="query",
     *    name="name",
     *    example="test routing plan",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort routing plan by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort routing plan by pagination",
     *    in="query",
     *    name="perPage",
     *    example="1",
     *    @OA\Schema(
     *       type="integer"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Routing Plan has been fetched successfully!',
     *        'data': {
     *          'routingPlan': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'client_id': '1',
     *              'target_id': '1',
     *              'priority': '1',
     *              'weight': '1',
     *              'name': 'test routing plan',
     *              'destination': 'test destination',
     *              'duplicate_conversation_type': 'test duplicate_conversation_type',
     *              'time_limit_days': '5',
     *              'time_limit_hours': '5',
     *              'convert_on': 'test',
     *              'status': 'active',
     *              'created_at': '2022-07-25T09:41:48.000000Z'
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Campaign Reporting not found")
     *        )
     *     ),
     * )
     */

    public function getRoutingPlans(Request $request)
    {
        $routingPlan = RoutingPlan::getRoutingPlan($request);
        if (empty($routingPlan)) {
            return $this->respondNotFound('Routing Plan not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Routing Plans has been fetched successfully!',
            'data' => [
                'routingPlan' => $routingPlan
            ],
        ]);
    }

    public function getIvrDialRouting(Request $request)
    {
        $routing = Routing::whereHas('targets')->get();
        if (empty($routing)) {
            return $this->respondNotFound('Routing not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Routing has been fetched successfully!',
            'data' => [
                'routing' => $routing
            ],
        ]);
    }
    public function routings()
    {
        $routing = Routing::getRouting();
        if (empty($routing)) {
            return $this->respondNotFound('Routing Plan not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Routing has been fetched successfully!',
            'data' => [
                'routings' => $routing
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-routing-plan/detail/{uuid}",
     * summary="Get Routing Plan Detail by uuid",
     * description="Get Routing Plan Detail by uuid",
     * operationId="getRoutingPlanDetail",
     * tags={"Routing Plan"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Routing Plan",
     *    in="path",
     *    name="uuid",
     *    required=true,
     *    example="53adb8de-3cab-4aec-9db2-5bc7fd40b764",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       {
     *       'status': true,
     *       'message': 'Routing Plan Detail has been fetched successfully!',
     *        'data': {
     *          'routingPlan': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'client_id': '1',
     *              'target_id': '1',
     *              'priority': '1',
     *              'weight': '1',
     *              'name': 'test routing plan',
     *              'destination': 'test destination',
     *              'duplicate_conversation_type': 'test duplicate_conversation_type',
     *              'time_limit_days': '5',
     *              'time_limit_hours': '5',
     *              'convert_on': 'test',
     *              'status': 'active',
     *              'created_at': '2022-07-25T09:41:48.000000Z'
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Routing Plan Detail Not Found")
     *        )
     *     ),
     * )
     */

    public function getRoutingPlanDetail($uuid)
    {

        $routing_plan = RoutingPlan::getRoutingPlanByUuid('uuid', $uuid);
        return $this->respond([
            'status' => true,
            'message' => 'Routing Plan Detail has been fetched successfully!',
            'data' => [
                'routingPlan' => $routing_plan
            ]
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/delete-routing-plan",
     * summary="Delete Routing Plan by uuid",
     * description="Delete Routing Plan Detail by uuid",
     * operationId="deleteRoutingPlan",
     * tags={"Routing Plan"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Routing Plan",
     *    in="path",
     *    name="uuid",
     *    required=true,
     *    example="53adb8de-3cab-4aec-9db2-5bc7fd40b764",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       {
     *       'status': true,
     *       'message': 'Routing Plan Detail has been deleted successfully!',
     *        'data': {
     *          'routingPlan': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'client_id': '1',
     *              'target_id': '1',
     *              'priority': '1',
     *              'weight': '1',
     *              'name': 'test routing plan',
     *              'destination': 'test destination',
     *              'duplicate_conversation_type': 'test duplicate_conversation_type',
     *              'time_limit_days': '5',
     *              'time_limit_hours': '5',
     *              'convert_on': 'test',
     *              'status': 'active',
     *              'created_at': '2022-07-25T09:41:48.000000Z'
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Routing Plan Not Found")
     *        )
     *     ),
     * )
     */

    public function deleteRoutingPlan(Request $request)
    {
        $target_id = RoutingPlan::where('uuid', $request->uuid)->first()->target_id;

        $filterConditions = IvrBuilderFilterConditions::where('target_id', $target_id);

        if ($filterConditions->count() > 0) {
            $filterConditions->delete();
        }

        RoutingPlan::where('uuid', $request->uuid)->forceDelete();
        return $this->respond([
            'status' => true,
            'message' => 'Record has been deleted successfully!',
            'data' => []
        ]);
    }
    public function storeRouting(Request $request)
    {
        // 'name' => ['required', 'unique:routings']
        $request->validate([
            'name' => ['required']
        ]);
        $routing = new Routing($request->all());
        $routing->uuid = generateUuid();
        $routing->save();
        return $this->respond([
            'status' => true,
            'message' => 'Routing has been created successfully!',
            'data' => []
        ]);
    }
    public function updateRouting(Request $request)
    {
        // 'name' => 'required|unique:routings,name,' . $request->uuid,
        $data =  $request->validate([
            'name' => 'required',
            'uuid' => 'required',
        ]);
        Routing::where('uuid', $request->uuid)->update($data);
        return $this->respond([
            'status' => true,
            'message' => 'Routing has been updated successfully!',
            'data' => []
        ]);
    }
    public function deleteRouting(Request $request)
    {
        $data =  $request->validate([
            'uuid' => 'required',
        ]);
        Routing::where('uuid', $request->uuid)->delete($data);
        return $this->respond([
            'status' => true,
            'message' => 'Routing has been updated successfully!',
            'data' => []
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-routing-plan",
     * summary="Create Routing Plan",
     * description="Create Routing Plan",
     * operationId="createRoutingPlan",
     * tags={"Routing Plan"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Routing Plan data",
     *    @OA\JsonContent(
     *       required={"client_uuid,targert_uuid,priority,weight"},
     *       @OA\Property(property="client_uuid",type="string", format="client_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="target_uuid",type="string", format="target_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="priority", type="integer", format="weight", example="1"),
     *       @OA\Property(property="weight", type="string", format="tag_name", example="test key"),
     *       @OA\Property(property="name", type="string", format="name", example="test routing plan"),
     *       @OA\Property(property="destination", type="string", format="destination", example="test destination"),
     *       @OA\Property(property="duplicate_conversation_type", type="string", format="duplicate_conversation_type", example="test duplicate_conversation_type"),
     *       @OA\Property(property="time_limit_days", type="integer", format="time_limit_days", example="5"),
     *       @OA\Property(property="time_limit_hours", type="integer", format="time_limit_hours", example="5"),
     *       @OA\Property(property="convert_on", type="string", format="convert_on", example="test"),
     *       @OA\Property(property="status", type="enum", format="status", example="active/inactive/disable"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Routing Plan has been created successfully!',
     *       'data': {
     *          'routingPlan': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'client_id': '1',
     *              'target_id': '1',
     *              'priority': '1',
     *              'weight': '1',
     *              'name': 'test routing plan',
     *              'destination': 'test destination',
     *              'duplicate_conversation_type': 'test duplicate_conversation_type',
     *              'time_limit_days': '5',
     *              'time_limit_hours': '5',
     *              'convert_on': 'test',
     *              'status': 'active',
     *              'created_at': '2022-07-25T09:41:48.000000Z'
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Routing Plan Not Found")
     *        )
     *     ),
     * )
     */

    public function storeRoutingPlan(Request $request)
    {
        $request->validate([
            'target_uuid' => 'required|uuid',
            'client_uuid' => 'required|uuid',
            'campaign_uuid' => 'nullable|uuid',
            'priority' => 'required',
            'weight' => 'required',
        ]);
        $routingPlan = new RoutingPlan($request->all());
        $routingPlan->status = $request->has('status') ? $request->status : 'inactive';
        $routingPlan->client_id = User::getIdByUuid($request->client_uuid);
        $routingPlan->target_id = TargetListing::getIdByUuid($request->target_uuid);

        if ($request->has('campaign_uuid')) {
            $routingPlan->campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        }

        if ($request->has('route_uuid')) {
            $routingPlan->routing_id = Routing::getIdByUuid($request->route_uuid);
        }
        $routingPlan->save();
        return $this->respond([
            'status' => true,
            'message' => 'Routing Plan has been created successfully!',
            'data' => [
                'routingPlan' => new RoutingPlanResource($routingPlan)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-routing-plan",
     * summary="Update Routing Plan",
     * description="Update Routing Plan",
     * operationId="updateRoutingPlan",
     * tags={"Routing Plan"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     *    description="Update Routing Plan by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Routing Plan data",
     *    @OA\JsonContent(
     *       required={"client_uuid,targert_uuid,priority,weight"},
     *       @OA\Property(property="client_uuid",type="string", format="client_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="target_uuid",type="string", format="target_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="priority", type="integer", format="weight", example="1"),
     *       @OA\Property(property="weight", type="string", format="tag_name", example="test key"),
     *       @OA\Property(property="name", type="string", format="name", example="test routing plan"),
     *       @OA\Property(property="destination", type="string", format="destination", example="test destination"),
     *       @OA\Property(property="duplicate_conversation_type", type="string", format="duplicate_conversation_type", example="test duplicate_conversation_type"),
     *       @OA\Property(property="time_limit_days", type="integer", format="time_limit_days", example="5"),
     *       @OA\Property(property="time_limit_hours", type="integer", format="time_limit_hours", example="5"),
     *       @OA\Property(property="convert_on", type="string", format="convert_on", example="test"),
     *       @OA\Property(property="status", type="enum", format="status", example="active/inactive/disable"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Routing Plan has been created successfully!',
     *       'data': {
     *          'routingPlan': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'client_id': '1',
     *              'target_id': '1',
     *              'priority': '1',
     *              'weight': '1',
     *              'name': 'test routing plan',
     *              'destination': 'test destination',
     *              'duplicate_conversation_type': 'test duplicate_conversation_type',
     *              'time_limit_days': '5',
     *              'time_limit_hours': '5',
     *              'convert_on': 'test',
     *              'status': 'active',
     *              'created_at': '2022-07-25T09:41:48.000000Z'
     *          }
     *      }
     * }",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="unauthenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="bad request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bad Request")
     *        )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Routing Plan Not Found")
     *        )
     *     ),
     * )
     */

    public function updateRoutingPlan(Request $request)
    {

        $request->validate([
            'uuid' => 'required'
        ]);

        $routingPlan = RoutingPlan::getRoutingPlanByUuid('uuid', $request->uuid);
        $data = $request->all();
        if ($request->has('status'))
            $data['status'] = $request->status;
        $routingPlan->update($data);

        return $this->respond([
            'status' => true,
            'message' => 'Routing Plan has been updated successfully!',
            'data' => [
                'routingPlan' => new RoutingPlanResource($routingPlan)
            ],
        ]);
    }
}
