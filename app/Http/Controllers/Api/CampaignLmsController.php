<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignLmsResource;
use App\Models\Campaign;
use App\Models\CampaignLms;
use App\Models\LmsCategory;
use App\Models\LmsCourse;
use Illuminate\Http\Request;

class CampaignLmsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
     * path="/api/campaign-lms",
     * summary="Create Campaign Lms",
     * description="Create Campaign Lms",
     * operationId="createCampaignLms",
     * tags={"Campaign Lms"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass data for campaign lms",
     *    @OA\JsonContent(
     *       required={"type,campaign_id,category_id,course_id"},
     *       @OA\Property(property="type", type="string", format="type", example="agent/client/publisher"),
     *       @OA\Property(property="campaign_id", type="string", format="campaign_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="category_id", type="string", format="category_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_id", type="string", format="course_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Lms has been created successfully!',
     *       'data': {
     *          'campaign_lms': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'type': 'agent',
     *              'campaign_id': 1,
     *              'category_id': 1,
     *              'course_id': 1,
     *              'is_active': 'true',
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
     *       @OA\Property(property="message", type="string", example="Campaign Lms not created")
     *        )
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'campaign_id' => 'required',
            'category_id' => 'required',
            'course_id' => 'required',
            'step' => 'required',
        ]);
        $campaignId = Campaign::getIdByUuid($request->campaign_id);
        $categoryId = LmsCategory::getIdByUuid($request->category_id);
        $courseId = LmsCourse::getIdByUuid($request->course_id);
        $newData = new CampaignLms();
        $newData->uuid = generateUuid();
        $newData->type = $request->type;
        $newData->campaign_id = $campaignId;
        $newData->category_id = $categoryId;
        $newData->course_id = $courseId;
        $newData->save();
        // Campaign::where('uuid', $request->campaign_id)->update(['step' => 8]);
        $campaign = Campaign::where('uuid', $request->campaign_id)->first();
        if($request->step > $campaign->step){
            $campaign->step = $request->step;
        }
        $campaign->update();

        return $this->respond([
            'status' => true,
            'message' => 'Campaign Lms has been Added Successfully!',
            'data' => [
                'campaign_lms' => $newData
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
     * path="/api/campaign-lms",
     * summary="Get Campaign Lms by uuid and type",
     * description="Get Campaign Lms",
     * operationId="getCampaignLmsByuuid",
     * tags={"Campaign Lms"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort campaign lms by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="1ec65f17-25cd-413e-b097-73acb6b5b4e2",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * * @OA\Parameter(
     *    description="sort campaign lms by type param",
     *    in="query",
     *    name="uuid",
     *    example="agent",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Lms has been fetched successfully!',
     *       'data': {
     *          'campaign_lms': {
     *              'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *              'type': 'agent',
     *              'campaign_id': 1,
     *              'category_id': 1,
     *              'course_id': 1,
     *              'is_active': true,
     *              'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Campaign Lms not found")
     *        )
     *     ),
     * )
     */

    public function show(Request $request, $uuid)
    {
        // return response()->json($request->all());
        $campaignId = Campaign::getIdByUuid($uuid);
        $campaignLms = CampaignLms::where('campaign_id', $campaignId)
            ->where('type', $request->type)
            ->join('lms_categories', 'lms_categories.id', '=', 'campaign_lms.category_id')
            ->join('lms_courses', 'lms_courses.id', '=', 'campaign_lms.course_id')
            ->select('campaign_lms.uuid', 'campaign_lms.type', 'lms_categories.lms_category_uuid', 'lms_categories.name', 'lms_courses.lms_course_uuid', 'lms_courses.title')->first();
        // dd($campaignLms);
        if ($campaignLms) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign Lms has been fetched Successfully!',
                'data' => [
                    'campaign_lms' => $campaignLms
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Lms not found!',
                'data' => [],
            ]);
        }
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
     * path="/api/campaign-lms",
     * summary="Update Campaign Lms by uuid",
     * description="Update Campaign Lms",
     * operationId="update Campaign Lms",
     * tags={"Campaign Lms"},
     * security={ {"sanctum": {} }},
     * * @OA\Parameter(
     *    description="Update Campaign Lms by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Campaign Lms data",
     *     @OA\JsonContent(
     *      required={"type,campaign_id,category_id,course_id"},
     *       @OA\Property(property="type", type="string", format="type", example="agent/client/publisher"),
     *       @OA\Property(property="campaign_id", type="string", format="campaign_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="category_id", type="string", format="category_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_id", type="string", format="course_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false")
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign Lms has been updated successfully!',
     *         'data': {
     *          'campaign_lms': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'type': 'agent',
     *             'campaign_id': 1,
     *             'category_id': 1,
     *             'course_id': 1,
     *             'is_active': 'true',
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
     *       @OA\Property(property="message", type="string", example="Campaign Lms not found")
     *        )
     *     ),
     * )
     */

    public function update(Request $request, $uuid)
    {
        $record = CampaignLms::where('uuid', $uuid)->first();

        if ($record) {
            $campaignId = Campaign::getIdByUuid($request->campaign_id);
            $categoryId = LmsCategory::getIdByUuid($request->category_id);
            $courseId = LmsCourse::getIdByUuid($request->course_id);
            $record->type = $request->type;
            $record->category_id = $categoryId;
            $record->course_id = $courseId;
            $record->campaign_id = $campaignId;
            $record->update();
            return $this->respond([
                'status' => true,
                'message' => 'Campaign Lms has been Updated Successfully!',
                'data' => [
                    'campaign_lms' => $record
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Lms not updated!',
                'data' => [],
            ]);
        }
    }

    public function getCoursesByCampaign(Request $request) {
        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $campaignLms = CampaignLms::where('campaign_id', $campaignId)
        ->where('type', $request->type)->get();
        if ($campaignLms) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign Lms has been fetched Successfully!',
                'data' => [
                    'campaignLms' => CampaignLmsResource::collection($campaignLms)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Lms not found!',
                'data' => [],
            ]);
        }
    }

    public function getAllCoursesByCampaign(Request $request) {
        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $campaignLms = CampaignLms::where('campaign_id', $campaignId)
        ->get();
        if ($campaignLms) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign Lms has been fetched Successfully!',
                'data' => [
                    'campaignLms' => CampaignLmsResource::collection($campaignLms)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Lms not found!',
                'data' => [],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid, Request $request)
    {
        // $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $campaignLms = CampaignLms::where('uuid', $uuid)->delete();
        if ($campaignLms) {
            return $this->respond([
                'status' => true,
                'message' => 'Campaign Lms has been deleted Successfully!',
                'data' => [
                    'campaignLms' => $campaignLms
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Lms not found!',
                'data' => [],
            ]);
        }
    }
}
