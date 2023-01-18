<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SystemSettingResource;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemSettingController extends ApiController
{
    public function index(Request $request)
    {
        $settings = SystemSetting::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            // ->when($request->type, function ($query, $type) {
            //     return $query->where('label', 'LIKE', "%{$type}%");
            // })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($settings)) {
            return $this->respond([
                'status' => false,
                'message' => 'Setting Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Settings has been Fetched Successfully!',
            'data' => [
                'settings' => $settings
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'value' => 'required'
        ]);

        $newSystemSetting = new SystemSetting();
        $newSystemSetting->name = $request->type;
        $newSystemSetting->value = $request->value;
        $newSystemSetting->save();

        return $this->respond([
            'status' => true,
            'message' => 'Setting has been Added Successfully!',
            'data' => [
                'setting' => new SystemSettingResource($newSystemSetting)
            ],
        ]);
    }

    // public function show($id)
    // {
    //     $drop_down = DropDown::where('uuid', $id)->first();
    //     if (empty($drop_down)) {
    //         return $this->respond([
    //             'status' => false,
    //             'message' => 'Drop Down Not Found',
    //             'data' =>  []
    //         ]);
    //     }
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Drop Down has been Fetched Successfully!',
    //         'data' => [
    //             'drop_down' => new DropDownResource($drop_down)
    //         ],
    //     ]);
    // }

    public function update(Request $request, $uuid)
    {
        // return response()->json($request->all());
        $request->validate([
            'type' => 'required',
            'value' => 'required'
        ]);

        $SystemSetting = SystemSetting::where('setting_uuid', $uuid)->first();
        $SystemSetting->name = $request->type;
        $SystemSetting->value = $request->value;
        $SystemSetting->update();

        return $this->respond([
            'status' => true,
            'message' => 'Setting has been Updated Successfully!',
            'data' => [
                'setting' => new SystemSettingResource($SystemSetting)
            ],
        ]);
    }

    public function destroy($uuid)
    {
        $setting = SystemSetting::where('setting_uuid', $uuid)->first();
        if (empty($setting)) {
            return $this->respond([
                'status' => false,
                'message' => 'Setting Not Found',
                'data' =>  []
            ]);
        }
        $setting->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Setting has been Deleted Successfully!',
            'data' => [
                'setting' => new SystemSettingResource($setting)
            ],
        ]);
    }











    /**
     * @OA\Get(
     * path="/api/setting/name",
     * summary="Get Setting",
     * description="Get User By name",
     * operationId="Get User By name",
     * tags={"Setting"},
     * security={ {"bearer": {} }},
     * @OA\Parameter(
     *    description="Name of Setting",
     *    in="path",
     *    name="name",
     *    required=true,
     *    example="test setting",
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
     *       'message': 'Setting has been Fetched Successfully!',
     *       'data': {
     *          'id': 2,
     *          'setting_uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'test setting',
     *          'value': 'test setting',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Setting Not Found")
     *        )
     *     ),
     * )
     */
    // public function getSetting($name)
    // {
    //     $settings = SystemSetting::where('name', $name)->first();
    //     if (empty($settings)) {
    //         return $this->respond([
    //             'status' => false,
    //             'message' => 'Setting Not Found',
    //             'data' =>  []
    //         ]);
    //     }
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Setting has been Fetched Successfully!',
    //         'data' => [
    //             'settings' => SystemSettingResource::collection($settings)
    //         ],
    //     ]);
    // }
    /**
     * @OA\Post(
     * path="/api/save-setting",
     * summary="Store Setting",
     * description="Store Setting by name, value",
     * operationId="createSetting",
     * tags={"Setting"},
     * security={ {"bearer": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass setting data",
     *    @OA\JsonContent(
     *       required={"name","value"},
     *       @OA\Property(property="name", type="string", format="name", example="test name"),
     *       @OA\Property(property="value", type="string", format="value", example="test value"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Setting has been Created Successfully!',
     *       'data': {
     *          'id': 2,
     *          'setting_uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
     *          'name': 'test setting',
     *          'value': 'test setting',
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Setting Not Found")
     *        )
     *     ),
     * )
     */
    // public function saveSetting(Request $request)
    // {
    //     $data = $request->all();
    //     $validator = Validator::make($data, [
    //         'name' => 'required',
    //         'value' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->respond([
    //             'status' => false,
    //             'message' => 'Validation Error',
    //             'data' =>  $validator->errors()
    //         ]);
    //     }
    //     $setting = SystemSetting::updateOrCreate([
    //         'name' => $data['name'],
    //     ], [
    //         'value' => $data['value'],
    //     ]);
    //     if (empty($setting)) {
    //         return $this->respond([
    //             'status' => false,
    //             'message' => 'Setting Not Found',
    //             'data' =>  []
    //         ]);
    //     }
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Setting has been Set Successfully!',
    //         'data' => [
    //             'setting' => new SystemSettingResource($setting)
    //         ],
    //     ]);
    // }
}
