<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserApplicationResource;
use App\Models\UserApplicationSetting;
use Illuminate\Http\Request;

class UserApplicationSettingController extends ApiController
{

    /**
     * @OA\Post(
     * path="/api/user-application-setting",
     * summary="Create User Application Setting",
     * description="Create User Application Setting",
     * operationId="createUserApplicationSetting",
     * tags={"User Application Setting"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass User Application Setting data",
     *    @OA\JsonContent(
     *       @OA\Property(property="is_dark_mode", type="boolean", format="is_dark_mode", example="true/false"),
     *       @OA\Property(property="favMenu", type="string", format="favMenu", example="[{'title'=>'test title','icon'=>'test icon','route'=>'test route'},{'title'=>'test title2','icon'=>'test icon2','route'=>'test route2'}]"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User Application Setting has been created successfully!',
     *       'data': {
     *          'userApplicationSetting': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'user_id': '1',
     *              'name': 'test tag',
     *              'is_dark_mode': 'false',
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
     *       @OA\Property(property="message", type="string", example="User Application Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function storeUserApplicationSetting(Request $request)
    {
        $userApplicationSetting = UserApplicationSetting::updateOrCreate([
            'user_id'   => $request->user()->id,
        ], [
            'user_id'     => $request->user()->id,
            'is_dark_mode' => $request->has('is_dark_mode') ? $request->boolean('is_dark_mode') : true,
            'fav_menu'    => json_encode($request->favMenu),
        ]);

        return $this->respond([
            'status' => true,
            'message' => 'User Application Setting has been added successfully',
            'data' => [
                'userApplicationSetting' => new UserApplicationResource($userApplicationSetting),
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-user-application-setting",
     * summary="Get User Application Setting",
     * description="Get User Application Setting",
     * operationId="getUserApplicationSetting",
     * tags={"User Application Setting"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="get user_application_settings by user_id",
     *    in="query",
     *    name="user_id",
     *    example="1",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User Application Settings has been Fetched Successfully!',
     *       'data': {
     *          'userApplicationSetting': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'user_id': '1',
     *              'name': 'test tag',
     *              'is_dark_mode': 'false',
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
     *       @OA\Property(property="message", type="string", example="User Application Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function getUserApplicationSetting(Request $request)
    {
        $userApplicationSetting = UserApplicationSetting::where('user_id', $request->user()->id)->first();
        if (!empty($userApplicationSetting)) {
            return $this->respond([
                'status' => true,
                'message' => 'User Application Setting has been fetched successfully',
                'data' => [
                    'userApplicationSetting' => new UserApplicationResource($userApplicationSetting),
                ],
            ]);
        }
    }
    public function updateUserApplicationSetting(Request $request)
    {
        $userApplicationSetting = UserApplicationSetting::updateOrCreate([
            'user_id'   => $request->user()->id,
        ], [
            'user_id'     => $request->user()->id,
            $request->key => $request->value,
        ]);

        return $this->respond([
            'status' => true,
            'message' => 'User Application Setting has been added successfully',
            'data' => [
                'userApplicationSetting' => new UserApplicationResource($userApplicationSetting),
            ],
        ]);
    }
}
