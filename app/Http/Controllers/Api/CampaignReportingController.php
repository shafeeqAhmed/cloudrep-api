<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CampaignReportingResource;
use App\Http\Resources\PerformanceReportResource;
use App\Http\Resources\CampaignFilterReportResource;
use App\Http\Resources\TimelineSummaryResource;
use App\Http\Resources\TopPerformersResource;
use App\Models\Campaign;
use App\Models\CampaignReporting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\TimeZone;
use App\Models\CampaignFilterReports;

class CampaignReportingController extends ApiController
{

    /**
     * @OA\Post(
     * path="/api/store-campaign-reporting",
     * summary="Create Campaign Reporting",
     * description="Create Campaign Reporting",
     * operationId="createCampaignReporting",
     * tags={"Campaign Reporting"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Reporting data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid"},
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="call_date", type="date", format="call_date", example="2022-11-24"),
     *       @OA\Property(property="profit", type="float", format="profit", example="5.00"),
     *       @OA\Property(property="campaign", type="string", format="campaign", example="test campaign"),
     *       @OA\Property(property="publisher", type="string", format="publisher", example="test publisher"),
     *       @OA\Property(property="caller_id", type="string", format="caller_id", example="+121233344"),
     *       @OA\Property(property="dialed", type="string", format="dialed", example="+121233344"),
     *       @OA\Property(property="time_to_call", type="time", format="time_to_call", example="05:12:20"),
     *       @OA\Property(property="duplicate", type="boolean", format="duplicate", example="true/false"),
     *       @OA\Property(property="hangup", type="enum", format="hangup", example="system/caller/target"),
     *       @OA\Property(property="time_to_connect", type="time", format="time_to_connect", example="05:12:20"),
     *       @OA\Property(property="target", type="string", format="target", example="test target"),
     *       @OA\Property(property="revenue", type="float", format="revenue", example="4.00"),
     *       @OA\Property(property="payout", type="float", format="payout", example="2.00"),
     *       @OA\Property(property="duration", type="time", format="duration", example="05:12:20"),
     *       @OA\Property(property="recording", type="integer", format="recording", example="20"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Reporting has been created successfully!',
     *       'data': {
     *          'campaignReporting': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'call_date': '2022-11-24',
     *              'profit': '5.00',
     *              'campaign': 'test campaign',
     *              'publisher': 'test publisher',
     *              'caller_id': '+1123545454',
     *              'dialed': '+112234545',
     *              'time_to_call': '04:13:20',
     *              'duplicate': 'true',
     *              'hangup': 'system',
     *              'time_to_connect': '04:13:20',
     *              'targert': 'test target',
     *              'revenue': '5.00',
     *              'payout': '5.00',
     *              'duration': '03:12:38',
     *              'recording': '40',
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
     *       @OA\Property(property="message", type="string", example="Campaign Reporting Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCampaignReporting(Request $request)
    {

        $request->validate([
            'campaign_uuid' => 'required|uuid',
        ]);

        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $campaignReporting = new CampaignReporting($request->all());
        $campaignReporting->campaign_id = $campaignId;
        $campaignReporting->duplicate = $request->has('duplicate') ? $request->boolean('duplicate') : false;
        $campaignReporting->save();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Reporting has been created successfully!',
            'data' => [
                'campaignReporting' => new CampaignReportingResource($campaignReporting)
            ],
        ]);
    }


    /**
     * @OA\Get(
     * path="/api/get-campaign-reporting",
     * summary="Get Campaign Reporting",
     * description="Get Campaign Reporting",
     * operationId="getCampaignReporting",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort campaign reporting by campaign param",
     *    in="query",
     *    name="campaign",
     *    example="test campaign",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort campaign reporting by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort campaign reporting by pagination",
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
     *       'message': 'Campaign Reporting has been fetched successfully!',
     *       'data': {
     *          'campaignReporting': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'call_date': '2022-11-24',
     *              'profit': '5.00',
     *              'campaign': 'test campaign',
     *              'publisher': 'test publisher',
     *              'caller_id': '+1123545454',
     *              'dialed': '+112234545',
     *              'time_to_call': '04:13:20',
     *              'duplicate': 'true',
     *              'hangup': 'system',
     *              'time_to_connect': '04:13:20',
     *              'targert': 'test target',
     *              'revenue': '5.00',
     *              'payout': '5.00',
     *              'duration': '03:12:38',
     *              'recording': '40',
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

    public function getCampaignReporting(Request $request)
    {
        $campaignReporting = CampaignReporting::getCampaignReporting($request);
        if (empty($campaignReporting)) {
            return $this->respondNotFound('Campaign Reporting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Reporting has been fetched successfully!',
            'data' => [
                'campaignReporting' => $campaignReporting
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-campaign-users",
     * summary="Get Campaign Users",
     * description="Get Campaign Users",
     * operationId="getCampaignUsers",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Users has been fetched successfully!',
     *       'data': {
     *          'users': {
     *              'clients': [
     *                  {
     *                      'name': 'Publisher',
     *                      'uuid': '944b22d5-9818-4711-b6b0-d9ff125ae246'
     *                  },
     *                  {
     *                      'name': 'Alice',
     *                      'uuid': 'f09ffc43-42b9-4fee-b0fb-bd867687867e'
     *                  },
     *                  {
     *                      'name': 'Client',
     *                      'uuid': 'cd9330c5-5f63-477b-98f6-19adae874021'
     *                  }
     *              ],
     *              'publishers': [
     *                  {
     *                      'name': 'Publisher',
     *                      'uuid': '944b22d5-9818-4711-b6b0-d9ff125ae246'
     *                  },
     *                  {
     *                      'name': 'Client',
     *                      'uuid': 'cd9330c5-5f63-477b-98f6-19adae874021'
     *                  }
     *              ],
     *              'campaigns': [
     *                  {
     *                      'name': 'demo campaign',
     *                      'uuid': '8bf8bdae-16a7-47dc-bda1-9d3564b3122a'
     *                  },
     *                  {
     *                      'name': 'test campaign',
     *                      'uuid': '8bf8bdae-16a7-47dc-bda1-9d3564b3122b'
     *                  },
     *                  {
     *                      'name': 'menu campaign',
     *                      'uuid': '8bf8bdae-16a7-47dc-bda1-9d3564b3122c'
     *                  }
     *              ],
     *              'targets': [
     *                  {
     *                      'name': 'test-target',
     *                      'uuid': '4e18b426-42aa-4fef-95de',
     *                      'destination': '+03224869866'
     *                  },
     *                  {
     *                      'name': 'demo target',
     *                      'uuid': '4e18b426-42aa',
     *                      'destination': '+123456789'
     *                  }
     *              ],
     *              'caller_ids': [
     *                  {
     *                      'caller_id': '+923077020163'
     *                  },
     *                  {
     *                      'caller_id': '+16473897807'
     *                  },
     *                  {
     *                      'caller_id': '+3077020163'
     *                  }
     *              ],
     *              'dialed_numbers': [
     *                  {
     *                      'dialed': '+16402215806'
     *                  },
     *                  {
     *                      'dialed': '+12897960920'
     *                  }
     *              ],
     *              'call_durations': [
     *                  {
     *                      'duration': '00:00:39'
     *                  },
     *                  {
     *                      'duration': '00:00:55'
     *                  },
     *                  {
     *                      'duration': '00:00:29'
     *                  },
     *                  {
     *                      'duration': '00:00:23'
     *                  }
     *              ],
     *              'time_to_connect': [
     *                  {
     *                      'time_to_connect': '00:00:18'
     *                  },
     *                  {
     *                      'time_to_connect': '00:00:16'
     *                  },
     *                  {
     *                      'time_to_connect': '00:00:11'
     *                  },
     *                  {
     *                      'time_to_connect': '00:00:15'
     *                  }
     *              ],
     *              'time_to_call': [],
     *              'revenue': [
     *                  {
     *                      'revenue': 50
     *                  },
     *                  {
     *                      'revenue': 30
     *                  }
     *              ],
     *              'payout': [
     *                  {
     *                     'payout': 15
     *                  },
     *                  {
     *                      'payout': 25
     *                  }
     *              ],
     *              'profit': [
     *                  {
     *                      'profit': 50
     *                  },
     *                  {
     *                      'profit': 15
     *                  },
     *                  {
     *                      'profit': -15
     *                  },
     *                  {
     *                      'profit': -25
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
     *       @OA\Property(property="message", type="string", example="Campaign User not found")
     *        )
     *     ),
     * )
     */

