<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CampaignAgentPayoutSettingResource;
use App\Http\Resources\CampaignCampaignAgentPayoutSettingResource;
use App\Models\Campaign;
use App\Models\CampaignAgentPayoutSetting;
use App\Models\DropDown;
use App\Models\User;
use Illuminate\Http\Request;
use Twilio\Rest\Preview\TrustedComms;

class CampaignAgentPayoutController extends APiController
{
    /**
     * @OA\Post(
     * path="/api/store-agent-payout-settings",
     * summary="Create Agent Payout Setting",
     * description="Create Agent Payout Setting",
     * operationId="createCampaignAgentPayoutSetting",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Payout Setting data",
     *    @OA\JsonContent(
     *       @OA\Property(property="per_call_earning", type="integer", format="per_call_earning", example="$1.5"),
     *       @OA\Property(property="commission", type="boolean", format="commission", example="true/false"),
     *       @OA\Property(property="commission_type", type="enum", format="commission_type", example="fixed_amount/revshare_percentage"),
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
     *       @OA\Property(property="break_duration", type="string", format="break_duration", example="30 min"),
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
     *       @OA\Property(property="tips", type="boolean", format="tips", example="true/false"),
     *       @OA\Property(property="bounties_condition", type="string", format="bounties_condition", example="10 sales"),
     *       @OA\Property(property="bounties_operator", type="string", format="bounties_operator", example="Equal to"),
     *       @OA\Property(property="bounties_value", type="integer", format="bounties_value", example="$100"),
     *       @OA\Property(property="bonus_type", type="string", format="bonus_type", example="annual/sales/performance/dependability"),
     *       @OA\Property(property="bonus_value", type="integer", format="bonus_value", example="$200"),
     *       @OA\Property(property="user_uuid", type="string", format="user_uuid", example="135f33b5-841e-47de-9686-dfd0c3eda7fc"),
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuid", example="135f33b5-841e-47de-9686-dfd0c3eda7fc")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Agent Payout Setting has been Created Successfully!',
     *       'data': {
     *          'agent_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'per_call_earning': '1.5',
     *             'commission': 'true',
     *             'commission_type': 'fixed_amount',
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
     *             'tips': true,
     *             'bounties_condition': 10 sales,
     *             'bounties_operator': Equals to,
     *             'bounties_value': $100,
     *             'bonus_type': annual,
     *             'bonus_value': $200,
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
     *       @OA\Property(property="message", type="string", example="Agent Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignAgentPayoutSetting(Request $request)
    {
        $agent_payout_setting = new CampaignAgentPayoutSetting($request->all());
        $user_id = User::getIdByUuid($request->user_uuid);
        $agent_payout_setting->user_id =  $user_id;

        $agent_payout_setting->campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $agent_payout_setting->commission = $request->boolean('commission');
        $agent_payout_setting->commission_type = $request->has('commission_type') ? $request->commission_type : 'fixed_amount';
        $agent_payout_setting->revshare_payout_limits = $request->boolean('revshare_payout_limits');
        $agent_payout_setting->duplicate_payouts = $request->has('duplicate_payouts') ? $request->duplicate_payouts : 'disable';
        $agent_payout_setting->payout_hours = $request->boolean('payout_hours');
        $agent_payout_setting->limit_payout = $request->boolean('limit_payout');
        $agent_payout_setting->global_cap = $request->boolean('global_cap');
        $agent_payout_setting->global_payout_cap = $request->boolean('global_payout_cap');
        $agent_payout_setting->monthly_cap = $request->boolean('monthly_cap');
        $agent_payout_setting->monthly_payout_cap = $request->boolean('monthly_payout_cap');
        $agent_payout_setting->daily_cap = $request->boolean('daily_cap');
        $agent_payout_setting->daily_payout_cap = $request->boolean('daily_payout_cap');
        $agent_payout_setting->hourly_cap = $request->boolean('hourly_cap');
        $agent_payout_setting->hourly_payout_cap = $request->boolean('hourly_payout_cap');
        $agent_payout_setting->concurrency_cap = $request->boolean('concurrency_cap');
        $agent_payout_setting->tips = $request->boolean('tips');
        $agent_payout_setting->save();

        // $data['step'] = 11;
        // $d =   Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $user_id])->update($data);
        $campaign =   Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $user_id])->first();
        if($request->step > $campaign->step){
            $campaign->step = $request->step;
        }
        $campaign->update();
        return $this->respond([
            'status' => true,
            'message' => 'Agent Payout Setting has been created successfully',
            'data' => [
                'agent_payout_setting' => new CampaignAgentPayoutSettingResource($agent_payout_setting)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-single-agent-payout-setting",
     * summary="Get Agent Payout Setting by uuid",
     * description="Get Agent Payout Setting by uuid",
     * operationId="getAgentPayoutById",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort Agent Payout Setting by uuid param",
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
     *       'message': 'Agent Payout Setting has been Fetched Successfully!',
     *       'data': {
     *          'agent_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'per_call_earning': '1.5',
     *             'commission': 'true',
     *             'commission_type': 'fixed_amount',
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
     *             'tips': true,
     *             'bounties_condition': 10 Sales,
     *             'bounties_operator': Equals to,
     *             'bounties_value': $200,
     *             'bonus_type': annual,
     *             'bonus_value': $200,
     *             'user_id', '1',
     *             'campaign_id': '1'
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
     *       @OA\Property(property="message", type="string", example="Agent Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function getSingleCampaignAgentPayoutSetting(Request $request)
    {
        $agent_payout_setting = CampaignAgentPayoutSetting::where('uuid', $request->uuid)->first();
        if (empty($agent_payout_setting)) {
            return $this->respondNotFound('Agent Payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Payout Setting has been Fetched Successfully',
            'data' => [
                'agent_payout_setting' => new CampaignAgentPayoutSettingResource($agent_payout_setting)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-agent-payout-settings",
     * summary="Get Agent Payout Settings",
     * description="Get Agent Payout Settings",
     * operationId="getAgentPayoutSettings",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Agent Payout Settings has been Fetched Successfully!',
     *       'data': {
     *          'agent_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'per_call_earning': '1.5',
     *             'commission': 'true',
     *             'commission_type': 'fixed_amount',
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
     *             'tips': true,
     *             'bounties_condition': 10 Sales,
     *             'bounties_operator': Equals to,
     *             'bounties_value': $200,
     *             'bonus_type': annual,
     *             'bonus_value': $200
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
     *       @OA\Property(property="message", type="string", example="Agent Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    // public function getCampaignAgentPayoutSetting()
    // {
    //     $agent_payout_setting = CampaignAgentPayoutSetting::all();
    //     if (empty($agent_payout_setting)) {
    //         return $this->respondNotFound('Agent Payout Settings not found');
    //     }
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Agent Payout Settings has been Fetched successfully',
    //         'data' => [
    //             'agent_payout_setting' => CampaignAgentPayoutSettingResource::collection($agent_payout_setting)
    //         ],
    //     ]);
    // }

    /**
     * @OA\Get(
     * path="/api/my-agent-payout-setting",
     * summary="Get Agent Payout Setting by current user",
     * description="Get Agent Payout Setting by current user",
     * operationId="getAgentPayoutByuserId",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Agent Payout Setting has been Fetched Successfully!',
     *       'data': {
     *          'agent_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'per_call_earning': '1.5',
     *             'commission': 'true',
     *             'commission_type': 'revshare_percentage',
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
     *             'tips': $200,
     *             'bounties_condition': 10 Sales,
     *             'bounties_operator': Equals to,
     *             'bounties_values': $200,
     *             'bonus_type': annual,
     *             'bonus_values': $200,
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
     *       @OA\Property(property="message", type="string", example="Agent Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function campaignAgentPayoutSetting(Request $request)
    {
        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $agent_payout_setting = CampaignAgentPayoutSetting::where('campaign_id', $campaign_id)->first();
        if (empty($agent_payout_setting)) {
            return $this->respondNotFound('Agent Payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Payout Setting has been Fetched Successfully',
            'data' => [
                'agent_payout_setting' => new CampaignAgentPayoutSettingResource($agent_payout_setting)
            ],
        ]);
    }

    /**
     * @OA\Put(
     * path="/api/update-agent-payout-setting",
     * summary="Update Agent Payout Setting by uuid",
     * description="Update Agent Payout Setting",
     * operationId="updateAgentPayoutSetting",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     * * @OA\Parameter(
     *    description="Update Agent Payout Setting by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Agent Payout Setting data",
     *     @OA\JsonContent(
     *       @OA\Property(property="per_call_earning", type="integer", format="per_call_earning", example="$1.5"),
     *       @OA\Property(property="commission", type="boolean", format="commission", example="true/false"),
     *       @OA\Property(property="commission_type", type="enum", format="commission_type", example="fixed_amount/revshare_percentage"),
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
     *       @OA\Property(property="break_duration", type="string", format="break_duration", example="30 min"),
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
     *       @OA\Property(property="hourly_rates", type="integer", format="hourly_rates", example="$200"),
     *       @OA\Property(property="tips", type="boolean", format="tips", example="true/false"),
     *       @OA\Property(property="bounties_condition", type="string", format="bounties_condition", example="10 Sales"),
     *       @OA\Property(property="bounties_operator", type="string", format="bounties_operator", example="Equals to"),
     *       @OA\Property(property="bounties_value", type="integer", format="bounties_value", example="$200"),
     *       @OA\Property(property="bonus_type", type="string", format="bonus_type", example="annual"),
     *       @OA\Property(property="bonus_value", type="integer", format="bonus_value", example="$200"),
     *       @OA\Property(property="user_uuid", type="string", format="user_uuid", example="5966ac42-2b31-4aeb-ad95-fec77cfc6154"),
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuid", example="5966ac42-2b31-4aeb-ad95-fec77cfc6154")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Agent Payout Setting has been Updated Successfully!',
     *         'data': {
     *          'agentr_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'per_call_earning': '1.5',
     *             'commission': 'true',
     *             'commission_type': 'fixed_amount',
     *             'payout_amount': '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts', 'time limits',
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
     *             'hourly_rates': $200,
     *             'tips': boolean,
     *             'bounties_condition': 10 Sales,
     *             'bounties_operator': Equals to,
     *             'bounties_value': $200,
     *             'bonus_type': annual,
     *             'bonus_value': $200,
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
     *       @OA\Property(property="message", type="string", example="Agent Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function updateCampaignAgentPayoutSetting(Request $request)
    {
        $agent_payout_setting = CampaignAgentPayoutSetting::where('uuid', $request->uuid)->first();
        $data = $request->all();
        $user_id = User::getIdByUuid($request->user_uuid);
        $data['user_id'] = $user_id;
        if ($request->has('commission'))
            $data['commission'] = $request->boolean('commission');
        if ($request->has('commission_type'))
            $data['commission_type'] = $request->commission_type;
        if ($request->has('revshare_payout_limits'))
            $data['revshare_payout_limits'] = $request->boolean('revshare_payout_limits');
        if ($request->has('duplicate_payouts'))
            $data['duplicate_payouts'] = $request->duplicate_payouts;
        if ($request->has('payout_hours'))
            $data['payout_hours'] = $request->boolean('payout_hours');
        if ($request->has('payout_limit'))
            $data['payout_limt'] = $request->boolean('payout_limit');
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
            $data['concurrecny_cap'] = $request->boolean('concurrency_cap');
        if ($request->has('tips'))
            $data['tips'] = $request->boolean('tips');
        $agent_payout_setting->update($data);
        if (empty($agent_payout_setting)) {
            return $this->respondNotFound('Agent payout Setting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Payout Setting has been Updated Successfully',
            'data' => [
                'agent_payout_setting' => new CampaignAgentPayoutSettingResource($agent_payout_setting)
            ],
        ]);
    }

    /**
     * @OA\Delete(
     * path="/api/delete-agent-payout-settings",
     * summary="Delete Agent Payout Setting",
     * description="Delete existing Agent Payout Setting",
     * operationId="deleteAgentPayoutSetting",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Agent Payout Setting has been Deleted Successfully!',
     *         'data': {
     *          'agent_payout_setting': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'per_call_earning': '1.5',
     *             'commission': 'true',
     *             'commission_type': 'revshare_percentage',
     *             'payout_amount': '10',
     *             'revshare_payout_limits': true,
     *             'min': 0,
     *             'max': 1,
     *             'duplicate_payouts': 'time limits',
     *             'days':'10',
     *             'hours':'5',
     *             'payout_hours': 'true',
     *             'open_time': '10:45:00',
     *             'close_time': '10:50:00',
     *             'time_zone': '+GMT Canada',
     *             'limit_payout': 'true',
     *             'global_cap': 'false',
     *             'global_payout_cap': 'false',
     *             'monthly_cap': 'true',
     *             'monthly_payout_cap': 'false',
     *             'daily_cap': 'true',
     *             'daily_payout_cap': 'true',
     *             'hourly_cap': 'true',
     *             'hourly_payout_cap'::'true',
     *             'concurrency_cap':'true',
     *             'tips': true,
     *             'bounties_condition': 10 Sales,
     *             'bounties_operator': Equals to,
     *             'bounties_value': $200,
     *             'bonus_type': annual,
     *             'bonus_value': $200,
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
     *       @OA\Property(property="message", type="string", example="Agent Payout Setting Not Found")
     *        )
     *     ),
     * )
     */

