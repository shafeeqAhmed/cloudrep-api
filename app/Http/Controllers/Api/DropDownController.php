<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Classes\Constants;
use App\Http\Requests\DropDownRequest;
use App\Http\Resources\DropDownResource;
use App\Models\DropDown;
use Illuminate\Http\Request;

class DropDownController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/dropdowns",
     * summary="Get Dropdown",
     * description="Get Dropdown",
     * operationId="getDropdown",
     * tags={"Dropdown"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort Dropdown items by name param",
     *    in="query",
     *    name="name",
     *    example="test Dropdown",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort Dropdown by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort Dropdown by pagination",
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
     *       'message': 'Dropdown has been Fetched Successfully!',
     *      'data': {
     *          'dropdowns': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'ads',
     *             'value': 'ads',
     *             'type': 'agent',
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
     *       @OA\Property(property="message", type="string", example="Dropdown Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $drop_downs = DropDown::when($request->q, function ($query, $q) {
            return $query->where('label', 'LIKE', "%{$q}%");
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
        if (empty($drop_downs)) {
            return $this->respond([
                'status' => false,
                'message' => 'Drop Down Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Drop Downs has been Fetched Successfully!',
            'data' => [
                'dropdowns' => $drop_downs
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
     * path="/api/dropdowns",
     * summary="Create Dropdown",
     * description="Create Dropdown",
     * operationId="createDropdown",
     * tags={"Dropdown"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Dropdown data",
     *    @OA\JsonContent(
     *       required={"label","value","type"},
     *       @OA\Property(property="label", type="string", format="label", example="ads"),
     *       @OA\Property(property="value", type="string", format="value", example="ads"),
     *       @OA\Property(property="type", type="enum", format="type", example="agent"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Dropdown has been Created Successfully!',
     *       'data': {
     *          'dropdowns': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'ads',
     *             'value': 'ads',
     *             'type': 'agent',
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
     *       @OA\Property(property="message", type="string", example="Dropdown Not Found")
     *        )
     *     ),
     * )
     */
    public function store(DropDownRequest $request)
    {
        $value = strtolower($request->label);
        $newDropdown = new DropDown($request->validated());
        $newDropdown->uuid = generateUuid();
        $newDropdown->label = $request->label;
        $newDropdown->value = $value;
        $newDropdown->type = $request->type;
        $newDropdown->save();

        return $this->respond([
            'status' => true,
            'message' => 'Drop Down Item has been Added Successfully!',
            'data' => [
                'drop_down' => new DropDownResource($newDropdown)
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
     * path="/api/dropdowns/{uuid}",
     * summary="Get Dropdown by uuid",
     * description="Get Dropdown by uuid",
     * operationId="getDropdownById",
     * tags={"Dropdown"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Dropdown",
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
     *       'message': 'Dropdown has been Fetched Successfully!',
     *       'data': {
     *          'dropdowns': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'ads',
     *             'value': 'ads',
     *             'type': 'agent',
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
     *       @OA\Property(property="message", type="string", example="Dropdown Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $drop_down = DropDown::where('uuid', $id)->first();
        if (empty($drop_down)) {
            return $this->respond([
                'status' => false,
                'message' => 'Drop Down Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Drop Down has been Fetched Successfully!',
            'data' => [
                'drop_down' => new DropDownResource($drop_down)
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
     * path="/api/dropdowns",
     * summary="Update Dropdown of Current User",
     * description="Update Dropdown",
     * operationId="updateDropdown",
     * tags={"Dropdown"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Dropdown data",
     *     @OA\JsonContent(
     *       required={"label","value","type"},
     *       @OA\Property(property="label", type="string", format="label", example="ads"),
     *       @OA\Property(property="value", type="string", format="value", example="ads"),
     *       @OA\Property(property="type", type="enum", format="type", example="agent"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Dropdown has been Updated Successfully!',
     *         'data': {
     *          'dropdowns': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'ads',
     *             'value': 'ads',
     *             'type': 'agent',
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
     *       @OA\Property(property="message", type="string", example="Dropdonwn Not Found")
     *        )
     *     ),
     * )
     */
    public function update(DropDownRequest $request, $uuid)
    {
        $data = DropDown::where('uuid', $uuid)->first();
        if ($data) {
            $value = strtolower($request->label);
            $data->label = $request->label;
            $data->value = $value;
            $data->type = $request->type;
            $data->update();
            return $this->respond([
                'status' => true,
                'message' => 'Drop Down Item Updated Successfully!',
                'data' => [
                    'drop_down' => new DropDownResource($data)
                ],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     * path="/api/dropdowns/{uuid}",
     * summary="Delete Dropdown",
     * description="Delete existing Dropdown",
     * operationId="deleteDropdown",
     * tags={"Dropdown"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Dropdown",
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
     *       'message': 'Dropdown has been Deleted Successfully!',
     *        'data': {
     *          'dropdowns': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'ads',
     *             'value': 'ads',
     *             'type': 'agent',
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
     *       @OA\Property(property="message", type="string", example="Dropdown Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $drop_down = DropDown::where('uuid', $id)->first();
        if (empty($drop_down)) {
            return $this->respond([
                'status' => false,
                'message' => 'Drop Down Not Found',
                'data' =>  []
            ]);
        }
        $drop_down->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Drop Down has been Deleted Successfully!',
            'data' => [
                'drop_down' => new DropDownResource($drop_down)
            ],
        ]);
    }

    public function storeDropdownItem(Request $request)
    {
        $request->validate([
            'label' => 'required',
            'type' => 'required'
        ]);
        $value = strtolower($request->label);
        // return response()->json($value);
        $newDropdown = new DropDown();
        $newDropdown->uuid = generateUuid();
        $newDropdown->label = $request->label;
        $newDropdown->value = $value;
        $newDropdown->type = $request->type;
        $newDropdown->save();

        return $this->respond([
            'status' => true,
            'message' => 'Drop Down Item has been Added Successfully!',
            'data' => [
                'drop_down' => new DropDownResource($newDropdown)
            ],
        ]);
    }

    public function updateDropdownItem(Request $request)
    {
        $request->validate([
            'label' => 'required',
            'type' => 'required'
        ]);
        $data = DropDown::where('uuid', $request->uuid)->first();
        if ($data) {
            $value = strtolower($request->label);
            $data->label = $request->label;
            $data->value = $value;
            $data->type = $request->type;
            $data->update();
            return $this->respond([
                'status' => true,
                'message' => 'Drop Down Item Updated Successfully!',
                'data' => [
                    'drop_down' => new DropDownResource($data)
                ],
            ]);
        }
    }

    public function deleteDropdownItem(Request $request)
    {
        $data = DropDown::where('uuid', $request->uuid)->delete();
        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'DropDown item Deleted Successfully!',
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'DropDown not found!',
            ]);
        }
    }

    public function allDropdownList(Request $request)
    {
        $drop_downs = DropDown::when($request->q, function ($query, $q) {
            return $query->where('label', 'LIKE', "%{$q}%");
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
        if (empty($drop_downs)) {
            return $this->respond([
                'status' => false,
                'message' => 'Drop Down Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Drop Downs has been Fetched Successfully!',
            'data' => [
                'dropdowns' => $drop_downs
            ],
        ]);
    }

    public function getSingleDropdown(Request $request)
    {
        // return response()->json($request->uuid);
        $data = DropDown::where('uuid', $request->uuid)->first();
        return $this->respond([
            'status' => true,
            'message' => 'DropDown item Fetched Successfully!',
            'data' => [
                'drop_down' => new DropDownResource($data)
            ],
        ]);
    }

    public function getDropdownOptions(Request $request)
    {
        return response()->json($request->all());
    }

    public function getDropDownByType(Request $request)
    {
        $dropdown_type = DropDown::where('type', $request->type)->pluck('value', 'label');
        if (empty($dropdown_type)) {
            return $this->respondNotFound('Dropdown Type not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Dropdown Type has been fetched successfully',
            'data' => [
                'dropdown_type' => $dropdown_type
            ],
        ]);
    }
}
