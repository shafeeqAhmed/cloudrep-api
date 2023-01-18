<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CampaignRegistrationRequest;
use App\Http\Resources\CampaignRegistrationResource;
use App\Models\CampaignRegistration;
use Illuminate\Http\Request;

class CampaignRegistrationController extends ApiController
{
    /**
     * @OA\Post(
     * path="/api/store-campaign-registration",
     * summary="Create Campaign Registration",
     * description="Create Campaign Registration",
     * operationId="createCampaignRegistration",
     * tags={"Campaign Registration"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Registration data",
     *    @OA\JsonContent(
     *       required={"username"},
     *       @OA\Property(property="payout_type", type="enum", format="payout_type", example="fixed amount/revshare percentage"),
     *       @OA\Property(property="username", type="string", format="username", example="hamza01"),
     *       @OA\Property(property="email", type="string", format="email", example="hamza@gmail.com"),
     *       @OA\Property(property="title", type="string", format="title", example="sales person"),
     *       @OA\Property(property="profile_picture", type="string", format="profile_picture", example="test"),
     *       @OA\Property(property="address", type="string", format="address", example="test address"),
     *       @OA\Property(property="status", type="enum", format="status", example="active/inactive"),
     *       @OA\Property(property="working_state", type="string", format="working_state", example="test state"),
     *       @OA\Property(property="working_hours", type="string", format="working_hours", example="true/false"),
     *       @OA\Property(property="open_time", type="time", format="open_time", example="12:30:02"),
     *       @OA\Property(property="close_time", type="time", format="close_time", example="12:40:05"),
     *       @OA\Property(property="time_zone", type="string", format="time_zone", example="+GST canada"),
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="1")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Registration has been created successfully!',
     *       'data': {
     *          'campaign_registration': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'username': 'hamza01',
     *             'email': 'hamza@gmail.com',
     *             'title': 'test title',
     *             'profile_picture', 'test.jpg',
     *             'address': test address,
     *             'status': 'active',
     *             'working_state': 'test state',
     *             'working_hours', 'true',
     *             'open_time', '12:30:02',
     *             'close_time', '12:40:05',
     *             'time_zone', '+GST Canada',
     *             'user_id', '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z'
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
     *       @OA\Property(property="message", type="string", example="Campaign Registration not created")
     *        )
     *     ),
     * )
     */

    public function storeCampaignRegistration(Request $request)
    {
        $request->validate([
            // 'username' => 'required|string|unique:campaign_registrations,username',
            'username' => 'nullable|string',
            'title' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|string',
            'working_state' => 'nullable|string',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'time_zone' => 'nullable|string'
        ]);
        $CampaignRegistration = new CampaignRegistration($request->all());
        $CampaignRegistration->username = $request->user()->name;
        $CampaignRegistration->email = $request->user()->email;
        $CampaignRegistration->user_id = $request->user()->id;
        $CampaignRegistration->profile_picture = $request->user()->profile_photo_path;
        $CampaignRegistration->working_hours = $request->boolean('working_hours');
        $CampaignRegistration->save();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Registration has been created successfully!',
            'date' => [
                'campaign_registration' => new CampaignRegistrationResource($CampaignRegistration),
            ],
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/update-campaign-registration",
     * summary="Update Campaign Registration by uuid",
     * description="Update Campaign Registration",
     * operationId="updateCampaignRegistration",
     * tags={"Campaign Registration"},
     * security={ {"sanctum": {} }},
     * * @OA\Parameter(
     *    description="Update Campaign Registration by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Registration data",
     *     @OA\JsonContent(
     *      required={"username"},
     *       @OA\Property(property="username", type="string", format="username", example="hamza01"),
     *       @OA\Property(property="email", type="string", format="email", example="hamza@gmail.com"),
     *       @OA\Property(property="title", type="string", format="title", example="test title"),
     *       @OA\Property(property="profile_picture", type="string", format="profile_picture", example="test.jpg"),
     *       @OA\Property(property="address", type="string", format="address", example="test address"),
     *       @OA\Property(property="status", type="enum", format="status", example="active/inactive"),
     *       @OA\Property(property="working_state", type="string", format="working_state", example="test state"),
     *       @OA\Property(property="working_hours", type="boolean", format="working_hours", example="true/false"),
     *       @OA\Property(property="open_time", type="time", format="open_time", example="12:30:02"),
     *       @OA\Property(property="close_time", type="time", format="close_time", example="12:40:05"),
     *       @OA\Property(property="time_zone", type="string", format="time_zone", example="+GST Canada"),
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="1")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Registration has been updated successfully!',
     *         'data': {
     *          'campaign_registration': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'username': 'hamza01',
     *             'email': 'hamza@gmail.com',
     *             'title': 'test title',
     *             'profile_picture': 'test',
     *             'address': 'test address',
     *             'status': 'active/inactive',
     *             'working_state': 'test state',
     *             'working_hours': 'true',
     *             'open_time': '12:30:02',
     *             'close_time': '12:40:05',
     *             'time_zone': '+GST Canada',
     *             'user_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Campaign Registration not found")
     *        )
     *     ),
     * )
     */

