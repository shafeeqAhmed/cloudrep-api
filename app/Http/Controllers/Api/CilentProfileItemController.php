<?php

namespace App\Http\Controllers\Api;

use App\Classes\Gamification;
use App\Http\Requests\ClientProfileItemRequest;
use App\Http\Resources\ClientProfileItemResource;
use App\Models\ClientProfileItem;
use App\Models\User;
use Illuminate\Http\Request;

class CilentProfileItemController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/client-profile-items",
     * summary="Get Client Profile Item",
     * description="Get Client Profile Item",
     * operationId="getClientProfileItem",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort client profile items by name param",
     *    in="query",
     *    name="name",
     *    example="test client profile item",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort client profile items by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort client profile items by pagination",
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
     *       'message': 'Client Profile Item has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'bussines_name': 'test client profile item',
     *          'bussines_address': 'canada',
     *          'bussines_phone_no': '123456789',
     *          'google_my_bussines': 'google.com/john-doe',
     *          'crunchbase': 'null',
     *          'linkedin': 'linkedin.com/john-doe',
     *          'twitter': 'twitter.com/john-doe',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $client_profile_items = ClientProfileItem::when($request->q, function ($query, $q) {
            return $query->where('business_name', 'LIKE', "%{$q}%");
        })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', 'LIKE', "%{$type}%");
            })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($client_profile_items)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Items has been Fetched Successfully!',
            'data' => [
                'client_profile_items' => ClientProfileItemResource::collection($client_profile_items)
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/client-profile-items/{uuid}",
     * summary="Get Client Profile Item by uuid",
     * description="Get Bussines Client Profile Item by uuid",
     * operationId="getClientProfileItemById",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Client Profile Item",
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
     *       'message': 'Client Profile Item has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'bussines_name': 'test client profile item',
     *          'bussines_address': 'canada',
     *          'bussines_phone_no': '123456789',
     *          'google_my_bussines': 'google.com/john-doe',
     *          'crunchbase': 'null',
     *          'linkedin': 'linkedin.com/john-doe',
     *          'twitter': 'twitter.com/john-doe',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $client_profile_item = ClientProfileItem::where('uuid', $id)->first();
        if (empty($client_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Item has been Fetched Successfully!',
            'data' => [
                'client_profile_item' => new ClientProfileItemResource($client_profile_item)
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     * path="/api/client-profile-items/{uuid}",
     * summary="Update Client Profile Item by uuid",
     * description="Update Client Profile Item",
     * operationId="updateClientProfileItem",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Profile Item data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="bussines_name", type="string", format="bussines_name", example="test client profile item"),
     *       @OA\Property(property="bussines_address", type="string", format="bussines_address", example="canada"),
     *       @OA\Property(property="bussines_phone_no", type="string", format="bussines_phone_no", example="+123456789"),
     *       @OA\Property(property="google_my_bussines", type="string", format="google_my_bussines", example="google.com/john-doe"),
     *       @OA\Property(property="crunchbase", type="string", format="crunchbase", example="test"),
     *       @OA\Property(property="linkedin", type="string", format="linkedin", example="linkedin.com/john-doe"),
     *       @OA\Property(property="twitter", type="string", format="twitter", example="twitter.com/john-doe"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="uuid of Client Profile Item",
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
     *       'message': 'Client Profile Item has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'bussines_name': 'test client profile item',
     *          'bussines_address': 'canada',
     *          'bussines_phone_no': '123456789',
     *          'google_my_bussines': 'google.com/john-doe',
     *          'crunchbase': 'null',
     *          'linkedin': 'linkedin.com/john-doe',
     *          'twitter': 'twitter.com/john-doe',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $client_profile_item = ClientProfileItem::where('uuid', $id)->first();
        // $data = $request->all();
        // $data['user_id'] = $request->user()->id;
        $client_profile_item->update($request->all());
        if (empty($client_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Item has been Updated Successfully!',
            'data' => [
                'client_profile_item' => new ClientProfileItemResource($client_profile_item)
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     * path="/api/client-profile-items/{uuid}",
     * summary="Delete Client Profile Item",
     * description="Delete existing Client Profile Item",
     * operationId="deleteClientProfileItem",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Client Profile Item",
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
     *       'message': 'Client Profile Item has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test service',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $client_profile_item = ClientProfileItem::where('uuid', $id)->first();
        if (empty($client_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Item Not Found',
                'data' =>  []
            ]);
        }
        $client_profile_item->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Item has been Deleted Successfully!',
            'data' => [
                'client_profile_item' => new ClientProfileItemResource($client_profile_item)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/client-profile-item",
     * summary="Get Client Profile Item By Current User",
     * description="Get Bussines Client Profile Item by login user",
     * operationId="getClientProfile",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Profile Item has been Fetched Successfully!',
     *       'data': {
     *          'client_profile_item': {
     *             'id': 1,
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'bussines_name': 'test client profile item',
     *             'bussines_address': 'canada',
     *             'bussines_phone_no': '123456789',
     *             'google_my_bussines': 'google.com/john-doe',
     *             'crunchbase': 'null',
     *             'linkedin': 'linkedin.com/john-doe',
     *             'twitter': 'twitter.com/john-doe',
     *             'step': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
     *             'updated_at': '2022-06-04T18:36:16.000000Z',
     *             'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function getClientProfileByUser(Request $request)
    {
        $client_profile_items = ClientProfileItem::where('user_id', $request->user()->id)->first();
        if (empty($client_profile_items)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Item Not Created',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Items has been Fetched Successfully!',
            'data' => [
                'client_profile_items' => new ClientProfileItemResource($client_profile_items)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-client-profile-items",
     * summary="Create Client Profile Item",
     * description="Create Client Profile Item",
     * operationId="createClientProfileItem",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Profile Item data",
     *    @OA\JsonContent(
     *       required={"bussines_name"},
     *       @OA\Property(property="bussines_name", type="string", format="bussines_name", example="test client profile item"),
     *       @OA\Property(property="bussines_address", type="string", format="bussines_address", example="canada"),
     *       @OA\Property(property="bussines_phone_no", type="string", format="bussines_phone_no", example="+123456789"),
     *       @OA\Property(property="google_my_bussines", type="string", format="google_my_bussines", example="google.com/john-doe"),
     *       @OA\Property(property="crunchbase", type="string", format="crunchbase", example="test"),
     *       @OA\Property(property="linkedin", type="string", format="linkedin", example="linkedin.com/john-doe"),
     *       @OA\Property(property="twitter", type="string", format="twitter", example="twitter.com/john-doe"),
     *       @OA\Property(property="step", type="integer", format="step", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Profile Item has been Created Successfully!',
     *       'data': {
     *          'client_profile_item': {
     *             'id': 1,
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'bussines_name': 'test client profile item',
     *             'bussines_address': 'canada',
     *             'bussines_phone_no': '123456789',
     *             'google_my_bussines': 'google.com/john-doe',
     *             'crunchbase': 'null',
     *             'linkedin': 'linkedin.com/john-doe',
     *             'twitter': 'twitter.com/john-doe',
     *             'step': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
     *             'updated_at': '2022-06-04T18:36:16.000000Z',
     *             'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function storeClientProfile(Request $request)
    {

        $client_profile_item = new ClientProfileItem([
            'bussines_name' => $request->bussines_name,
            'bussines_address' => $request->bussines_address,
            'bussines_phone_no' => $request->bussines_phone_no,
            'google_my_bussines' => $request->google_my_bussines,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'crunchbase' => $request->crunchbase,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            'user_id' => $request->user()->id,
            'step' => 1
        ]);
        $client_profile_item->save();
        $gamification = new Gamification();
        $gamification->add($request, $client_profile_item->user_id, 20, 'Client Profile Item Creation', true);
        if (empty($client_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Not Created',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Item has been Created Successfully!',
            'data' => [
                'client_profile_item' => new ClientProfileItemResource($client_profile_item)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-client-profile-items",
     * summary="Update Client Profile Item of Current User",
     * description="Update Client Profile Item",
     * operationId="updateClientProfile",
     * tags={"Client Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Profile Item data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="bussines_name", type="string", format="bussines_name", example="test client profile item"),
     *       @OA\Property(property="bussines_address", type="string", format="bussines_address", example="canada"),
     *       @OA\Property(property="bussines_phone_no", type="string", format="bussines_phone_no", example="+123456789"),
     *       @OA\Property(property="google_my_bussines", type="string", format="google_my_bussines", example="google.com/john-doe"),
     *       @OA\Property(property="crunchbase", type="string", format="crunchbase", example="test"),
     *       @OA\Property(property="linkedin", type="string", format="linkedin", example="linkedin.com/john-doe"),
     *       @OA\Property(property="twitter", type="string", format="twitter", example="twitter.com/john-doe"),
     *       @OA\Property(property="step", type="string", format="step", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Profile Item has been Updated Successfully!',
     *       'data': {
     *          'client_profile_item': {
     *             'id': 1,
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'bussines_name': 'test client profile item',
     *             'bussines_address': 'canada',
     *             'bussines_phone_no': '123456789',
     *             'google_my_bussines': 'google.com/john-doe',
     *             'crunchbase': 'null',
     *             'linkedin': 'linkedin.com/john-doe',
     *             'twitter': 'twitter.com/john-doe',
     *             'step': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
     *             'updated_at': '2022-06-04T18:36:16.000000Z',
     *             'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */

    public function updateClientProfile(Request $request)
    {
        $client_profile_item = ClientProfileItem::where('user_id', $request->user()->id)->first();

        if ($client_profile_item) {
            $data = $request->all();
            $data['step'] = 2;
            $client_profile_item->update($data);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Client Profile Item Not Found and Updated!',
            ]);
        }
        if (empty($client_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Profile Item has been Updated Successfully!',
            'data' =>  [
                'client_profile_item' => new ClientProfileItemResource($client_profile_item)
            ]
        ]);
    }
}
