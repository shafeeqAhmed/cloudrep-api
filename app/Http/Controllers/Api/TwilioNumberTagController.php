<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TwilioNumberResource;
use App\Http\Resources\TwilioNumberTagResource;
use Illuminate\Http\Request;
use App\Models\TwilioNumberTag;
use App\Models\TwillioNumber;

class TwilioNumberTagController extends ApiController
{

    /**
     * @OA\Get(
     * path="/api/get-twilio-number-tag",
     * summary="Get Twilio Number Tag",
     * description="Get Twilio Number Tag",
     * operationId="getTwilioNumberTag",
     * tags={"Twilio Number Tag"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sorte twilion number tag by name param",
     *    in="query",
     *    name="name",
     *    example="test tag",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort twilio number tag by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort twilio number tag by pagination",
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
     *       'message': 'Twilio Number Tag has been created successfully!',
     *       'data': {
     *          'twilioNumberTag': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'twilio_number_id': '1',
     *              'name': 'test tag',
     *              'tag_name': 'test key',
     *              'tag_value': 'test value',
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
     *       @OA\Property(property="message", type="string", example="Campaign Reporting not found")
     *        )
     *     ),
     * )
     */

    public function getTwilioNumberTag(Request $request)
    {
        $twilioNumberTag = TwilioNumberTag::getTwilioNumberTag($request);
        if (empty($twilioNumberTag)) {
            return $this->respondNotFound('Twilio Number Tag not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Twilio Number Tags has been fetched successfully!',
            'data' => [
                'twilioNumberTag' => $twilioNumberTag
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-twilio-number-tag",
     * summary="Create Twilio Number Tag",
     * description="Create Twilio Number Tag",
     * operationId="createTwilioNumberTag",
     * tags={"Twilio Number Tag"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Twilio Number Tag data",
     *    @OA\JsonContent(
     *       required={"twilio_number_uuid"},
     *       @OA\Property(property="twilio_number_uuid",type="string", format="twilio_number_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="name", type="string", format="name", example="test tag"),
     *       @OA\Property(property="tag_name", type="string", format="tag_name", example="test key"),
     *       @OA\Property(property="tag_value", type="string", format="tag_value", example="test value"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Twilio Number Tag has been created successfully!',
     *       'data': {
     *          'twilioNumberTag': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'twilio_number_id': '1',
     *              'name': 'test tag',
     *              'tag_name': 'test key',
     *              'tag_value': 'test value',
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
     *       @OA\Property(property="message", type="string", example="Twilio Number Tag Not Found")
     *        )
     *     ),
     * )
     */

    public function storeTwilioNumberTag(Request $request)
    {

        $request->validate([
            'twilio_number_uuid' => 'required|uuid',
        ]);
        $number = TwillioNumber::getRecord('uuid', $request->twilio_number_uuid);
        $twilioNumberId = $number->id;
        $record = [];
        foreach ($request->lists as $list) {
            $temp = [];
            $temp['uuid'] = generateUuid();
            $temp['twilio_number_id']  = $twilioNumberId;
            $temp['name']  = $request->name;
            $temp['tag_name'] = $list['key'];
            $temp['tag_value'] = $list['value'];
            $temp['created_at'] = now();
            $record[] = $temp;
        }
        $number->name = $request->name;
        $number->save();
        if ($twilioNumberId) {
            TwilioNumberTag::where('twilio_number_id', $twilioNumberId)->forceDelete();
            $twilioNumberTag = TwilioNumberTag::insert($record);
        } else {
            $twilioNumberTag = TwilioNumberTag::insert($record);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Twilio Number Tag has been created successfully!',
            'data' => [
                'twilioNumberTag' => $twilioNumberTag,
            ],
        ]);
    }

     /**
     * @OA\Post(
     * path="/api/update-twilio-number-tag",
     * summary="Update Twilio Number Tag",
     * description="Update Twilio Number Tag",
     * operationId="updateTwilioNumberTag",
     * tags={"Twilio Number Tag"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
    *    description="Update Twilio Number Tag by uuid param",
    *    in="query",
    *    name="uuid",
    *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
    *    @OA\Schema(
    *       type="string"
    *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Twilio Number Tag data",
     *    @OA\JsonContent(
     *       required={"twilio_number_uuid"},
     *       @OA\Property(property="twilio_number_uuid",type="string", format="twilio_number_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="name", type="string", format="name", example="test tag"),
     *       @OA\Property(property="tag_name", type="string", format="tag_name", example="test key"),
     *       @OA\Property(property="tag_value", type="string", format="tag_value", example="test value"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Twilio Number Tag has been updated successfully!',
     *       'data': {
     *          'twilioNumberTag': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'twilio_number_id': '1',
     *              'name': 'test tag',
     *              'tag_name': 'test key',
     *              'tag_value': 'test value',
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
     *       @OA\Property(property="message", type="string", example="Twilio Number Tag Not Found")
     *        )
     *     ),
     * )
     */

    public function updateTwilioNumberTag(Request $request)
    {

        $request->validate([
            'twilio_number_uuid' => 'required|uuid',
        ]);

        $twilioNumberTag = TwilioNumberTag::getTwilioNumberTagByUuid('uuid', $request->uuid);
        $data = $request->all();
        $twilioNumberId = TwillioNumber::getIdByUuid($request->twilio_number_uuid);
        $data['twilio_number_id'] = $twilioNumberId;
        $twilioNumberTag->update($data);

        return $this->respond([
            'status' => true,
            'message' => 'Twilio Number Tag has been updated successfully!',
            'data' => [
                'twilioNumberTag' => new TwilioNumberTagResource($twilioNumberTag),
            ],
        ]);
    }
}