    public function destroyCampaignAgentPayoutSetting(request $request)
    {
        $agent_payout_setting = CampaignAgentPayoutSetting::where('uuid', $request->uuid)->first();
        if (empty($agent_payout_setting)) {
            return $this->respondNotFound('Agent Payout Setting not found');
        }
        $agent_payout_setting->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Agent Payout Setting has been Deleted Successfully',
            'date' => [
                'agent_payout_setting' => new CampaignAgentPayoutSettingResource($agent_payout_setting)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-bonus-types",
     * summary="Get Bonus Types by dropdown type",
     * description="Get bonus types by dropdown typw",
     * operationId="getBonusTypeByType",
     * tags={"Agent Payout Setting"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="type of Dropdown",
     *    in="path",
     *    name="type",
     *    required=true,
     *    example="performance/sales/dependability/none",
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
     *       'message': 'Agent Bonus Type has been Fetched Successfully!',
     *       'data': {
     *          'invoice_terms': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'Performance',
     *             'value', 'performance',
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
     *       @OA\Property(property="message", type="string", example="Agent Bonus Type Not Found")
     *        )
     *     ),
     * )
     */

    public function getBonusType(Request $request)
    {
        $agent_bonus_types = DropDown::where('type', 'agent bonus type')->pluck('label', 'value');
        if (empty($agent_bonus_types)) {
            return $this->respondNotFound('Agent Bonus Type not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Bonus Types has been fetched successfully',
            'data' => [
                'agent_bonus_type' => $agent_bonus_types
            ],
        ]);
    }

    public function getAgentPayoutOn()
    {
        $agent_payout_on = DropDown::where('type', 'agent payout_on')->pluck('label', 'value');
        if (empty($agent_payout_on)) {
            return $this->respondNotFound('Agent Payout_on not found');
        }
        return $this->respond([
            'data' => [
                'agent_payout_on' => $agent_payout_on
            ],
        ]);
    }
}
