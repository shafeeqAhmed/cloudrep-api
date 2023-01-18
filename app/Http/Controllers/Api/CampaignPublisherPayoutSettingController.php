<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CampaignPublisherPayoutSettingRequest;
use App\Http\Resources\CampaignPublisherPayoutSettingResource;
use App\Models\Campaign;
use App\Models\CampaignPublisherPayoutSetting;
use Illuminate\Http\Request;
use App\Models\User;

class CampaignPublisherPayoutSettingController extends ApiController
{
    /**
     * @OA\Post(
     * path="/api/store-publisher-payout-settings",
     * summary="Create Publisher Payout Setting",
     * description="Create Publisher Payout Setting",
     * operationId="createCampaignPublisherPayoutSetting",
     * tags={"Publisher Payout Setting"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Payout Setting data",
     *    @OA\JsonContent(
     *       @OA\Property(property="payout_type", type="enum", format="payout_type", example="fixed amount/revshare percentage"),
     *       @OA\Property(property="payout_on", type="string", format="payout_on", example="call length"),
     *       @OA\Property(property="length", type="integer", format="length", example="5.5"),
     *       @OA\Property(property="payout_amount", type="integer", format="payout_amount", example="100"),
     *       @OA\Property(property="revshare_payout_limits", type="boolean", format="revshare_payout_limits", example="true/false"),
     *       @OA\Property(property="min", type="integer", format="min", example="0"),
     *       @OA\Property(property="max", type="integer", format="max", example="1"),
     *       @OA\Property(property="duplicate_payouts", type="enum", format="duplicate_payouts", example="Disable/Enable/Time Limits"),
     *       @OA\Property(property="days", type="integer", format="days", example="5"),
     *       @OA\Property(property="hours", type="integer", format="hours", example="5.5"),
     *       @OA\Property(property="payout_hours", type="boolean", format="payout_hours", example="true/false"),
     *       @OA\Property(property="open_time", type="time", format="open_time", example="10:45:00"),
     *       @OA\Property(property="close_time", type="time", format="close_time", example="10:50:00"),
     *       @OA\Property(property="start_break_time", type="time", format="start_break_time", example="12:30:02"),
     *       @OA\Property(property="break_duration" , type="time", format="break_time", example="30min"),
     *       @OA\Property(property="time_zone", type="string", format="time_zone", example="+GMT Canada"),
     *       @OA\Property(property="limit_payout", type="boolean", format="limit_payout", example="true/false"),
     *       @OA\Property(property="global_cap", type="boolean", format="global_cap", example="true/false"),
     *       @OA\Property(property="global_payout_cap", type="boolean", format="global_payout_cap", example="true/false"),
     *       @OA\Property(property="monthly_cap", type="boolean", format="monthly_cap", example="true/false"),
     *       @OA\Property(property="monthly_payout_cap", type="boolean", format="monthly_payout_cap", example="true/false"),
     *       @OA\Property(property="daily_cap", type="boolean", format="daily_cap", example="true/false"),
     *       @OA\Property(property="daily_payout_cap", type="boolean", format="daily_payout_cap", example="true/false"),
     *       @OA\Property(property="hourly_cap", type="boolean", format="hourly_cap", example="true/false"),
     *       @OA\Property(property="hourly_payout_cap", type="boolean", format="hourly_payout_cap", example="true/false"),
     *       @OA\Property(property="concurrency_cap", type="boolean", format="concurrency_cap", example="true/false"),
     *       @OA\Property(property="user_uuid", type="string", format="user_uuid", example="5966ac42-2b31-4aeb-ad95-fec77cfc6154"),
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuid", example="5966ac42-2b31-4aeb-ad95-fec77cfc6154")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Payout Setting has been Created Successfully!',
     *       'data': {
     *          'publisher_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'payout_type': 'fixed amount',
     *             'payout_on': 'call type',
     *             'length': '5',
     *             'payout_amount', '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days': '10',
     *             'hours': '5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'start_break_time': '12:30:02',
     *             'break_duration': '30 min',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap': 'true',
     *             'concurrency_cap': 'true',
     *             'user_id': '1',
     *             'campaign_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Publisher Payout Setting Not Found")
     *        )
     *     ),
     * )
     */
    public function storeCampaignPublisherPayoutSetting(Request $request)
    {
        $publisher_payout_setting = new CampaignPublisherPayoutSetting($request->all());
        $user_id = User::getIdByUuid($request->user_uuid);

        $publisher_payout_setting->user_id = $user_id;
        $publisher_payout_setting->campaign_id = Campaign::getIdByUuid($request->campaign_uuid);

        // $publisher_payout_setting->revshare_payout_limits = getBooleanStatus($request, 'revshare_payout_limits', false);
        $publisher_payout_setting->payout_type = $request->has('payout_type') ? $request->payout_type : 'fixed_amount';
        $publisher_payout_setting->revshare_payout_limits = $request->boolean('revshare_payout_limits');
        $publisher_payout_setting->payout_hours = $request->boolean('payout_hours');
        $publisher_payout_setting->limit_payout = $request->boolean('limit_payout');
        $publisher_payout_setting->global_cap = $request->boolean('global_cap');
        $publisher_payout_setting->global_payout_cap = $request->boolean('global_payout_cap');
        $publisher_payout_setting->monthly_cap = $request->boolean('monthly_cap');
        $publisher_payout_setting->monthly_payout_cap = $request->boolean('monthly_payout_cap');
        $publisher_payout_setting->daily_cap = $request->boolean('daily_cap');
        $publisher_payout_setting->daily_payout_cap = $request->boolean('daily_payout_cap');
        $publisher_payout_setting->hourly_cap = $request->boolean('hourly_cap');
        $publisher_payout_setting->hourly_payout_cap = $request->boolean('hourly_payout_cap');
        $publisher_payout_setting->concurrency_cap = $request->boolean('concurrency_cap');
        $publisher_payout_setting->duplicate_payouts = $request->has('duplicate_payouts') ? $request->duplicate_payouts : 'disable';
        $publisher_payout_setting->save();

        $data['step'] = 10;
        Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $user_id])->update($data);

        return $this->respond([
            'status' => true,
            'message' => 'Publisher Payout Setting has been Created Successfully!',
            'data' => [
                'publisher_payout_setting' => new CampaignPublisherPayoutSettingResource($publisher_payout_setting)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-single-publisher-payout-setting",
     * summary="Get Publisher Payout Setting by uuid",
     * description="Get Publisher Payout Setting by uuid",
     * operationId="getPublisherPayoutById",
     * tags={"Publisher Payout Setting"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort Publisher Payout Setting by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Payout Setting has been Fetched Successfully!',
     *       'data': {
     *          'publisher_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'payout_type': 'fixed amount',
     *             'payout_on': 'call type',
     *             'length': '5',
     *             'payout_amount': '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days': '10',
     *             'hours': '5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'start_break_time': '12:30:02,
     *             'break_duration': 30 min',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap': 'true',
     *             'concurrency_cap': 'true',
     *             'user_id': '1',
     *             'campaign_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Publisher Payout Setting Not Found")
     *        )
     *     ),
     * )
     */
    public function getSingleCampaignPublisherPayoutSetting(Request $request)
    {
        $publisher_payout_setting = CampaignPublisherPayoutSetting::where('uuid', $request->uuid)->first();
        if (empty($publisher_payout_setting)) {
            // return $this->respond([
            //     'status' => false,
            //     'message' => 'Publisher Payout Setting not found',
            //     'data' => []
            // ]);
            return $this->respondNotFound('Publisher Payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Payout Setting has been fetched successfully',
            'data' => [
                'publisher_payout_setting' => new CampaignPublisherPayoutSettingResource($publisher_payout_setting)
            ]
        ]);
    }


    /**
     * @OA\Get(
     * path="/api/get-publisher-payout-settings",
     * summary="Get Publisher Payout Settings",
     * description="Get Publisher Payout Settings",
     * operationId="getCampaignPublisherPayoutSettings",
     * tags={"Publisher Payout Setting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Payout Settings has been Fetched Successfully!',
     *       'data': {
     *          'publisher_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'payout_type': 'fixed amount',
     *             'payout_on': 'call type',
     *             'length': '5',
     *             'payout_amount': '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days': '10',
     *             'hours': '5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'start_break_time': '12:30:02',
     *             'break_duration': 30 min',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap': 'true',
     *             'concurrency_cap': 'true',
     *             'user_id': '1',
     *             'campaign_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Publisher Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function getCampaignPublisherPayoutSetting(Request $request)
    {
        $publisher_payout_settings = CampaignPublisherPayoutSetting::all();
        if (empty($publisher_payout_settings)) {
            return $this->respondNotFound('Publisher Payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Payout Settings has been fetched successfully',
            'data' => [
                'publisher_payout_settings' => CampaignPublisherPayoutSettingResource::collection($publisher_payout_settings)
            ]
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/my-publisher-payout-setting",
     * summary="Get Publisher Payout Setting by current user",
     * description="Get Publisher Payout Setting by current user",
     * operationId="getPublisherPayoutByuserId",
     * tags={"Publisher Payout Setting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Payout Setting has been Fetched Successfully!',
     *       'data': {
     *          'publisher_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'payout_type': 'fixed amount',
     *             'payout_on': 'call type',
     *             'length': '5',
     *             'payout_amount': '10',
     *             'revshare_payout_limit': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days': '10',
     *             'hours': '5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'start_break_time': '12:30:02',
     *             'break_duration': '30 min',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap': 'true',
     *             'concurrency_cap': 'true',
     *             'user_id': '1',
     *             'campaign_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Publisher Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function getPublisherPayoutByUser(Request $request)
    {
        $publisher_payout_setting = CampaignPublisherPayoutSetting::where('user_id', $request->user()->id)->first();
        if (empty($publisher_payout_setting)) {
            return $this->respondNotFound('Publisher Payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Payout Settings has been fetched successfully',
            'data' => [
                'publisher_payout_settings' => new CampaignPublisherPayoutSettingResource($publisher_payout_setting)
            ]
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/update-publisher-payout-setting",
     * summary="Update Publisher Payout Setting by uuid",
     * description="Update Publisher Payout Setting",
     * operationId="updateCampaignPublisherPayoutSetting",
     * tags={"Publisher Payout Setting"},
     * security={ {"sanctum": {} }},
     * * @OA\Parameter(
     *    description="Update Publisher Payout Setting by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Payout Setting data",
     *     @OA\JsonContent(
     *       @OA\Property(property="payout_type", type="enum", format="payout_type", example="fixed amount/revshare percentage"),
     *       @OA\Property(property="payout_on", type="string", format="payout_on", example="call length"),
     *       @OA\Property(property="length", type="integer", format="length", example="5.5"),
     *       @OA\Property(property="payout_amount", type="integer", format="payout_amount", example="100"),
     *       @OA\Property(property="revshare_payout_limits", type="boolean", format="revshare_payout_limits", example="true"),
     *       @OA\Property(property="min", type="integer", format="min", example="0"),
     *       @OA\Property(property="max", type="integer", format="max", example="1"),
     *       @OA\Property(property="duplicate_payouts", type="enum", format="duplicate_payouts", example="Disable/Enable/Time Limits"),
     *       @OA\Property(property="days", type="integer", format="days", example="5"),
     *       @OA\Property(property="hours", type="integer", format="hours", example="5.5"),
     *       @OA\Property(property="payout_hours", type="boolean", format="payout_hours", example="true/false"),
     *       @OA\Property(property="open_time", type="time", format="open_time", example="10:45:00"),
     *       @OA\Property(property="close_time", type="time", format="close_time", example="10:50:00"),
     *       @OA\Property(property="start_break_time", type="time", format="start_break_time", example="12:30:02"),
     *       @OA\Property(property="break_duration", type="time", format="break_duration", example="30 min"),
     *       @OA\Property(property="time_zone", type="string", format="time_zone", example="+GMT Canada"),
     *       @OA\Property(property="limit_payout", type="boolean", format="limit_payout", example="true/false"),
     *       @OA\Property(property="global_cap", type="boolean", format="global_cap", example="true/false"),
     *       @OA\Property(property="global_payout_cap", type="boolean", format="global_payout_cap", example="true/false"),
     *       @OA\Property(property="monthly_cap", type="boolean", format="monthly_cap", example="true/false"),
     *       @OA\Property(property="monthly_payout_cap", type="boolean", format="monthly_payout_cap", example="true/false"),
     *       @OA\Property(property="daily_cap", type="boolean", format="daily_cap", example="true/false"),
     *       @OA\Property(property="daily_payout_cap", type="boolean", format="daily_payout_cap", example="true/false"),
     *       @OA\Property(property="hourly_cap", type="boolean", format="hourly_cap", example="true/false"),
     *       @OA\Property(property="hourly_payout_cap", type="boolean", format="hourly_payout_cap", example="true/false"),
     *       @OA\Property(property="concurrency_cap", type="boolean", format="concurrency_cap", example="true/false"),
     *       @OA\Property(property="user_uuid", type="string", format="user_uuid", example="5966ac42-2b31-4aeb-ad95-fec77cfc6154"),
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuid", example="5966ac42-2b31-4aeb-ad95-fec77cfc6154")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Payout Setting has been Updated Successfully!',
     *         'data': {
     *          'publisher_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'payout_type': 'fixed amount',
     *             'payout_on': 'call type',
     *             'length': '5',
     *             'payout_amount': '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days': '10',
     *             'hours': '5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'start_break_time': '12:30:02',
     *             'break_duration': '30 min',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap': 'true',
     *             'concurrency_cap': 'true',
     *             'user_id': '1',
     *             'campaign_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Publisher Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function updateCampaignPublisherPayoutSetting(Request $request)
    {
        $publisher_payout_setting = CampaignPublisherPayoutSetting::where('uuid', $request->uuid)->first();
        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        if ($request->has('payout_type'))
            $data['payout_type'] = $request->payout_type;
        if ($request->has('revshare_payout_limits'))
            $data['revshare_payout_limits'] = $request->boolean('revshare_payout_limits');
        if ($request->has('payout_hours'))
            $data['payout_hours'] = $request->boolean('payout_hours');
        if ($request->has('limit_payout'))
            $data['limit_payout'] = $request->boolean('limit_payout');
        if ($request->has('global_cap'))
            $data['global_cap'] = $request->boolean('global_cap');
        if ($request->has('global_payout_cap'))
            $data['global_payout_cap'] = $request->boolean('global_payout_cap');
        if ($request->has('monthly_cap'))
            $data['monthly_cap'] = $request->boolean('monthly_cap');
        if ($request->has('monthly_payout_cap'))
            $data['monthly_payout_cap'] = $request->boolean('monthly_payout_cap');
        if ($request->has('daily_cap'))
            $data['daily_cap'] = $request->boolean('daily_cap');
        if ($request->has('daily_payout_cap'))
            $data['daily_payout_cap'] = $request->boolean('daily_payout_cap');
        if ($request->has('hourly_cap'))
            $data['hourly_cap'] = $request->boolean('hourly_cap');
        if ($request->has('hourly_payout_cap'))
            $data['hourly_payout_cap'] = $request->boolean('hourly_payout_cap');
        if ($request->has('concurrency_cap'))
            $data['concurrency_cap'] = $request->boolean('concurrency_cap');
        $publisher_payout_setting->update($data);
        if (empty($publisher_payout_setting)) {
            return $this->respondNotFound('Publisher Payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Payout Setting has been updated successfully',
            'data' => [
                'publisher_payout_setting' => new CampaignPublisherPayoutSettingResource($publisher_payout_setting)
            ]
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/delete-publisher-payout-settings",
     * summary="Delete Publisher Payout Setting",
     * description="Delete existing Publisher Payout Setting",
     * operationId="deleteCampaignPublisherPayoutSetting",
     * tags={"Publisher Payout Setting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Payout Setting has been Deleted Successfully!',
     *         'data': {
     *          'publisher_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'payout_type': 'fixed amount',
     *             'payout_on': 'call type',
     *             'length': '5',
     *             'payout_amount': '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days': '10',
     *             'hours': '5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'start_break_time': '12:30:02',
     *             'break_duration': '30 min',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap': 'true',
     *             'concurrency_cap': 'true',
     *             'user_id': '1',
     *             'campaign_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Publisher Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function deleteCampaignPublisherPayoutSetting(Request $request)
    {
        $publisher_payout_setting = CampaignPublisherPayoutSetting::where('uuid', $request->uuid)->first();
        if (empty($publisher_payout_setting)) {
            $this->respondNotFound('Publisher Payout Setting Not Found');
        }
        $publisher_payout_setting->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Payout Setting has been deleted successfully',
            'data' => [
                'publisher_payout_setting' => new CampaignPublisherPayoutSettingResource($publisher_payout_setting)
            ]
        ]);
    }
}
