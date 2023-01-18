<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\BussinesCategoryRequest;
use App\Http\Resources\BussinesCategoryResource;
use App\Models\BussinesCategory;
use App\Models\ClientProfileItem;
use Illuminate\Http\Request;

class BussinesCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/bussines-categories",
     * summary="Get Bussines Categories",
     * description="Get Bussines Categories",
     * operationId="getBussinesCategories",
     * tags={"Bussines Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort bussines categories by name param",
     *    in="query",
     *    name="name",
     *    example="test bussines categories",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort bussines categories by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort bussines categories by pagination",
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
     *       'message': 'Business Categories has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test bussines categoy',
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
     *       @OA\Property(property="message", type="string", example="Business Category Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $bussines_categories = BussinesCategory::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);

        if (empty($bussines_categories)) {
            return $this->respond([
                'status' => false,
                'message' => 'Bussines Categories Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Bussines Categories has been Fetched Successfully!',
            'data' => [
                'bussines_categories' => BussinesCategoryResource::collection($bussines_categories)
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
     * path="/api/bussines categories",
     * summary="Create Bussines Category",
     * description="Create Bussines Category",
     * operationId="createBussinesCategory",
     * tags={"Bussines Category"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Bussines Category data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test service"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Bussines Categories has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test bussines category',
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
     *       @OA\Property(property="message", type="string", example="Busines Category Not Found")
     *        )
     *     ),
     * )
     */
    public function store(BussinesCategoryRequest $request)
    {
        $bussines_category = new BussinesCategory($request->validated());
        $bussines_category->user_id = $request->user()->id;
        $bussines_category->save();
        ClientProfileItem::where('user_id', $request->user()->id)->update([
            'step' => 2
        ]);

        if (empty($bussines_category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Bussines Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Bussines Category has been Created Successfully!',
            'data' => [
                'bussines_category' => new BussinesCategoryResource($bussines_category)
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/bussines-categories/{uuid}",
     * summary="Get Bussines Category",
     * description="Get Bussines Category by uuid",
     * operationId="getBussines CategoryById",
     * tags={"Bussines Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Bussines Category",
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
     *       'message': 'Bussines Category has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test Bussines Category',
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
     *       @OA\Property(property="message", type="string", example="Bussines Category Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $bussines_category = BussinesCategory::where('uuid', $id)->first();
        if (empty($bussines_category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Bussines Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Bussines Category has been Fetched Successfully!',
            'data' => [
                'bussines_category' => new BussinesCategoryResource($bussines_category)
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
     * path="/api/bussines-categories/uuid",
     * summary="Update Bussines Category",
     * description="Update Bussines Category",
     * operationId="updateBussinesCategory",
     * tags={"Bussines Category"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Bussines Category data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test service"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="uuid of Bussines Category",
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
     *       'message': 'Bussines Category has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test bussines category',
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
     *       @OA\Property(property="message", type="string", example="Busines Category Not Found")
     *        )
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $bussines_category = BussinesCategory::where('uuid', $id)->first();
        if ($request->has('name'))
            $bussines_category->name = $request->name;
        $bussines_category->user_id = $request->user()->id;
        $bussines_category->save();
        if (empty($bussines_category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Bussines Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Bussines Category has been Deleted Successfully!',
            'data' => [
                'bussines_category' => new BussinesCategoryResource($bussines_category)
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
     * path="/api/bussines-categories/{uuid}",
     * summary="Delete Bussines Category",
     * description="Delete existing Bussines Category",
     * operationId="deleteBussinesCategory",
     * tags={"Bussines Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Bussines Category",
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
     *       'message': 'Bussines Category has been Deleted Successfully!',
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
     *       @OA\Property(property="message", type="string", example="Bussines Category Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $bussines_category = BussinesCategory::where('uuid', $id)->first();
        if (empty($bussines_category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Bussines Category Not Found',
                'data' =>  []
            ]);
        }
        $bussines_category->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Bussines Category has been Deleted Successfully!',
            'data' => [
                'bussines_category' => new BussinesCategoryResource($bussines_category)
            ],
        ]);
    }
}
