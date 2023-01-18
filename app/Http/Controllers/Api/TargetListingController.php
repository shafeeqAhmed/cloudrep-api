<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TargetListingResource;
use App\Models\Campaign;
use App\Models\TargetListing;
use App\Models\User;
use Illuminate\Http\Request;

class TargetListingController extends ApiController
{

    /**
     * @OA\Get(
     * path="/api/get-target-listing",
     * summary="Get Target Listing",
     * description="Get Target Listing",
     * operationId="getTargetListing",
     * tags={"Target Listing"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sorte target listing by name param",
     *    in="query",
     *    name="name",
     *    example="test target listing",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort target listing by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort target listing by pagination",
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
     *       'message': 'Target Listing has been fetched successfully!',
     *        'data': {
     *          'targetListing': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'client_id': '1',
     *              'name': 'test target listing',
     *              'type': 'test type',
     *              'destination': 'test destination',
     *              'status': 'active',
     *              'is_primary': 'true',
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
     *       @OA\Property(property="message", type="string", example="Target Listing not found")
     *        )
     *     ),
     * )
     */

    public function getTargets(Request $request)
    {
        $targetListing = TargetListing::getTargetListing($request);

        if (empty($targetListing)) {
            return $this->respondNotFound('Target Listing not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Target Listing has been fetched successfully!',
            'data' => [
                'targetListing' => $targetListing
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-target-detail/{uuid}",
     * summary="Get Target Listing Detail by uuid",
     * description="Get Target Listing Detail by uuid",
     * operationId="getTargetListingDetail",
     * tags={"Target Listing"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Target Listing",
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
     *       'message': 'Target Listing Detail has been fetched successfully!',
     *        'data': {
     *          'targetListing': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'client_id': '1',
     *              'name': 'test target listing',
     *              'type': 'test type',
     *              'destination': 'test destination',
     *              'status': 'active',
     *              'is_primary': 'true',
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
     *       @OA\Property(property="message", type="string", example="Target Listing Detail Not Found")
     *        )
     *     ),
     * )
     */

    public function getTargetDetail($uuid)
    {
        $target = TargetListing::getTargetListingByUuid('uuid', $uuid);
        return $this->respond([
            'status' => true,
            'message' => '',
            'data' => [
                'targetData' => $target
            ]
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-target",
     * summary="Create Target Listing",
     * description="Create Target Listing",
     * operationId="createTargetListing",
     * tags={"Target Listing"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Target Listing data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid,client_uuid,name"},
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="client_uuid",type="string", format="client_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="name", type="integer", format="name", example="test target listing"),
     *       @OA\Property(property="type", type="string", format="type", example="test type"),
     *       @OA\Property(property="destination", type="string", format="destination", example="test destination"),
     *       @OA\Property(property="status", type="enum", format="status", example="active/inactive/disable"),
     *       @OA\Property(property="is_primary", type="boolean", format="is_primary", example="true/false"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Routing Plan has been created successfully!',
     *       'data': {
     *          'targetListing': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'client_id': '1',
     *              'name': 'test target listing',
     *              'type': 'test type',
     *              'destination': 'test destination',
     *              'status': 'active',
     *              'is_primary': 'true',
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
     *       @OA\Property(property="message", type="string", example="Target Listing Not Found")
     *        )
     *     ),
     * )
     */

    public function storeTarget(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'nullable|uuid',
            'client_uuid' => 'required|uuid',
            'route_uuid' => 'nullable|uuid',
            'name' => 'required',
            'destination'  => 'required',
        ]);

        $targetListing = TargetListing::storeTarget();
        return $this->respond([
            'status' => true,
            'message' => 'Target Listing has been created successfully!',
            'data' => [
                'targetListing' => new TargetListingResource($targetListing)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-target",
     * summary="Update Target Listing",
     * description="Update Target Listing",
     * operationId="updateTargetListing",
     * tags={"Target Listing"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     *    description="Update Target Listing by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Target Listing data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid,client_uuid,name"},
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="client_uuid",type="string", format="client_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="name", type="integer", format="name", example="test target listing"),
     *       @OA\Property(property="type", type="string", format="type", example="test type"),
     *       @OA\Property(property="destination", type="string", format="destination", example="test destination"),
     *       @OA\Property(property="status", type="enum", format="status", example="active/inactive/disable"),
     *       @OA\Property(property="is_primary", type="boolean", format="is_primary", example="true/false"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Target Listing has been created successfully!',
     *       'data': {
     *          'targetListing': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'client_id': '1',
     *              'name': 'test target listing',
     *              'type': 'test type',
     *              'destination': 'test destination',
     *              'status': 'active',
     *              'is_primary': 'true',
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
     *       @OA\Property(property="message", type="string", example="Target Listing Not Found")
     *        )
     *     ),
     * )
     */

    public function updateTarget(Request $request)
    {
        $request->validate([
            'client_uuid' => 'required',
            'uuid' => 'required'
        ]);
        (new  TargetListing())->updateTarget();
        return $this->respond([
            'status' => true,
            'message' => 'Target Listing has been updated successfully!',
            'data' => []
        ]);
    }
}
