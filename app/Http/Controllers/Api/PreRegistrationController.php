<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DropDownResource;
use App\Http\Resources\PreRegistrationResource;
use App\Models\BussinesCategory;
use App\Models\DropDown;
use App\Models\PreRegistration;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use Carbon\Carbon;

class PreRegistrationController extends APiController
{
    /**
     * @OA\Get(
     * path="/api/fetch-buisness-scale-type",
     * summary="Get Business Scale Type",
     * description="Get Business Scale Type",
     * operationId="getBusinessScaleType",
     * tags={"Pre Registration"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort dropdown by type",
     *    in="query",
     *    name="type",
     *    example="business_scale_type",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'dropdown against business scale type fetched successfully!',
     *       'data': {
     *          'dropdowns': [
     *              {
     *                  'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                  'label': 'Earning an income',
     *                  'value': 'earning an income',
     *                  'type': 'business_scale_type',
     *                  'created_at': '2022-06-04T18:32:20.000000Z',
     *              },
     *              {
     *                  'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                  'label': 'Scale My business',
     *                  'value': 'scale my business',
     *                  'type':  'business_scale_type',
     *                  'created_at': '2022-06-04T18:32:20.000000Z',
     *              }
     *          ]
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
     *       @OA\Property(property="message", type="string", example="Dropdown not found")
     *        )
     *     ),
     * )
     */

    public function fetchBusinessScaleType()
    {
        $records = DropDown::where('type', 'business_scale_type')->get();
        return $this->respond([
            'status' => true,
            'message' => 'Dropdown value againts business scale type fetched Successfully!',
            'data' => [
                'dropdowns' => DropDownResource::collection($records)
            ],
        ]);
    }

