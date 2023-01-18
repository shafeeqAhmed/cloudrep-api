<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\LmsCategoryResource;
use App\Models\LmsCategory;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/categories",
     * summary="Get Categories",
     * description="Get Categories",
     * operationId="getCategories",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort category by name param",
     *    in="query",
     *    name="name",
     *    example="test category",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort category by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort category by pagination",
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
     *       'message': 'Categories has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Categories Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $categories = LmsCategory::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($categories)) {
            return $this->respond([
                'status' => false,
                'message' => 'Categories Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Categories has been Fetched  Successfully!',
            'data' => [
                'categories' => LmsCategoryResource::collection($categories)
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
     * path="/api/categories",
     * summary="Create Category",
     * description="Create Category",
     * operationId="createCategory",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass category data",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="name", example="test category"),
     *       @OA\Property(property="description", type="string", format="description", example="test description"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="parent_id", type="integer", format="parent_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Category has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Category Not Found")
     *        )
     *     ),
     * )
     */
    public function store(CategoryRequest $request)
    {
        $category = new LmsCategory($request->validated());
        $category->is_active = $request->has('is_active') ? $request->is_active : 'true';
        $category->parent_id = $request->parent_id;
        $category->save();

        if (empty($category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Category has been Created Successfully!',
            'data' => [
                'category' => new LmsCategoryResource($category)
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
     * path="/api/categories/{category_uuid}",
     * summary="Get Category By category_uuid",
     * description="Get Category By category_uuid",
     * operationId="getCategoryById",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="category_uuid of Category",
     *    in="path",
     *    name="category_uuid",
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
     *       'message': 'Category has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Category Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $category = LmsCategory::where('lms_category_uuid', $id)->first();
        if (empty($category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Category has been Fetched Successfully!',
            'data' => [
                'category' => new LmsCategoryResource($category)
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
     * path="/api/categories/{category_uuid}",
     * summary="Update Category",
     * description="Update Category",
     * operationId="updateCategory",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass category data",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="name", example="test category"),
     *       @OA\Property(property="description", type="string", format="description", example="test description"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="parent_id", type="integer", format="parent_id", example="1"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="category_uuid of Category",
     *    in="path",
     *    name="category_uuid",
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
     *       'message': 'Category has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Category Not Found")
     *        )
     *     ),
     * )
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = LmsCategory::where('lms_category_uuid', $id)->first();
        $data = $request->validated();
        if ($request->has('is_active'))
            $data['is_active'] = $request->is_active;
        $category->update($data);

        if (empty($category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Category has been Updated Successfully!',
            'data' => [
                'category' => new LmsCategoryResource($category)
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
     * path="/api/categories/{category_uuid}",
     * summary="Delete existing Category",
     * description="Delete category",
     * operationId="deleteCategory",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * * * @OA\Parameter(
     *    description="category_uuid of Category",
     *    in="path",
     *    name="category_uuid",
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
     *       'message': 'Category has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Category Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $category = LmsCategory::where('lms_category_uuid', $id)->first();
        if (empty($category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Category Not Found',
                'data' =>  []
            ]);
        }
        $category->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Category has been Deleted Successfully!',
            'data' => [
                'category' => new LmsCategoryResource($category)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/parent-categories/{parent_id}",
     * summary="Get Category By parent_id",
     * description="Get Category By parent_id",
     * operationId="getCategoryByParentId",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="parent_id of Category",
     *    in="path",
     *    name="parent_id",
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
     *       'message': 'Category has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Category Not Found")
     *        )
     *     ),
     * )
     */

    public function getCategoryByParent($parent_id)
    {
        $parent_categories = LmsCategory::where('parent_id', $parent_id)->get();
        if (empty($parent_categories)) {
            return $this->respond([
                'status' => false,
                'message' => 'Parent Categories Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Parent Categories has been Fetched Successfully!',
            'data' => LmsCategoryResource::collection($parent_categories)
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/categories-list",
     * summary="Get Categories List",
     * description="Get Categories List",
     * operationId="getCategoriesList",
     * tags={"Category"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort category by type param",
     *    in="query",
     *    name="type",
     *    example="parent,child,all",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Categories List has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test category',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'parent_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Categories List Not Found")
     *        )
     *     ),
     * )
     */

    public function getCategoryList(Request $request)
    {
        if ($request->type == 'parent') {
            $categories_list = LmsCategory::where('parent_id', null)->get();
        } else if ($request->type == 'child') {
            $categories_list = LmsCategory::where('parent_id', '!=', null)->get();
        } else if ($request->type == 'all') {
            $categories_list = LmsCategory::all();
        }
        if (empty($categories_list)) {
            return $this->respond([
                'status' => false,
                'message' => 'Categories List Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Categories List has been Fetched Successfully!',
            'data' => LmsCategoryResource::collection($categories_list)
        ]);
    }
}