    public function GetCampaignUsers(Request $request)
    {
        $users = CampaignReporting::getCampaignClients($request);
        if (empty($users)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Users has been Fetched  Successfully!',
            'data' => [
                'users' => $users
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-timeline",
     * summary="Get Timeline",
     * description="Get Timeline",
     * operationId="getTimeline",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Pass Timeline data",
     *    @OA\JsonContent(
     *       @OA\Property(property="type", type="string", format="type", example="today/yesterday/lastTwoDays/lastSevenDays/thisWeek/lastWeek/last30Days/thisMonth/lastMonth/last6Month/lastYear/weekly/range"),
     *       @OA\Property(property="user_uuid", type="string", format="user_uuid", example="4e18b426-42aa-4fef-95de-053b29456f69"),
     *       @OA\Property(property="time_zone", type="string", format="time_zone", example="Europe/Andorra"),
     *       @OA\Property(property="dateRange", type="string", format="dateRange", example="{'startDate':'2022-12-18T19:00:00.000Z','endDate':'2022-12-19T06:59:59.999Z'}"),
     *       @OA\Property(property="customFilters", type="string", format="customFilters", example="[{'type':'Name/ID','filter_key':'client_name','operator':'contains','filter_value':'clie','operation':''},{'type':'Name/ID','filter_key':'publisher_name','operator':'contains','filter_value':'pub','operation':'or'}]"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Timeline has been fetched successfully!',
     *       'data': {
     *          'timeLine': [
     *             {
     *                 'count':1,
     *                 'Date': '2022-12-20'
     *             },
     *             {
     *                 'count':2,
     *                 'Date': '2022-12-22'
     *             },
     *             {
     *                 'count':1,
     *                 'Date': '2022-12-24'
     *             },
     *           ],
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
     *       @OA\Property(property="message", type="string", example="Timeline not found")
     *        )
     *     ),
     * )
     */

    public function getTimeline(Request $request)
    {

        $timeLine = CampaignReporting::getTimeline($request);
        if (empty($timeLine)) {
            return $this->respondNotFound('Timeline not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Timeline has been fetched successfully!',
            'data' => [
                'timeLine' => $timeLine
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-performance-summary",
     * summary="Get Performance Summary",
     * description="Get Performance Summary",
     * operationId="getPerformanceSummary",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Pass Performance Summary Data",
     *    @OA\JsonContent(
     *       @OA\Property(property="type", type="string", format="type", example="today/yesterday/lastTwoDays/lastSevenDays/thisWeek/lastWeek/last30Days/thisMonth/lastMonth/last6Month/lastYear/weekly/range"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Performance Summary has been fetched successfully!',
     *       'data': {
     *          'performanceSummary': [
     *             {
     *                  'id': 3,
     *                  'uuid': '8bf8bdae-16a7-47dc-bda1-9d3564b3122c',
     *                  'parent_call_sid': 'CAf27b30976edb8fbf3cbb168ab2972fbf',
     *                  'call_sid': 'CA3942857e484a97fbe0567ee790ff3cbe',
     *                  'call_date': 'Dec 06 13:59:25 PM',
     *                  'campaign_id': 3,
     *                  'publisher_id': 4,
     *                  'client_id': 4,
     *                  'caller_id': '+3077020163',
     *                  'dialed': '+12897960920',
     *                  'time_to_call': null,
     *                  'duplicate': 1,
     *                  'hangup_reason': null,
     *                  'time_to_connect': '00:00:15',
     *                  'target_id': 1,
     *                  'revenue': 0,
     *                  'payout': 25,
     *                  'duration': '00:00:23',
     *                  'recording': 0,
     *                  'profit': -25,
     *                  'hangup': 'system',
     *                  'caller_country': 'GR',
     *                  'call_status': 'completed',
     *                  'initiated_at': '2022-12-06 13:59:25',
     *                  'ringing_at': '2022-12-06 13:59:29',
     *                  'answered_at': '2022-12-06 13:59:40',
     *                  'completed_at': '2022-12-06 14:00:03',
     *                  'created_at': '2022-12-05T07:45:24.000000Z',
     *                  'step': 6,
     *                  'user_id': 4,
     *                  'campaign_name': 'menu campaign',
     *                  'name': 'Client',
     *                  'phone_no': '+13251445665',
     *                  'title': 'test',
     *                  'email': 'client@gmail.com',
     *                  'address': 'canada',
     *                  'country': 'Canada',
     *                  'state': 'Alberta',
     *                  'city': 'torono',
     *                  'zipcode': null,
     *                  'is_published': 0,
     *                  'service_id': 5,
     *                  'category_id': 1,
     *                  'vertical_id': 3,
     *                  'language': 'English',
     *                  'currency': 'Dollar',
     *                  'time_zone': 'GST',
     *                  'start_date': '2022-12-05',
     *                  'start_time': '23:59:00',
     *                  'end_date': null,
     *                  'end_time': null,
     *                  'descripiton': 'test description',
     *                  'website_url': null,
     *                  'deeplink': null,
     *                  'blog_url': null,
     *                  'facebook_url': null,
     *                  'twitter_url': null,
     *                  'linkedin_url': null,
     *                  'client_image': null,
     *                  'agent_image': null,
     *                  'publisher_image': null,
     *                  'cost_per_call': null,
     *                  'client_duration_type': null,
     *                  'client_per_call_duration': null,
     *                  'payout_per_call': null,
     *                  'publisher_duration_type': null,
     *                  'publisher_per_call_duration': null,
     *                  'agent_duration_type': null,
     *                  'campaign_rate': null,
     *                  'air_time': 0,
     *                  'paid_air_time_by': null,
     *                  'air_time_price': null,
     *                  'call_recording_price': null,
     *                  'transcripts': 0,
     *                  'transcript_price': null,
     *                  'call_storage': 0,
     *                  'call_storage_price': null,
     *                  'routing': 'standard',
     *                  'addressType': null,
     *                  'ivr_id': null
     *             },
     *           ],
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
     *       @OA\Property(property="message", type="string", example="Performance Summary not found")
     *        )
     *     ),
     * )
     */
    public function getPerformanceSumary(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);
        $summary = CampaignReporting::getPerformanceSumary($request);
        if (empty($summary)) {
            return $this->respondNotFound('Performance Sumary not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Performance Sumary has been fetched successfully!',
            'data' => [
                'performanceSummary' => $summary
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-performance-report",
     * summary="Get Performance Report",
     * description="Get Performance Report",
     * operationId="getPerformanceReport",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Performance Summary has been fetched successfully!',
     *       'data': {
     *          'performanceReport': [
     *             {
     *             },
     *           ],
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
     *       @OA\Property(property="message", type="string", example="Performance Report not found")
     *        )
     *     ),
     * )
     */

    public function getPerformanceReport(Request $request)
    {
        $report = CampaignReporting::getPerformanceReport($request);
        if (empty($report)) {
            return $this->respondNotFound('Performance Report not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Performance Report has been fetched successfully!',
            'data' => [
                'performanceReport' => PerformanceReportResource::collection($report)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-top-performers",
     * summary="Get Top Performers",
     * description="Get Top Performers",
     * operationId="getTopPerformers",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Pass Top Performers data",
     *    @OA\JsonContent(
     *       @OA\Property(property="type", type="string", format="type", example="publisher/campaign/target"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Top Performers has been fetched successfully!',
     *       'data': {
     *          'topPerformers': [
     *             {
     *                  'name': 'demo campaign',
     *                  'converted': 2,
     *                  'calls': 4,
     *                  'payout': 15,
     *                  'revenue': 80,
     *                  'profit': 65,
     *                  'currency': 'Dollar'
     *              },
     *           ],
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
     *       @OA\Property(property="message", type="string", example="Timeline not found")
     *        )
     *     ),
     * )
     */

    public function getTopPerformers(Request $request)
    {
        $topPerformers = CampaignReporting::getTopPerformers($request);
        if (empty($topPerformers)) {
            return $this->respondNotFound('Top Performers not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Top Performers has been fetched successfully!',
            'data' => [
                'topPerformers' => TopPerformersResource::collection($topPerformers)
                // 'topPerformers' => $topPerformers
            ],
        ]);
    }

    public function getTimeLineSummary(Request $request)
    {
        $summary = CampaignReporting::getTimeLineSummary($request);
        if (empty($summary)) {
            return $this->respondNotFound('Summary not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Summary has been fetched successfully!',
            'data' => [
                'timeLineSummary' => TimelineSummaryResource::collection($summary)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-call-count-of-coutries",
     * summary="Get Call Countries Count",
     * description="Get Call Countries Count",
     * operationId="getCallCountOfCountries",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Call Countries Count has been fetched successfully!',
     *       'data': {
     *          'map': [
     *             {
     *                'CA': 1,
     *                'GR': 3,
     *                'PK': 3
     *              }
     *           ],
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
     *       @OA\Property(property="message", type="string", example="Call Country count not found")
     *        )
     *     ),
     * )
     */

    public function getCallCountOfCountries(Request $request)
    {
        $map = CampaignReporting::getCallCountOfCountries($request);
        if (empty($map)) {
            return $this->respondNotFound('Call Country count not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Call Countries Count has been fetched successfully!',
            'data' => [
                'map' => $map
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-utc-list",
     * summary="Get UTC List",
     * description="Get UTC List",
     * operationId="getUtcList",
     * tags={"Campaign Reporting"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'UtcLists has been fetched successfully!',
     *       'data': {
     *          'utcList': [
     *             {
     *                  'timezone': 'Europe/Andorra',
     *                  'dst_offset': 2
     *              },
     *              {
     *                  'timezone': 'Asia/Dubai',
     *                  'dst_offset': 4
     *              },
     *              {
     *                  'timezone': 'Asia/Kabul',
     *                  'dst_offset': 4.5
     *              },
     *              {
     *                  'timezone': 'America/Antigua',
     *                  'dst_offset': -4
     *              },
     *           ],
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
     *       @OA\Property(property="message", type="string", example="Utc List not found")
     *        )
     *     ),
     * )
     */

    public function getUtcList(Request $request)
    {
        $utcLists = TimeZone::getTimeZone($request);
        if (empty($utcLists)) {
            return $this->respond([
                'status' => false,
                'message' => 'UtcList Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'UtcLists has been Fetched  Successfully!',
            'data' => [
                'utcList' => $utcLists
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-campaign-reporting",
     * summary="Update Campaign Reporting",
     * description="Update Campaign Reporting",
     * operationId="updateCampaignReporting",
     * tags={"Campaign Reporting"},
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     *    description="Update Campaign Reporting by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Reporting data",
     *    @OA\JsonContent(
     *       required={"campaign_uuid"},
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="call_date", type="date", format="call_date", example="2022-11-24"),
     *       @OA\Property(property="profit", type="float", format="profit", example="5.00"),
     *       @OA\Property(property="campaign", type="string", format="campaign", example="test campaign"),
     *       @OA\Property(property="publisher", type="string", format="publisher", example="test publisher"),
     *       @OA\Property(property="caller_id", type="string", format="caller_id", example="+121233344"),
     *       @OA\Property(property="dialed", type="string", format="dialed", example="+121233344"),
     *       @OA\Property(property="time_to_call", type="time", format="time_to_call", example="05:12:20"),
     *       @OA\Property(property="duplicate", type="boolean", format="duplicate", example="true/false"),
     *       @OA\Property(property="hangup", type="enum", format="hangup", example="system/caller/target"),
     *       @OA\Property(property="time_to_connect", type="time", format="time_to_connect", example="05:12:20"),
     *       @OA\Property(property="target", type="string", format="target", example="test target"),
     *       @OA\Property(property="revenue", type="float", format="revenue", example="4.00"),
     *       @OA\Property(property="payout", type="float", format="payout", example="2.00"),
     *       @OA\Property(property="duration", type="time", format="duration", example="05:12:20"),
     *       @OA\Property(property="recording", type="integer", format="recording", example="20"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Reporting has been updated successfully!',
     *       'data': {
     *          'campaignReporting': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *              'call_date': '2022-11-24',
     *              'profit': '5.00',
     *              'campaign': 'test campaign',
     *              'publisher': 'test publisher',
     *              'caller_id': '+1123545454',
     *              'dialed': '+112234545',
     *              'time_to_call': '04:13:20',
     *              'duplicate': 'true',
     *              'hangup': 'system',
     *              'time_to_connect': '04:13:20',
     *              'targert': 'test target',
     *              'revenue': '5.00',
     *              'payout': '5.00',
     *              'duration': '03:12:38',
     *              'recording': '40',
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
     *       @OA\Property(property="message", type="string", example="Campaign Reporting Not Found")
     *        )
     *     ),
     * )
     */

    public function updateCampaignReporting(Request $request)
    {
        $CampaignReporting = CampaignReporting::getCampaignReportingByUuid('uuid', $request->uuid);
        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $request->validate([
            'campaign_uuid' => 'required',
        ]);
        $data = $request->all();
        $data['campaign_id'] = $campaignId;
        if ($request->has('duplicate'))
            $data['duplicate'] = $request->boolean('duplicate');
        $CampaignReporting->update($data);

        if (empty($CampaignReporting)) {
            return $this->respondNotFound('Campaign Reporting not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Reporting has been updated successfully',
            'date' => [
                'CampaignReporting' => new CampaignReportingResource($CampaignReporting)
            ],
        ]);
    }

    public function storeCampaignResults(Request $request)
    {
        $campaignReporting = CampaignReporting::storeCampaignResults($request);

        dd($campaignReporting);
    }

    public function storeCampaignFilterReport(Request $request)
    {
        /*  $request->validate([
            'filter_report_name' => 'unique:campaign_filter_reports,filter_report_name'
        ]); */
        $report = CampaignFilterReports::saveCampaignFilterReport($request);
        return $this->respond([
            'status' => true,
            'message' => 'Filter Report has been stored successfully!',
            'data' => [
                'report' => $report,
            ],
        ]);
    }

    public function getCampaignFilterReports(Request $request)
    {
        $reports = CampaignFilterReports::getCampaignFilterReports($request);
        if (empty($reports)) {
            return $this->respondNotFound('Filter Reports not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Filter Reports has been fetched successfully!',
            'data' => [
                'campaignFilterReports' => CampaignFilterReportResource::collection($reports)
            ],
        ]);
    }

    public function updateCampaignFilterReport(Request $request)
    {
        $request->validate([

            'uuid' => 'required'
        ]);
        (new CampaignFilterReports())->updateCampaignFilterReport($request);
        return $this->respond([
            'status' => true,
            'message' => 'Target Listing has been updated successfully!',
            'data' => []
        ]);
    }

    public function deleteFilterReport(Request $request)
    {
        $data =  $request->validate([
            'uuid' => 'required',
        ]);
        CampaignFilterReports::where('uuid', $request->uuid)->delete($data);
        return $this->respond([
            'status' => true,
            'message' => 'Filter Report has been deleted successfully!',
            'data' => []
        ]);
    }

    public function getUserDashboardRecord(Request $request)
    {
        $dashboard_record = CampaignReporting::getUserDashboardRecord($request);
        if (empty($dashboard_record)) {
            return $this->respondNotFound('User Dshboard Record not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'User Dshboard Record has been fetched successfully!',
            'data' => [
                'dashboard_record' => $dashboard_record
            ],
        ]);
    }
}