    public function fetchCloudrepWorkType()
    {
        $records = DropDown::where('type', 'work_type_with_cloudrep')->get();
        return $this->respond([
            'status' => true,
            'message' => 'Dropdown value againts work type with Cloudrep fetched Successfully!',
            'data' => [
                'dropdowns' => DropDownResource::collection($records)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-selected-record",
     * summary="Get Selected Record",
     * description="Get Selected Record",
     * operationId="getSelectedRecord",
     * tags={"Pre Registration"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort pre registration by ip address",
     *    in="query",
     *    name="ip_address",
     *    example="127.0.0.1",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort dropdown by value",
     *    in="query",
     *    name="value",
     *    example="scale my business",
     *    @OA\Schema(
     *        type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Pre Registration fetched successfully!',
     *       'data': {
     *          'PreRegistration': {
     *                  'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                  'name': 'test pre registration',
     *                  'email': null,
     *                  'business_scale_type': 'start my business',
     *                  'phone_no': null,
     *                  'created_at': '2022-06-04T18:32:20.000000Z',
     *              },
     *           'dropdown': {
     *                  'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                  'label': 'Start My business',
     *                  'value': 'start my business',
     *                  'type':  'business_scale_type',
     *                  'created_at': '2022-06-04T18:32:20.000000Z',
     *              }
     *          ]
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
     *       @OA\Property(property="message", type="string", example="Dropdown not found")
     *        )
     *     ),
     * )
     */

    public function FetchSelectedRecord(Request $request)
    {
        $record = PreRegistration::where('ip_address', $request->ip())->first();
        if ($record) {
            // if($record->business_scale_type) {
            $dropdown = DropDown::where('value', $record->business_scale_type)->first();
            // }
            // if($record->work_type_with_cloudrep) {
            $dropdown2 = DropDown::where('value', $record->work_type_with_cloudrep)->first();
            // } else {
            //     $dropdown2 = [];
            // }
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration fetched Successfully!',
                'data' => [
                    'preRegistration' => new PreRegistrationResource($record),
                    'dropdown' => $dropdown ? new DropDownResource($dropdown) : [],
                    'dropdown2' => $dropdown2 ? new DropDownResource($dropdown2) : []
                ],
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration Not found!',
                'data' => [],
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/store-preg-step-one",
     * summary="Create Pre Registration Step One",
     * description="Create Pre Registration Step One",
     * operationId="createPreRegistrationStepOne",
     * tags={"Pre Registration"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Pre Registration Step One data",
     *    @OA\JsonContent(
     *       required={"busniess_scale_type"},
     *       @OA\Property(property="business_scale_type", type="string", format="business_scale_type", example="36973573-1b28-4ad9-92f9-a1df6bb599d8")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Pre Registration Step One has been created successfully!',
     *       'data': {
     *          'preRegistration': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'name': null,
     *             'email': null,
     *             'business_scale_type': 'start my business',
     *             'phone_no': null,
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
     *       @OA\Property(property="message", type="string", example="Pre Registration not created")
     *        )
     *     ),
     * )
     */

    public function storeStepOne(Request $request)
    {
        $request->validate([
            'business_scale_type' => 'required'
        ]);

        $dropdown = DropDown::where('uuid', $request->business_scale_type)->first();
        $exsist = PreRegistration::where('ip_address', $request->ip())->first();
        if($exsist)
        {
            $exsist->business_scale_type = $dropdown->value;
            $exsist->update();
            if ($exsist) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Pre Registration Step One Updated Successfully!',
                    'data' => [
                        'preRegistration' => new PreRegistrationResource($exsist)
                    ],
                ]);
            }
        }else{
            $newRecord = new PreRegistration();
            $newRecord->ip_address = $request->ip();
            $newRecord->business_scale_type = $dropdown->value;
            $newRecord->save();
            if ($newRecord) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Pre Registration Step One Stored Successfully!',
                    'data' => [
                        'preRegistration' => new PreRegistrationResource($newRecord)
                    ],
                ]);
            }
        }

    }

    /**
     * @OA\Post(
     * path="/api/store-preg-step-two",
     * summary="Create Pre Registration Step Two",
     * description="Create Pre Registration Step Two",
     * operationId="createPreRegistrationStepTwo",
     * tags={"Pre Registration"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Pre Registration Step Two data",
     *    @OA\JsonContent(
     *       required={"uuid,name"},
     *       @OA\Property(property="uuid", type="string", format="uuid", example="36973573-1b28-4ad9-92f9-a1df6bb599d8"),
     *       @OA\Property(property="name", type="string", format="name", example="test pre registration")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Pre Registration Step Two has been Created Successfully!',
     *       'data': {
     *          'preRegistration': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'name': null,
     *             'email': null,
     *             'business_scale_type': 'start my business',
     *             'phone_no': null,
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
     *       @OA\Property(property="message", type="string", example="Pre Registration not created")
     *        )
     *     ),
     * )
     */

    public function storeStepTwo(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $preRegistration = PreRegistration::where('uuid', $request->uuid)->first();
        $preRegistration->name = $request->name;
        $preRegistration->update();
        if ($preRegistration) {
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration Step Two Stored Successfully!',
                'data' => [
                    'preRegistration' => new PreRegistrationResource($preRegistration)
                ],
            ]);
        }
    }
    public function storeStepThree(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $preRegistration = PreRegistration::where('uuid', $request->uuid)->first();
        $preRegistration->email = $request->email;
        $preRegistration->update();
        if ($preRegistration) {
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration Step Three Stored Successfully!',
                'data' => [
                    'preRegistration' => new PreRegistrationResource($preRegistration)
                ],
            ]);
        }
    }
    public function storeStepFour(Request $request)
    {
        $request->validate([
            'phone_no' => 'required'
        ]);

        $preRegistration = PreRegistration::where('uuid', $request->uuid)->first();
        if($preRegistration->phone_no == $request->phone_no && $preRegistration->is_verified == 1){
            return $this->respond([
                'status' => false,
                'message' => 'Phone number already varified!',
                'data' => [],
            ]);
        }else{
            $preRegistration->phone_no = $request->phone_no;
            $preRegistration->is_verified = null;
            $preRegistration->update();
            if ($preRegistration) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Pre Registration Step Four Stored Successfully!',
                    'data' => [
                        'preRegistration' => new PreRegistrationResource($preRegistration)
                    ],
                ]);
            }
        }

    }

    //Step 5 for otp verification
    public function getTwoFa(Request $request)
    {
        $record = PreRegistration::where('ip_address', $request->ip())->first();
        $code = VerificationCode::generatedVerificationCodeForPreReg($record->id);

        return $this->respond([
            'status' => true,
            'message' => 'OTP has been send on your register number',
            'data' => [
                'code' => $code
            ]
        ]);
    }

    public function verifyTwoFa(Request $request)
    {
        $request->validate([
            'code' => 'required|min:4|max:4',
        ]);
        $record = PreRegistration::where('ip_address', $request->ip())->first();
        $result =  VerificationCode::verifyTwoFaPreReg($record->id, $request->code);

        if ($result) {
            $record->is_verified = true;
            $record->update();
        }

        return $this->respond([
            'status' => $result == 1 ? true : false,
            'message' => $result == 1 ? 'OTP has been verified Successfully!' : 'OTP is not correct please try again!',
            'data' => []
        ]);
    }

    public function storeStepSix(Request $request)
    {
        $request->validate([
            'work_type' => 'required'
        ]);

        $dropdown = DropDown::where('uuid', $request->work_type)->first();
        $preRegistration = PreRegistration::where('uuid', $request->uuid)->first();
        $preRegistration->work_type_with_cloudrep = $dropdown->value;
        $preRegistration->update();
        if ($preRegistration) {
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration Step Five Stored Successfully!',
                'data' => [
                    'preRegistration' => new PreRegistrationResource($preRegistration)
                ],
            ]);
        }
    }

    public function storeStepEight(Request $request)
    {
        $request->validate([
            'business_category' => 'required'
        ]);

        $bCategory = BussinesCategory::getIdByUuid($request->business_category);
        $preRegistration = PreRegistration::where('uuid', $request->uuid)->first();
        $preRegistration->business_category = $bCategory;
        $preRegistration->update();
        if ($preRegistration) {
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration Step Eight Stored Successfully!',
                'data' => [
                    'preRegistration' => new PreRegistrationResource($preRegistration)
                ],
            ]);
        }
    }

    public function storeStepNine(Request $request)
    {
        $request->validate([
            'business_name' => 'required'
        ]);

        $preRegistration = PreRegistration::where('uuid', $request->uuid)->first();
        $preRegistration->business_name = $request->business_name;
        $preRegistration->update();
        if ($preRegistration) {
            return $this->respond([
                'status' => true,
                'message' => 'Pre Registration Step Nine Stored Successfully!',
                'data' => [
                    'preRegistration' => new PreRegistrationResource($preRegistration)
                ],
            ]);
        }
    }

}
