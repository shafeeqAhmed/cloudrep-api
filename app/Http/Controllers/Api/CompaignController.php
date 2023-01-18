<?php

namespace App\Http\Controllers\Api;

use App\Classes\Gamification;
use App\Http\Requests\CampaignRequest;
use App\Http\Resources\CampaignGeoLocationResource;
use App\Http\Resources\CampaignResource;
use App\Models\BussinesCategory;
use App\Models\Campaign;
use App\Models\Currency;
use App\Models\CampaignCategory;
use App\Models\CampaignGeoLocation;
use App\Models\CampaignReporting;
use App\Models\CampaignService;
use App\Models\CampaignVertical;
use App\Models\CompanyVertical;
use App\Models\Service;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CompaignController extends APiController
{
    /**
     * @OA\Post(
     * path="/api/store-campaigns",
     * summary="Create Campaign",
     * description="Create Campaign",
     * operationId="createCampaign",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign data",
     *    @OA\JsonContent(
     *       required={"service_uuid"},
     *       @OA\Property(property="service_uuid", type="string", format="service_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign has been Created Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': null,
     *              'phone_no': null,
     *              'title': null,
     *              'email': null,
     *              'address': null,
     *              'country': null,
     *              'state': null,
     *              'city': null,
     *              'zipcode': null,
     *              'service_id': '2',
     *              'category_id': null,
     *              'vertical_id': null,
     *              'language': null,
     *              'currency': null,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration' : 5,
     *              'publisher_per_call': 5.1,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agnet_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign Not Found")
     *        )
     *     ),
     * )
     */
    public function storeServiceAgainstCampaign(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required',
            'service_uuid' => 'required',
            'step' => 'required',
        ]);
        $campaign = Campaign::where('uuid', $request->campaign_uuid)->first();
        $campaign->service_id = Service::getIdByUuid($request->service_uuid);
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();

        // $campaign = new Campaign($request->validated());

        // if($request->service_uuid)
        // $campaign->step = 2;
        // $campaign->service_id = Service::getIdByUuid($request->service_uuid);
        // $campaign->save();

        // if ($request->has('services')) {
        //     $services = explode(',', $request->services);
        //     $record = [];
        //     foreach ($services as $service) {
        //         if ($service) {
        //             $service_id = Service::getIdByUuid($service);
        //             $temp = [];
        //             $temp['uuid'] = generateUuid();
        //             $temp['user_id'] = $user_id;
        //             $temp['campaign_id'] = $campaign->id;
        //             $temp['service_id'] = $service_id;
        //             $temp['created_at'] = now();
        //             $record[] = $temp;
        //         }
        //     }
        //     CampaignService::insert($record);
        // }

        return $this->respond([
            'status' => true,
            'message' => 'Service has been Created Successfully!',
            'data' => [
                'campaign' => new CampaignResource($campaign)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-campaigns",
     * summary="Update Campaign",
     * description="Update Campaign",
     * operationId="updateCampaign",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid,service_uuid"},
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="service_uuid", type="string", format="service_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': null,
     *              'phone_no': null,
     *              'title': null,
     *              'email': null,
     *              'address': null,
     *              'country': null,
     *              'state': null,
     *              'city': null,
     *              'zipcode': null,
     *              'service_id': '2',
     *              'category_id': null,
     *              'vertical_id': null,
     *              'language': null,
     *              'currency': null,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agent_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign Not Found")
     *        )
     *     ),
     * )
     */

    public function updateServiceAgainstCampaign(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required',
            'service_uuid' => 'required',
            'step' => 'required',
        ]);
        $campaign = Campaign::where('uuid', $request->campaign_uuid)->first();
        $campaign->service_id = Service::getIdByUuid($request->service_uuid);
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        // $request->validate([
        //     'campaign_uuid' => 'required|uuid',
        //     'service_uuid' => 'required|string',
        // ]);
        // $serviceId = Service::getIdByUuid($request->service_uuid);
        // $campaign  = Campaign::updateRecord('uuid', $request->campaign_uuid, ['service_id' => $serviceId, 'step' => 2]);
        // Campaign::updateRecord('uuid', $request->campaign_uuid, ['step' => 2]);
        // $userId = User::getIdByUuid($request->user_uuid);
        // $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        // CampaignService::where(['user_id' => $userId, 'campaign_id' => $campaignId])->delete();
        // CampaignService::updateRecord('user_id', $userId, ['deleted_at' => now()]);

        // if ($request->has('services')) {
        //     $services = explode(',', $request->services);
        //     foreach ($services as $uuid) {
        //         $campaign_service = CampaignService::withTrashed()
        //             ->join('services', 'services.id', '=', 'campaign_services.service_id')
        //             ->join('campaigns as c', 'c.id', '=', 'campaign_services.campaign_id')
        //             ->where('campaign_services.user_id', $userId)
        //             ->where('services.service_uuid', $uuid)
        //             ->where('c.id', $campaignId)
        //             ->select('campaign_services.*')->first();
        //         if ($campaign_service) {
        //             $campaign_service->restore();
        //         } else {
        //             $service_id = Service::getIdByUuid($uuid);
        //             $newService = new CampaignService();
        //             $newService->uuid = generateUuid();
        //             $newService->user_id = $userId;
        //             $newService->service_id = $service_id;
        //             $newService->save();
        //         }
        //     }

        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Service has been Updated Successfully!',
                'data' => [
                    'campaign' => $campaign
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Service is not Updated!',
                'data' => []
            ]);
        }

        // }
    }
    public function getCampaignServices(Request $request)
    {
        $campaignServices = CampaignService::getUserService(User::getIdByUuid($request->user_uuid));
        return $this->respond([
            'status' => false,
            'message' => 'Campaign Services uuids Not Found',
            'data' =>  [
                'services' => $campaignServices
            ]
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/search-user/{role}",
     * summary="Search User By Role",
     * description="Get User By role",
     * operationId="searchUserByRole",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="role of User",
     *    in="path",
     *    name="role",
     *    required=true,
     *    example="agent/publisher/client",
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
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          'users': [
     *              {
     *                  'user_uuid': '6e3cd84f-b3e5-4387-87da-0b7654f248d2',
     *                  'name': 'Calista Blevins'
     *              },
     *              {
     *                  'user_uuid': '2b57d931-d73e-41e7-b278-c2272c2513c4',
     *                  'name': 'Elaine Baxter'
     *              },
     *              {
     *                  'user_uuid': 'e611d3f1-3dbc-4130-96fb-210a7ec20b04',
     *                  'name': 'Tara Michael'
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
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */

    public function searchUser($role, Request $request)
    {
        $users = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })
            ->when($role == 'client', function ($query) {
                return $query->whereHas('clientProfileItem');
            })
            // ->when($request->q, function ($query, $q) {
            //     return $query->where('name', 'LIKE', "%{$q}%");
            // })
            ->when($request->sortBy, function ($query, $sortBy) use ($request) {
                return $query->orderBy($sortBy, $request->sortDesc ? 'asc' : 'desc');
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->where('step', 6)
            ->get(['user_uuid', 'name']);

        if (empty($users)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'User has been Fetched Successfully!',
            'data' => [
                'users' => $users
            ]
        ]);
    }


    /**
     * @OA\Get(
     * path="/api/get-campaign/{uuid}",
     * summary="Get Campaign By Uuid",
     * description="Get Campaign By uuid",
     * operationId="getCampaignByUuid",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Campaign",
     *    in="path",
     *    name="uuid",
     *    required=true,
     *    example="6e3cd84f-b3e5-4387-87da-0b7654f248d2",
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
     *       'message': 'Campaign has been Fetched Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test campaign,
     *              'phone_no': null,
     *              'title': null,
     *              'email': null,
     *              'address': null,
     *              'country': null,
     *              'state': null,
     *              'city': null,
     *              'zipcode': null,
     *              'service_id': '2',
     *              'category_id': null,
     *              'vertical_id': null,
     *              'language': null,
     *              'currency': null,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agent_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign Not Found")
     *        )
     *     ),
     * )
     */

    public function getCampaign($uuid)
    {
        $campaign = Campaign::with(['service', 'user', 'agentPayoutSetting', 'publisherPayoutSetting', 'campaignLms.course'])->where('uuid', $uuid)->first();
        if (empty($campaign)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign has been Fetched Successfully!',
            'data' => [
                'campaign' => new CampaignResource($campaign)
            ],
        ]);
    }

    public function getCampaignsByUser(Request $request)
    {
        $campaign = Campaign::where('user_id', $request->user()->id)->select('uuid', 'campaign_name')->get();
        if (empty($campaign)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign has been Fetched Successfully!',
            'data' => [
                'campaigns' => $campaign
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-step-one",
     * summary="Create Step One for Compaign",
     * description="Create Step One",
     * operationId="createStepOne",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass stepone date for Campaign",
     *    @OA\JsonContent(
     *       required={"uuid,name,email,phone,title"},
     *       @OA\Property(property="uuid", type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="name", type="string", format="name", example="test"),
     *       @OA\Property(property="email", type="string", format="email", example="test@gmail.com"),
     *       @OA\Property(property="phone", type="integer", format="phone", example="+122334355"),
     *       @OA\Property(property="title", type="string", format="title", example="test title"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign StepOne has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test,
     *              'phone_no': +122334355,
     *              'title': test title,
     *              'email': test@gmail.com,
     *              'address': null,
     *              'country': null,
     *              'state': null,
     *              'city': null,
     *              'zipcode': null,
     *              'service_id': '2',
     *              'category_id': null,
     *              'vertical_id': null,
     *              'language': null,
     *              'currency': null,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agent_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign StepOne Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignName(Request $request)
    {
        $request->validate([
            'campaign_name' => [
                'required', 'max:255',
                function ($attribute, $value, $fail) {
                    $campaignNameExists = Campaign::where('campaign_name', $value)->count() > 0;
                    if ($campaignNameExists) {
                        $fail('The ' . $attribute . ' must be unique.');
                    }
                }
            ],
            'step' => 'required'
        ]);

        $campaign = new Campaign();
        $campaign->campaign_name = $request->campaign_name;
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->save();

        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign has been Created Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }

    public function updateCampaignName(Request $request)
    {
        $request->validate([
            'campaign_name' => [
                'required', 'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    $campaignNameExists = Campaign::where('campaign_name', $value)->where('uuid', '!=', $request->uuid)->count() > 0;
                    if ($campaignNameExists) {
                        $fail('The ' . $attribute . ' must be unique.');
                    }
                }
            ],

        ]);

        $campaign = Campaign::where('uuid', $request->uuid)->first();

        if ($campaign) {
            $campaign->campaign_name = $request->campaign_name;
            $campaign->update();
            return $this->respond([
                'status' => true,
                'message' => 'Campaign name has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }


    public function storeCampaignClient(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'name' => 'required|string',
            'email' => 'required|string',
            'phone_no' => 'required|string',
            'title' => 'required|string',
            'step' => 'required',
        ]);
        // $data = $request->except(['user_uuid', 'campaign_uuid']);
        // $data['user_id'] = User::getIdByUuid($request->user_uuid);
        // $data['step'] = 3;
        // $campaign = Campaign::where('uuid', $request->campaign_uuid)->update($data);
        // return $this->respond(updateRecordResponseArray($campaign));

        $user_id = User::getIdByUuid($request->user_uuid);

        $campaign = Campaign::where('uuid', $request->campaign_uuid)->first();
        $campaign->user_id = $user_id;
        $campaign->name = $request->name;
        $campaign->email = $request->email;
        $campaign->phone_no = $request->phone_no;
        $campaign->title = $request->title;
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Client Information has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/store-step-two",
     * summary="Create Step Two for Compaign",
     * description="Create Step Two",
     * operationId="createStepTwo",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass steptwo data for Campaign",
     *    @OA\JsonContent(
     *       required={"uuid"},
     *       @OA\Property(property="uuid", type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="country", type="string", format="country", example="test country"),
     *       @OA\Property(property="state", type="string", format="state", example="test state"),
     *       @OA\Property(property="city", type="integer", format="city", example="test city"),
     *       @OA\Property(property="zipcode", type="string", format="zipcode", example="test zipcode"),
     *       @OA\Property(property="address", type="string", format="address", example="test address"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign StepOne has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test,
     *              'phone_no': +122334355,
     *              'title': test title,
     *              'email': test@gmail.com,
     *              'address': test address,
     *              'country': test country,
     *              'state': test state,
     *              'city': test city,
     *              'zipcode': test zipcode,
     *              'service_id': '2',
     *              'category_id': null,
     *              'vertical_id': null,
     *              'language': null,
     *              'currency': null,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agent_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign StepTwo Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignClientAddress(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'country' => 'required',
            'state' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'step' => 'required',
            // 'user_uuid' => 'required|uuid',
            // 'zipcode' => 'required',
        ]);
        // $userId = User::getIdByUuid($request->user_uuid);
        // $data = $request->except(['user_uuid', 'campaign_uuid']);
        // $data['step'] = 4;
        // $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->update($data);

        // return $this->respond(updateRecordResponseArray($campaign));
        // $user_id = User::getIdByUuid($request->user_uuid);

        $campaign = Campaign::where('uuid', $request->campaign_uuid)->first();
        $campaign->country = $request->country;
        $campaign->state = $request->state;
        $campaign->city = $request->city;
        $campaign->address = $request->address;
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Client Address has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }

    public function getCampaignsCompleted(Request $request)
    {
        $campaigns = Campaign::with(['category', 'vertical', 'service'])->when($request->q, function ($query, $q) {
            return $query->where('is_published', 1)->where(function ($query) use ($q) {
                $query->where('campaign_name', 'LIKE', "%{$q}%")
                    ->orWhere('country', 'LIKE', "%{$q}%");
            });
        })->when($request->sortBy, function ($query, $sortBy) use ($request) {
            return $query->orderBy($sortBy, $request->sortDesc ? 'asc' : 'desc');
        })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->where('is_published', 1)
            ->paginate($request->perPage);
        if (empty($campaigns)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaigns has been Fetched Successfully!',
            'data' => [
                'campaigns' => $campaigns,
                // 'category' => $campaigns,
            ],
        ]);
    }

    public function getCampaignsDarfted(Request $request)
    {
        $campaigns = Campaign::when($request->q, function ($query, $q) {
            return $query->where('is_published', 0)->where(function ($query) use ($q) {
                $query->where('campaign_name', 'LIKE', "%{$q}%")
                    ->orWhere('country', 'LIKE', "%{$q}%");
            });
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })->where('is_published', '=', 0)
            ->paginate($request->perPage);
        if (empty($campaigns)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaigns has been Fetched Successfully!',
            'data' => [
                'campaigns' => $campaigns
            ],
        ]);
    }


    /**
     * @OA\Post(
     * path="/api/store-step-three",
     * summary="Create Step Three for Compaign",
     * description="Create Step Three",
     * operationId="createStepThree",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass stepthree data for Campaign",
     *    @OA\JsonContent(
     *       required={"uuid"},
     *       @OA\Property(property="uuid", type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="category_id", type="string", format="category_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="vertical_id", type="string", format="vertical_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="language", type="integer", format="language", example="test language"),
     *       @OA\Property(property="currency", type="string", format="currency", example="test currency"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign StepThree has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test,
     *              'phone_no': +122334355,
     *              'title': test title,
     *              'email': test@gmail.com,
     *              'address': test address,
     *              'country': test country,
     *              'state': test state,
     *              'city': test city,
     *              'zipcode': test zipcode,
     *              'service_id': '2',
     *              'category_id': 1,
     *              'vertical_id': 2,
     *              'language': test language,
     *              'currency': test currency,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agnet_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign StepThree Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignBusinessCateVertical(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'category_uuid' => 'required
            |uuid',
            'vertical_uuid' => 'required|uuid',
            'language' => 'required|string',
            'currency' => 'required|string',
            'step' => 'required',
        ]);
        +$category_id = BussinesCategory::getIdByUuid($request->category_uuid);
        $vertical_id = CompanyVertical::getIdByUuid($request->vertical_uuid);
        $userId = User::getIdByUuid($request->user_uuid);
        $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->first();
        $campaign->category_id = $category_id;
        $campaign->vertical_id = $vertical_id;
        $campaign->language = $request->language;
        $campaign->currency = $request->currency;
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Business Category has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
        // $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->update([
        //     'category_id' => $category_id,
        //     'vertical_id' => $vertical_id,
        //     'language' => $request->language,
        //     'currency' => $request->currency,
        //     'step' => $request->step > $campaign->step ? $request->step : $campaign->step,
        // ]);
        // return $this->respond(updateRecordResponseArray($campaign));
    }

    /**
     * @OA\Post(
     * path="/api/store-step-four",
     * summary="Create Step Four for Compaign",
     * description="Create Step Four",
     * operationId="createStepFour",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass stepfour data for Campaign",
     *    @OA\JsonContent(
     *       required={"uuid"},
     *       @OA\Property(property="uuid", type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="start_date", type="string", format="start_date", example="2022-07-25"),
     *       @OA\Property(property="start_time", type="string", format="start_time", example="12:40:01"),
     *       @OA\Property(property="end_date", type="integer", format="end_date", example="2022-07-26"),
     *       @OA\Property(property="end_time", type="string", format="end_time", example="03:40:01"),
     *       @OA\Property(property="decription", type="string", format="currency", example="test description"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign StepFour has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test,
     *              'phone_no': +122334355,
     *              'title': test title,
     *              'email': test@gmail.com,
     *              'address': test address,
     *              'country': test country,
     *              'state': test state,
     *              'city': test city,
     *              'zipcode': test zipcode,
     *              'service_id': '2',
     *              'category_id': 1,
     *              'vertical_id': 2,
     *              'language': test language,
     *              'currency': test currency,
     *              'start_date': 2022-07-25,
     *              'start_time': 12:40:01,
     *              'end_date': 2022-07-26,
     *              'end_time': 03:40:01,
     *              'description': test description,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agnet_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign StepFour Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignStartEndDateTime(Request $request)
    {

        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            // 'time_zone' => 'required',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date',
            // 'end_date' => Rule::when($request->end_date != null, [
            //     'required|date',
            // ]),
            // 'start_time' => 'required',
            // // 'end_time' => 'required',
            // 'end_date' => Rule::when($request->end_time != null, [
            //     'required',
            // ]),
            'descripiton' => 'required',
            'step' => 'required',
        ]);


        $userId = User::getIdByUuid($request->user_uuid);

        // $data = $request->except('campaign_uuid', 'user_uuid');
        // $data['end_date'] = $request->end_date ? $request->end_date : null;
        // $data['end_time'] = $request->end_date ? $request->end_time : null;
        // $data['step'] = 6;
        // $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->update($data);
        // return $this->respond(updateRecordResponseArray($campaign));

        $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->first();
        $campaign->time_zone = $request->time_zone;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->start_time = $request->start_time;
        $campaign->end_time = $request->end_time;
        $campaign->descripiton = $request->descripiton;
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Date & Time has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/store-step-five",
     * summary="Create Step Five for Compaign",
     * description="Create Step Five",
     * operationId="createStepFive",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass stepfive data for Campaign",
     *    @OA\JsonContent(
     *       required={"uuid"},
     *       @OA\Property(property="website_url", type="string", format="website_url", example="website_url"),
     *       @OA\Property(property="deeplink", type="string", format="deeplink", example="deeplink"),
     *       @OA\Property(property="blog_url", type="string", format="blog_url", example="blog_url"),
     *       @OA\Property(property="facebook_url", type="integer", format="facebook_url", example="facebook_url"),
     *       @OA\Property(property="twitter_url", type="string", format="twitter_url", example="twitter_url"),
     *       @OA\Property(property="linkedin_url", type="string", format="linkedin_url", example="linkedin_url"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign StepFive has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test,
     *              'phone_no': +122334355,
     *              'title': test title,
     *              'email': test@gmail.com,
     *              'address': test address,
     *              'country': test country,
     *              'state': test state,
     *              'city': test city,
     *              'zipcode': test zipcode,
     *              'service_id': '2',
     *              'category_id': 1,
     *              'vertical_id': 2,
     *              'language': test language,
     *              'currency': test currency,
     *              'start_date': 2022-07-25,
     *              'start_time': 12:40:01,
     *              'end_date': 2022-07-26,
     *              'end_time': 03:40:01,
     *              'description': test description,
     *              'website_url': website_ul,
     *              'deeplink': deeplink,
     *              'blog_url': blog_url,
     *              'facebook_url': facebook_url,
     *              'twitter_url': twitter_url,
     *              'linkedin_url': linkedin_url,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agnet_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign StepFive Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignSocialWebsiteLinks(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            // 'website_url' => 'required|url',
            // 'deeplink' => 'nullable|url',
            // 'blog_url' => 'nullable|url',
            // 'facebook_url' => 'nullable|url',
            // 'twitter_url' => 'nullable|url',
            // 'linkedin_url' => 'nullable|url',
            'step' => 'required'

        ]);

        $userId = User::getIdByUuid($request->user_uuid);

        // $data = $request->except('campaign_uuid', 'user_uuid');
        // $data['step'] = 7;
        // $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->update($data);
        // $campaign = Campaign::where('uuid', $request->uuid)->update($data);

        // return $this->respond(updateRecordResponseArray($campaign));

        $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->first();
        $campaign->website_url = $request->website_url;
        $campaign->deeplink = $request->deeplink;
        $campaign->blog_url = $request->blog_url;
        $campaign->facebook_url = $request->facebook_url;
        $campaign->twitter_url = $request->twitter_url;
        $campaign->linkedin_url = $request->linkedin_url;
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign name has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/store-step-six",
     * summary="Create Step Six for Compaign",
     * description="Create Step Six",
     * operationId="createStepSix",
     * tags={"Campaign"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass stepsix data for Campaign",
     *    @OA\JsonContent(
     *       required={"uuid"},
     *       @OA\Property(property="publisher_per_call_duration", type="string", format="publisher_per_call_duration", example="5"),
     *       @OA\Property(property="payout_per_call", type="string", format="payout_per_call", example="2.5"),
     *       @OA\Property(property="client_per_call_duration", type="string", format="client_per_call_duration", example="5"),
     *       @OA\Property(property="ear_time", type="integer", format="ear_time", example="3.4"),
     *       @OA\Property(property="campaign_rate", type="float", format="cmapaign_rate", example="30"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign StepSix has been Updated Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test,
     *              'phone_no': +122334355,
     *              'title': test title,
     *              'email': test@gmail.com,
     *              'address': test address,
     *              'country': test country,
     *              'state': test state,
     *              'city': test city,
     *              'zipcode': test zipcode,
     *              'service_id': '2',
     *              'category_id': 1,
     *              'vertical_id': 2,
     *              'language': test language,
     *              'currency': test currency,
     *              'start_date': 2022-07-25,
     *              'start_time': 12:40:01,
     *              'end_date': 2022-07-26,
     *              'end_time': 03:40:01,
     *              'description': test description,
     *              'website_url': website_ul,
     *              'deeplink': deeplink,
     *              'blog_url': blog_url,
     *              'facebook_url': facebook_url,
     *              'twitter_url': twitter_url,
     *              'linkedin_url': linkedin_url,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
     *              'ear_time': 3.4,
     *              'campaign_rate': 30,
     *              'publisher_image': null,
     *              'agent_image': null,
     *              'client_image': null,
     *              'created_at': '2022-07-25T09:41:48.000000Z',
     *              'agent_payout_setting': {
     *                  'uuid': '8fc0dae2-1113-4423-82eb-6edeb1c4b958',
     *                  'per_call_earning': null,
     *                  'commission': true,
     *                  'commission_type': 'revshare_percentage',
     *                  'payout_on': null,
     *                  'payout_amount': 0,
     *                  'revshare_payout_limits': true,
     *                  'min': null,
     *                  'max': null,
     *                  'duplicate_payouts': 'enable',
     *                  'days': 2,
     *                  'hours': 5,
     *                  'payout_hours': false,
     *                  'open_time': null,
     *                  'close_time': null,
     *                  'start_break_time': '12:30:02',
     *                  'break_duration': '30',
     *                  'time_zone': null,
     *                  'limit_payout': false,
     *                  'global_cap': false,
     *                  'global_payout_cap': false,
     *                  'monthly_cap': false,
     *                  'monthly_payout_cap': false,
     *                  'daily_cap': false,
     *                  'daily_payout_cap': false,
     *                  'hourly_cap': false,
     *                  'hourly_payout_cap': false,
     *                  'concurrency_cap': false,
     *                  'tips': false,
     *                  'bounties_condition': null,
     *                  'bounties_operator': null,
     *                  'bounties_value': null,
     *                  'bonus_type': null,
     *                  'bonus_value': null,
     *                  'user_id': null,
     *                  'campaign_id': 1,
     *                  'created_at': '2022-08-16T10:00:47.000000Z'
     *              },
     *              'campaign_location': [
     *                  {
     *                      'uuid': '135f33b5-841e-47de-9686-dfd0c3eda7fc',
     *                      'country': 'test country',
     *                      'state': 'test state',
     *                      'city_town': 'test city',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:27:39.000000Z'
     *                  },
     *                  {
     *                      'uuid': '0176e268-ee7b-4232-b97f-8fabebdf23e7',
     *                      'country': 'pakistan',
     *                      'state': 'punjab',
     *                      'city_town': 'lahore',
     *                      'zipcode': 123,
     *                      'long': 1.2,
     *                      'lat': 2.5,
     *                      'campaign_id': 1,
     *                      'created_at': '2022-08-15T16:28:37.000000Z'
     *                  }
     *              ]
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
     *       @OA\Property(property="message", type="string", example="Campaign StepSix Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignRates(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'cost_per_call' => 'required|integer|between:1,100',
            'client_duration_type' => 'required',
            'client_per_call_duration' => 'required',
            'payout_per_call' => 'required|integer|between:1,100',
            'publisher_duration_type' => 'required',
            'publisher_per_call_duration' => 'required',
            'campaign_rate' => Rule::when($request->campaign_rate != null, [
                'required',
            ]),
            'step' => 'required',
        ]);

        $userId = User::getIdByUuid($request->user_uuid);
        // $data = $request->except('campaign_uuid', 'user_uuid');
        // $data['campaign_rate'] = $request->campaign_rate ? $request->campaign_rate : null;
        // $data['step'] = 8;
        // $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->update($data);

        // return $this->respond(updateRecordResponseArray($campaign));
        $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->first();

        $campaign->cost_per_call = $request->cost_per_call;
        $campaign->client_duration_type = $request->client_duration_type;
        $campaign->client_per_call_duration = $request->client_per_call_duration;

        $campaign->payout_per_call = $request->payout_per_call;
        $campaign->publisher_duration_type = $request->publisher_duration_type;
        $campaign->publisher_per_call_duration = $request->publisher_per_call_duration;

        $campaign->campaign_rate = $request->campaign_rate;

        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($campaign) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign name has been Updated Successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ],
            ]);
        }
    }


    public function storeCampaignImages(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            // 'type' => ['required', 'in:client_image,agent_image,publisher_image'],
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'step' => 'required'
        ]);

        $userId = User::getIdByUuid($request->user_uuid);
        $campaign = Campaign::where(['uuid' => $request->campaign_uuid, 'user_id' => $userId])->first();
        $isUpdateRecord = false;
        //check if request contain an image
        if ($request->hasFile('image')) {
            //check against key record exist in db
            $oldImagePath = $campaign[$request->type];
            if (!empty($oldImagePath)) {
                //if record exist delete this from storage
                removeImage('uploads/campaign', $oldImagePath);
            }
            //upload image
            $image = uploadImage('image', 'uploads/campaign', 300, 300);

            $isUpdateRecord = $campaign->update([
                $request->type => $image
            ]);
            // check all images uploaded?
            $status = $this->isStepSevenCompleted($request->campaign_uuid);
            // if all images upload update step
            if ($status) {
                // Campaign::updateRecord('uuid', $request->campaign_uuid, ['step' => 9]);
                if ($request->step > $campaign->step) {
                    $campaign->step = $request->step;
                }
                $campaign->update();
                if ($campaign) {
                    return $this->respond([
                        'status' => true,
                        'message' => 'Campaign Image has been Updated Successfully!',
                        'data' => [
                            'campaign' => new CampaignResource($campaign)
                        ],
                    ]);
                }
            }
        }
        return $this->respond(updateRecordResponseArray($isUpdateRecord));
    }


    private function isStepSevenCompleted($campaignUuid)
    {
        $record = Campaign::getRecord('uuid', $campaignUuid);
        return (!empty($record->client_image) && !empty($record->client_image) && !empty($record->client_image));
    }
    public function removeCampaignImage(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'user_uuid' => 'required|uuid',
            'type' => ['required', 'in:client_image,agent_image,publisher_image'],
        ]);
        $campaign = Campaign::getRecord('uuid', $request->campaign_uuid);
        $oldImagePath = $campaign[$request->type];
        if (!empty($oldImagePath)) {
            //if record exist delete this from storage
            removeImage('uploads/campaign', $oldImagePath);
        }
        $isUpdateRecord = $campaign->update([$request->type => '']);
        return $this->respond(updateRecordResponseArray($isUpdateRecord));
    }
    public function getSingleCampaign($uuid)
    {
        $campaign = Campaign::where('uuid', $uuid)->with(['category', 'vertical', 'agentPayoutSetting'])
            ->first();
        if (empty($campaign)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign has been Fetched Successfully!',
            'data' => [
                'campaign' => $campaign,
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-campaign-location",
     * summary="Create Campaign Location",
     * description="Create Campaign Location",
     * operationId="createCampaignLocation",
     * tags={"Campaign Location"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Location data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid"},
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="country",type="string", format="country", example="pakistan"),
     *       @OA\Property(property="state", type="string", format="state", example="punjab"),
     *       @OA\Property(property="city_town", type="string", format="city_town", example="lahore"),
     *       @OA\Property(property="zipcode", type="integer", format="zipcode", example="5400"),
     *       @OA\Property(property="long", type="float", format="long", example="1.2"),
     *       @OA\Property(property="lat", type="float", format="lat", example="2.5"),
     *       @OA\Property(property="address", type="string", format="address", example="test address"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Location has been created successfully!',
     *       'data': {
     *          'campaignLocation': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'country': 'pakistan',
     *              'state': 'punjab',
     *              'city_town': 'lahore',
     *              'zipcode': '5400',
     *              'long': '1.2',
     *              'lat': '2.5',
     *              'address': 'test address',
     *              'campaign_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Campaign Location Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignLocation(Request $request)
    {

        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'address_type' => 'required',
            'step' => 'required',
        ]);

        // return response()->json($request->address_type);
        $campaignLocation = new CampaignGeoLocation($request->all());
        $campaign_id = Campaign::getIdByUuid($request->campaign_uuid);
        $campaignLocation->campaign_id = $campaign_id;
        $campaignLocation->save();
        // Campaign::where('uuid', $request->campaign_uuid)->update([
        //     'step' => 12,
        // ]);
        $campaign = Campaign::where('id', $campaign_id)->first();
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        $gamification = new Gamification();
        $gamification->add($request, $request->user()->id, 20, 'Compaign Registration', true);
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Location has been created successfully!',
            'data' => [
                'campaignLocation' => new CampaignGeoLocationResource($campaignLocation)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-campaign-location",
     * summary="Get Campaign Location",
     * description="Get Campaign Location by campaign_uuid",
     * operationId="getCampaignLocationbyCampaignId",
     * tags={"Campaign Location"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="campaign_uuid of campaign",
     *    in="path",
     *    name="campaign_uuid",
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
     *       'message': 'Campaign Location has been fetched succesfully!',
     *       'data': {
     *          'campaign_location': {
     *              'uuid': '9a0a27ea-d113-492a-8c8d-55b19d4bcf2d',
     *              'country': 'pakistan',
     *              'state': 'punjab',
     *              'city_town': 'lahore',
     *              'zipcode': '5400',
     *              'long': '1.2',
     *              'lat': '2.5',
     *              'address': 'test address',
     *              'campaign_id': '1',
     *              'created_at': '2022-06-25T14:32:54.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Campaign Location not found")
     *        )
     *     ),
     * )
     */

    public function getCampaignLocationByCampaignId(Request $request)
    {

        $request->validate([
            'campaign_uuid' => 'required|uuid',
        ]);

        $campaign_uuid = Campaign::getIdByUuid($request->campaign_uuid);
        $campaignLocation = CampaignGeoLocation::where('campaign_id', $campaign_uuid)->get();
        if (empty($campaignLocation)) {
            return $this->respondNotFound('Campaign Location Not Found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Location has been fetched successfully!',
            'data' => [
                'campaignLocation' => CampaignGeoLocationResource::collection($campaignLocation)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-campaign-location",
     * summary="Update Campaign Location",
     * description="Update Campaign Location",
     * operationId="updateCampaignLocation",
     * tags={"Campaign Location"},
     * security={{"sanctum": {}}},
     *  @OA\Parameter(
     *    description="uuid of Campaign Location",
     *    in="path",
     *    name="uuid",
     *    required=true,
     *    example="53adb8de-3cab-4aec-9db2-5bc7fd40b764",
     *    @OA\Schema(
     *       type="string",
     *       format="int64"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Location data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid"},
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="country", type="string", format="country", example="pakistan"),
     *       @OA\Property(property="state", type="string", format="state", example="punjab"),
     *       @OA\Property(property="city_town", type="string", format="city_town", example="lahore"),
     *       @OA\Property(property="zipcode", type="integer", format="zipcode", example="5400"),
     *       @OA\Property(property="long", type="float", format="long", example="1.2"),
     *       @OA\Property(property="lat", type="float", format="lat", example="2.5"),
     *       @OA\Property(property="address", type="string", format="address", example="test address")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Locatin has been updated successfully!',
     *       'data': {
     *          'campaign_location': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'country': 'pakistan',
     *              'state': 'punjab',
     *              'city_town': 'lahore',
     *              'zipcode': '5400',
     *              'long': '1.2',
     *              'lat': '2.5',
     *              'address': 'test address',
     *              'campaign_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Campaign Location not found")
     *        )
     *     ),
     * )
     */

    public function updateCampaignLocation(Request $request)
    {
        $campaignLocation = CampaignGeoLocation::where('uuid', $request->uuid)->first();
        // dd($campaignLocation);
        $data = $request->all();
        if (empty($campaignLocation)) {
            return $this->respondNotFound('Campaign Location Not Found');
        }
        $campaignLocation->update($data);
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Location has been updated successfully!',
            'data' => [
                'campaignLocation' => new CampaignGeoLocationResource($campaignLocation)
            ]
        ]);
    }

    public function storeCampaignPublish($uuid)
    {
        $campaign = Campaign::where('uuid', $uuid)->first();
        if ($campaign) {
            $campaign->is_published = true;
            $campaign->step = 14;
            $status = 'complete';

            // calculate campaign is complete or incomplete
            $campaign_name = $campaign->campaign_name;
            $user_uuid = !empty($campaign->user) ? $campaign->user->user_uuid : null;
            $cost_per_call = $campaign->cost_per_call;
            $routing = $campaign->routing;

            if ($routing == 'ivr') {
                $targets = $campaign->ivr_id ?? null;
            } else {
                $targets = $campaign->targets ?? null;
            }
            $twillioNumbers = $campaign->twillioNumbers ?? null;

            if (empty($user_uuid) || empty($cost_per_call) || empty($targets[0]) ||  empty($twillioNumbers[0])) {
                $status = 'incomplete';
            }

            $campaign->status = $status;

            $campaign->update();
            return $this->respond([
                'status' => true,
                'message' => 'Campaign has been publish successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ]
            ]);
        }
    }

    public function deleteEndDateTime($uuid)
    {
        // return response()->json($uuid);
        $campaign = Campaign::where('uuid', $uuid)->first();
        if ($campaign) {
            $campaign->end_date = null;
            $campaign->end_time = null;
            $campaign->update();
            return $this->respond([
                'status' => true,
                'message' => 'Campaign has been updated successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ]
            ]);
        }
    }
    public function deleteCampaignDateTime($uuid)
    {
        $campaign = Campaign::where('uuid', $uuid)->first();
        if ($campaign) {
            $campaign->start_date = null;
            $campaign->end_date = null;
            $campaign->start_time = null;
            $campaign->end_time = null;
            $campaign->time_zone = null;
            $campaign->update();
            return $this->respond([
                'status' => true,
                'message' => 'Campaign has been updated successfully!',
                'data' => [
                    'campaign' => new CampaignResource($campaign)
                ]
            ]);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/campaign-location",
     * summary="Delete Campaign Location",
     * description="Delete exisiting Campaign Location",
     * operationId="deleteCampaignLocation",
     * tags={"Campaign Location"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Campaign Location",
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
     *       'message': 'Campaign Location has been deleted successfully!',
     *        'data': {
     *          'campaign_location': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'country': 'pakistan',
     *              'state': 'punjab',
     *              'city_town': 'lahore',
     *              'zipcode': '5400',
     *              'long': '1.2',
     *              'lat': '2.5',
     *              'address': 'test address',
     *              'campaign_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Campaign Location not found")
     *        )
     *     ),
     * )
     */

    public function deleteCampaignLocation(Request $request)
    {
        $campaignLocation = CampaignGeoLocation::where('uuid', $request->uuid)->first();
        if (empty($campaignLocation)) {
            return $this->respondNotFound('Campaign Location not found');
        }
        $campaignLocation->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Location has been deleted successfully!',
            'data' => [
                'campaignLocation' => new CampaignGeoLocationResource($campaignLocation)
            ],
        ]);
    }

    public function deleteCampaign($uuid)
    {
        $campaign = Campaign::where('uuid', $uuid)->first();
        if (empty($campaign)) {
            return $this->respondNotFound('Campaign Registration not found');
        }
        $campaign->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign has been deleted successfully!',
            'data' => [],
        ]);
    }


    public  function getCampaignsPublishers()
    {
        $numbers = Campaign::getPublisher();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign has been deleted successfully!',
            'data' => [
                'numbers' => $numbers
            ],
        ]);
    }
    public function assignIvrToCampaign()
    {
        $record =  Campaign::assignIvrToCampaign();
        return   updateRecordResponseArray($record);
    }
    public function updateRoutingTypeOfCampaign()
    {
        $record =  Campaign::updateRoutingTypeOfCampaign();
        return   updateRecordResponseArray($record);
    }
    public function updateAddressTypeOfCampaign()
    {
        $record =  Campaign::updateAddressTypeOfCampaign();
        return   updateRecordResponseArray($record);
    }
    public function storeCampaignSingleZipcode(Request $request)
    {
        // return response()->json($request->all());
        $request->validate([
            'campaign_uuid' => 'required',
            'zipcode' => 'required|integer',
            'address_type' => 'required',
            'step' => 'required'
        ]);
        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $camGeoLocation = new CampaignGeoLocation();
        $camGeoLocation->zipcode = $request->zipcode;
        $camGeoLocation->address_type = $request->address_type;
        $camGeoLocation->campaign_id = $campaignId;
        $camGeoLocation->save();
        $campaign = Campaign::where('id', $campaignId)->first();
        if ($request->step > $campaign->step) {
            $campaign->step = $request->step;
        }
        $campaign->update();
        if ($camGeoLocation) {
            return $this->respond([
                'status' => true,
                'message' => 'Zipcode has been saved successfully!',
                'data' => [
                    'campaign_geo_location' => new CampaignGeoLocationResource($camGeoLocation)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Zipcode not saved successfully!',
            ]);
        }
    }
    public function updateCampaignSettings(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'type' =>  'required',
            'value' =>  'required',
        ]);
        if ($request->type == 'is_agent') {
            Campaign::updateRecord('uuid', $request->uuid, ['is_agent_include' => $request->value]);
        }
    }

    public function convertTimeToTimeZone(Request $request)
    {

        $time = convertTimeToTimezone(Carbon::now(), 'UTC', $request->timeZone);

        return $this->respond([
            'status' => true,
            'message' => 'Campaign Location has been fetched successfully!',
            'data' => [
                'time' =>  $time
            ],
        ]);
    }

    public function getCurrencies(Request $request)
    {
        $currencies = Currency::getCurrencies($request);
        if (empty($currencies)) {
            return $this->respond([
                'status' => false,
                'message' => 'Currency List Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Currency List has been Fetched  Successfully!',
            'data' => [
                'currencies' => $currencies
            ],
        ]);
    }
}