    public function updateCampaignRegistration(Request $request)
    {
        $CampaignRegistration = CampaignRegistration::where('uuid', $request->uuid)->first();
        $request->validate([
            'title' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'nullable|string',
            'working_state' => 'nullable|string',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'time_zone' => 'nullable|string'
        ]);
        $data = $request->all();
        $data['email'] = $request->user()->email;
        $data['user_id'] = $request->user()->id;
        $data['profile_picture'] = $request->profile_photo_path;
        if ($request->has('working_hours'))
            $data['working_hours'] = $request->boolean('working_hours');
        $CampaignRegistration->update($data);

        if (empty($CampaignRegistration)) {
            return $this->respondNotFound('Campaign Registration not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Registration has been updated successfully',
            'date' => [
                'campaign_registration' => new CampaignRegistrationResource($CampaignRegistration)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-campaign-registration",
     * summary="Get Campaign Registrations",
     * description="Get Campaign Registrations",
     * operationId="getCampaignRegistrations",
     * tags={"Campaign Registration"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort campaign registration by username param",
     *    in="query",
     *    name="username",
     *    example="hamza01",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort campaign registration by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort campaign registrations by pagination",
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
     *       'message': 'Campaign Registrations has been fetched successfully!',
     *       'data': {
     *          'campaign_registration': {
     *              'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *              'username': 'hamza01',
     *              'email': 'hamza@gmail.com',
     *              'title': 'test title',
     *              'profile_picture': 'test.jpg',
     *              'address': 'test address',
     *              'status': 'active',
     *              'working_state': 'test state',
     *              'working_hours': 'true',
     *              'open_time': '12:30:02',
     *              'close_time': '12:40:05',
     *              'time_zone': '+GST Canada',
     *              'user_id': '1',
     *              'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Campaign Registration not found")
     *        )
     *     ),
     * )
     */

    public function getCampaignRegistration(Request $request)
    {
        $CampaignRegistration = CampaignRegistration::when($request->q, function ($query, $q) {
            return $query->where('username', 'LIKE', "%{$q}");
        })
            ->when($request->soryBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->orderBy('id', 'DESC')->paginate($request->perPage);
        if (empty($CampaignRegistration)) {
            return $this->respondNotFound('Campaign Registration not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Registration has been fetched successfully!',
            'data' => [
                'campaign_registration' => $CampaignRegistration
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-single-campaign-registration",
     * summary="Get Single Campaign Registration by uuid",
     * description="Get Single Campaign Registration",
     * operationId="getSingleCampaignRegistration",
     * tags={"Campaign Registration"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort campaign registration by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="1ec65f17-25cd-413e-b097-73acb6b5b4e2",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Registration has been fetched successfully!',
     *       'data': {
     *          'campaign_registration': {
     *              'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *              'username': 'hamza01',
     *              'email': 'hamza@gmail.com',
     *              'title': 'sales person',
     *              'profile_picture': 'test',
     *              'address': 'test address',
     *              'status': 'active',
     *              'working_state': 'test state',
     *              'working_hours': 'true',
     *              'open_time': '12:30:02',
     *              'close_time': '12:40:05',
     *              'time_zone': '+GST Canada',
     *              'user_id': '1',
     *              'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Campaign Registration not found")
     *        )
     *     ),
     * )
     */

    public function getSingleCampaignRegistration(Request $request)
    {
        $CampaignRegistration = CampaignRegistration::where('uuid', $request->uuid)->first();
        if (empty($CampaignRegistration)) {
            return $this->respondNotFound('Campaign Registration not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Registration has been fetched successfully!',
            'data' => [
                'campaign_registration' => new CampaignRegistrationResource($CampaignRegistration)
            ],
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/delete-campaign-registration",
     * summary="Delete Campaign Registration by uuid",
     * description="Delete existing Campaign Registration by uuid param",
     * operationId="deleteCampaignRegistration",
     * tags={"Campaign Registration"},
     * security={ {"sanctum": {} }},
     * * @OA\Parameter(
     *    description="delete campaign registration by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="1ec65f17-25cd-413e-b097-73acb6b5b4e2",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Registration has been deleted successfully!',
     *         'data': {
     *          'campaign_registration': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *              'username': 'hamza01',
     *              'email': 'hamza@gmail.com',
     *              'title': 'sales person',
     *              'profile_picture': 'test.jpg',
     *              'address': 'test address',
     *              'status': 'active',
     *              'working_state': 'test state',
     *              'working_hours': 'true',
     *              'open_time': '12:30:02',
     *              'close_time': '12:40:05',
     *              'time_zone': '+GST Cananda',
     *              'user_id': '1',
     *              'created_at': '2022-06-04T18:32:20.000000Z'
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
     *       @OA\Property(property="message", type="string", example="Campaign Registration Not Found")
     *        )
     *     ),
     * )
     */

    public function deleteCampaignRegistration(Request $request)
    {
        $CampaignRegistration = CampaignRegistration::where('uuid', $request->uuid)->first();
        if (empty($CampaignRegistration)) {
            return $this->respondNotFound('Campaign Registration not found');
        }
        $CampaignRegistration->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Registration has been deleted successfully!',
            'data' => [
                'campaign_registration' => new CampaignRegistrationResource($CampaignRegistration)
            ],
        ]);
    }
}
