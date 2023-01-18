<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CompanyVerticalRequest;
use App\Http\Resources\CompanyVertialResource;
use App\Models\BussinesCategory;
use App\Models\ClientVertical;
use App\Models\CompanyVertical;
use App\Models\CompanyVerticals;
use Illuminate\Http\Request;

class CompanyVertialController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/company-verticals",
     * summary="Get Company Vertical",
     * description="Get Company Vertical",
     * operationId="getCompanyVertical",
     * tags={"Company Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort company verticals by name param",
     *    in="query",
     *    name="name",
     *    example="test company vertical",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort company vertical by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort company vertical by pagination",
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
     *       'message': 'Company Vertical has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test company vertical',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null,
     *          'bussines_category': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'name': 'test company vertical',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
     *              'updated_at': '2022-06-25T14:32:54.000000Z',
     *              'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Company Vertical Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $company_verticals = CompanyVertical::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($company_verticals)) {
            return $this->respond([
                'status' => false,
                'message' => 'Company Verticals Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Company Verticals has been Fetched Successfully!',
            'data' => [
                'company_verticals' => CompanyVertialResource::collection($company_verticals)
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
     * path="/api/company-verticals",
     * summary="Create Company Vertical",
     * description="Create Company Vertical",
     * operationId="createCompanyVertical",
     * tags={"Company Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Company Vertical data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test company vertical"),
     *       @OA\Property(property="bussines_category_id", type="integer", format="bussines_address", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *        'status': true,
     *       'message': 'Company Vertical has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test company vertical',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null,
     *          'bussines_category': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'name': 'test company vertical',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
     *              'updated_at': '2022-06-25T14:32:54.000000Z',
     *              'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Company Vertical Not Found")
     *        )
     *     ),
     * )
     */
    public function store(CompanyVerticalRequest $request)
    {
        $company_vertical = new CompanyVertical($request->validated());
        $bussines_category_id = BussinesCategory::getIdByUuid($request->bussines_category_id);
        $company_vertical->bussines_category_id = $bussines_category_id;
        $company_vertical->icon = $request->icon;
        $company_vertical->save();

        if (empty($company_vertical)) {
            return $this->respond([
                'status' => false,
                'message' => 'Company Vertial Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Company Vertial has been Created Successfully!',
            'data' => [
                'company_vertical' => new CompanyVertialResource($company_vertical)
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
     * path="/api/company-verticals/{uuid}",
     * summary="Get Company Vertical",
     * description="Get Company Vertical by uuid",
     * operationId="getCompanyVerticalById",
     * tags={"Company Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Company Vertical",
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
     *       'message': 'Company Vertical has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test company vertical',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null,
     *          'bussines_category': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'name': 'test company vertical',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
     *              'updated_at': '2022-06-25T14:32:54.000000Z',
     *              'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Company Vertical Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $company_vertical = CompanyVertical::where('uuid', $id)->first();
        if (empty($company_vertical)) {
            return $this->respond([
                'status' => false,
                'message' => 'Company Vertial Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Company Vertial has been Fetched Successfully!',
            'data' => [
                'company_vertical' => new CompanyVertialResource($company_vertical)
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
     * path="/api/company-verticals/{uuid}",
     * summary="Update Company Vertical",
     * description="Update Company Vertical",
     * operationId="updateCompanyVertical",
     * tags={"Company Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Company Vertical data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test company vertical"),
     *       @OA\Property(property="bussines_category_id", type="integer", format="bussines_address", example="1"),
     *    ),
     * ),
     * * @OA\Parameter(
     *    description="uuid of Company Vertical",
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
     *       'message': 'Company Vertical has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test company vertical',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null,
     *          'bussines_category': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'name': 'test company vertical',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
     *              'updated_at': '2022-06-25T14:32:54.000000Z',
     *              'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Company Vertical Not Found")
     *        )
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $company_vertical = CompanyVertical::where('uuid', $id)->first();
        if ($request->has('name'))
            $company_vertical->name = $request->name;
        if ($request->has('icon'))
            $company_vertical->icon = $request->icon;
        if ($request->has('bussines_category_id'))
            $bussines_category_id = BussinesCategory::getIdByUuid($request->bussines_category_id);
        $company_vertical->bussines_category_id = $bussines_category_id;
        $company_vertical->save();
        if (empty($company_vertical)) {
            return $this->respond([
                'status' => false,
                'message' => 'Company Vertial Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Company Vertial has been Updated Successfully!',
            'data' => [
                'company_vertical' => new CompanyVertialResource($company_vertical)
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
     * path="/api/company-verticals/{uuid}",
     * summary="Delete Company Vertical",
     * description="Delete existing Company Vertical",
     * operationId="deleteCompanyVertical",
     * tags={"Company Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Company Vertical",
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
     *       'message': 'Company Vertical has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test company vertical',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null,
     *          'bussines_category': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'name': 'test company vertical',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
     *              'updated_at': '2022-06-25T14:32:54.000000Z',
     *              'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="company Vertical Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $company_vertical = CompanyVertical::where('uuid', $id)->first();
        if (empty($company_vertical)) {
            return $this->respond([
                'status' => false,
                'message' => 'Company Vertial Not Found',
                'data' =>  []
            ]);
        }
        $company_vertical->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Company Vertial has been Updated Successfully!',
            'data' => [
                'company_vertical' => new CompanyVertialResource($company_vertical)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/business-verticals/{business_category_id}",
     * summary="Get Company Vertical",
     * description="Get Company Vertical by business_category_id",
     * operationId="getCompanyVerticalBycategory_id",
     * tags={"Company Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="business_category_id of Company Vertical",
     *    in="path",
     *    name="business_category_id",
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
     *       'message': 'Company Vertical has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test company vertical',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null,
     *          'bussines_category': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'name': 'test company vertical',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
     *              'updated_at': '2022-06-25T14:32:54.000000Z',
     *              'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Company Vertical Not Found")
     *        )
     *     ),
     * )
     */

    public function getBusinessCategoriesVerticals($business_category_uuid)
    {
        $id = BussinesCategory::getIdByUuid($business_category_uuid);
        $company_verticals = CompanyVertical::where('bussines_category_id', $id)->get();
        if (empty($company_verticals)) {
            return $this->respond([
                'status' => false,
                'message' => 'Business Vertial Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Business Vertials has been Fetched Successfully!',
            'data' => [
                'business_verticals' => CompanyVertialResource::collection($company_verticals)
            ],
        ]);
    }

    public function getVerticals()
    {

        $company_verticals = CompanyVertical::get();
        if (empty($company_verticals)) {
            return $this->respond([
                'status' => false,
                'message' => 'Business Vertial Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Business Vertials has been Fetched Successfully!',
            'data' => [
                'business_verticals' => CompanyVertialResource::collection($company_verticals)
            ],
        ]);
    }
}
