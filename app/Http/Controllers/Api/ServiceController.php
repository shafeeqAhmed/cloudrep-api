<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Classes\Constants;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/services",
     * summary="Get Services",
     * description="Get Services",
     * operationId="getServices",
     * tags={"Service"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort services by name param",
     *    in="query",
     *    name="name",
     *    example="test services",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort services by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *  * @OA\Parameter(
     *    description="sort services by type",
     *    in="query",
     *    name="type",
     *    example="client,agent,publisher",
     *    @OA\Schema(
     *       type="integer"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort services by pagination",
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
     *       'message': 'Services has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test service',
     *          'type': 'client',
     *          'icon': 'test icon 1',
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
     *       @OA\Property(property="message", type="string", example="Services Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $services = Service::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', '=', $type);
            })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($services)) {
            return $this->respond([
                'status' => false,
                'message' => 'Services Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Services has been Fetched Successfully!',
            'data' => [
                'services' => ServiceResource::collection($services)
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

    /**
     * @OA\Post(
     * path="/api/services",
     * summary="Create Service",
     * description="Create Service",
     * operationId="createService",
     * tags={"Service"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Service data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test service"),
     *       @OA\Property(property="type", type="string", format="type", example="client,agent,publisher"),
     *       @OA\Property(property="icon", type="string", format="icon", example="test icon"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Services has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test service',
     *          'type': 'client',
     *          'icon': 'test icon 1',
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
     *       @OA\Property(property="message", type="string", example="Service Not Found")
     *        )
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'icon' => 'required',
            'image' => 'required',
        ]);
        $new = new Service($request->all());
        $new->save();
        if ($new) {
            return $this->respond([
                'status' => true,
                'message' => 'Service has been Created Successfully!',
                'data' => [

                    'service' => new ServiceResource($new)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Service not Created!',
                'data' => [
                    'service' => []
                ],
            ]);
        }

        // $types = [Constants::$SERVICE_TYPE_CLIENT, Constants::$SERVICE_TYPE_PUBLISHER, Constants::$SERVICE_TYPE_AGENT];
        // $service = new Service($request->validated());
        // $service->icon = $request->icon;
        // if (in_array($request->type, $types)) {
        //     $service->type = $request->type;
        // } else {
        //     return $this->respond([
        //         'status' => false,
        //         'message' => 'Service Type must be ' . implode(',', $types) . ''
        //     ]);
        // }
        // $service->save();
        // if (empty($service)) {
        //     return $this->respond([
        //         'status' => false,
        //         'message' => 'Service Not Found',
        //         'data' =>  []
        //     ]);
        // }
        // return $this->respond([
        //     'status' => true,
        //     'message' => 'Service has been Created Successfully!',
        //     'data' => [

        //         'service' => new ServiceResource($service)
        //     ],
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/services/{service_uuid}",
     * summary="Get Service",
     * description="Get Service by service_uuid",
     * operationId="getServiceById",
     * tags={"Service"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="service_uuid of Service",
     *    in="path",
     *    name="service_uuid",
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
     *       'message': 'Services has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test service',
     *          'type': 'client',
     *          'icon': 'test icon 1',
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
     *       @OA\Property(property="message", type="string", example="Service Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $service = Service::where('service_uuid', $id)->first();
        if (empty($service)) {
            return $this->respond([
                'status' => false,
                'message' => 'Service Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Service has been Fetched Successfully!',
            'data' => [
                'service' => new ServiceResource($service)
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
     * path="/api/services/{service_uuid}",
     * summary="Update Service",
     * description="Update Service",
     * operationId="updateService",
     * tags={"Service"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Service data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test service"),
     *       @OA\Property(property="type", type="string", format="type", example="client,agent,publisher"),
     *       @OA\Property(property="icon", type="string", format="icon", example="test icon"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="service_uuid of Service",
     *    in="path",
     *    name="service_uuid",
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
     *       'message': 'Services has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test service',
     *          'type': 'client',
     *          'icon': 'test icon 1',
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
     *       @OA\Property(property="message", type="string", example="Service Not Found")
     *        )
     *     ),
     * )
     */
    public function update(ServiceRequest $request, $id)
    {
        $service = Service::where('service_uuid', $id)->first();
        $data = $request->validated();
        if ($request->has('name'))
            $data['name'] = $request->name;
        if ($request->has('type'))
            $data['type'] = $request->type;
        if ($request->has('icon'))
            $data['icon'] = $request->icon;
        $service->update($data);

        if (empty($service)) {
            return $this->respond([
                'status' => false,
                'message' => 'Service Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Service has been Updated Successfully!',
            'data' => [
                'service' => new ServiceResource($service)
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
     * path="/api/services/{service_uuid}",
     * summary="Delete Service",
     * description="Delete existing Service",
     * operationId="deleteService",
     * tags={"Service"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="service_uuid of Service",
     *    in="path",
     *    name="service_uuid",
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
     *       'message': 'Services has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test service',
     *          'type': 'client',
     *          'icon': 'test icon 1',
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
     *       @OA\Property(property="message", type="string", example="Service Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $service = Service::where('service_uuid', $id)->first();
        if (empty($service)) {
            return $this->respond([
                'status' => false,
                'message' => 'Service Not Found',
                'data' =>  []
            ]);
        }
        $service->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Service has been Deleted Successfully!',
            'data' => [
                'service' => new ServiceResource($service)
            ],
        ]);
    }
}
