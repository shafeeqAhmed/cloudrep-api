<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TwilioNumberResource;
use App\Models\Campaign;
use App\Models\TwilioNumber;
use App\Models\TwillioNumber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TwilioNumberController extends APiController
{
    /**
     * @OA\Get(
     * path="/api/get-twilio-number",
     * summary="Get Twilio Number",
     * description="Get Twilio Number",
     * operationId="getTwilioNumber",
     * tags={"Twilio Number"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort twilio number by name param",
     *    in="query",
     *    name="name",
     *    example="test twilio number",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort twilio number by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort twilio number by pagination",
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
     *       'message': 'Twilio Number has been fetched successfully!',
     *        'data': {
     *          'twilioNumber': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'number_sid': '71ab8e5c-9422-4ab2-a7af-5d713764ab72',
     *              'number': '+123456789',
     *              'country': 'canada',
     *              'bill_card': '1',
     *              'type': 'local',
     *              'name': 'test twilio number',
     *              'allocated': '12 Nov, 2022',
     *              'renews': '13 Nov, 2022',
     *              'last_call_date': '2022-11-14',
     *              'campaign_name': 'demo campaign',
     *              'campaign_id': '1',
     *              'number_pool': 'test number pool',
     *              'amount': '0',
     *              'publisher_id': 'null',
     *              'status': '1',
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
     *       @OA\Property(property="message", type="string", example="Twilio Number not found")
     *        )
     *     ),
     * )
     */

    public function getTwilioNumber(Request $request)
    {
        $numbers = TwillioNumber::getTwilioNumber($request);
        return $this->respond(getRecordResponseArray(['numbers' => $numbers]));
    }

    /**
     * @OA\Post(
     * path="/api/store-twilio-number",
     * summary="Create Twilio Number",
     * description="Create Twilio Number",
     * operationId="storeTwilioNumber",
     * tags={"Twilio Number"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Twilio Number data",
     *    @OA\JsonContent(
     *       required={"uuid,name"},
     *       @OA\Property(property="uuid",type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="number_sid",type="string", format="number_sid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="number", type="string", format="number", example="+223432424"),
     *       @OA\Property(property="country", type="string", format="country", example="canada"),
     *       @OA\Property(property="bill_card", type="boolean", format="bill_card", example="true/false"),
     *       @OA\Property(property="type", type="enum", format="type", example="local/mobile/tollFree"),
     *       @OA\Property(property="name", type="string", format="name", example="test name"),
     *       @OA\Property(property="allocated", type="date", format="allocated", example="2022-07-19"),
     *       @OA\Property(property="renews", type="date", format="renews", example="2022-07-19"),
     *       @OA\Property(property="last_call_date", type="date", format="last_call_date", example="2022-07-19"),
     *       @OA\Property(property="campaign_name", type="string", format="campaign_name", example="test campaign"),
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuie", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="number_pool", type="string", format="number_pool", example="test number pool"),
     *       @OA\Property(property="amount", type="float", format="amount", example="10.00"),
     *       @OA\Property(property="publisher_uuid", type="string", format="publisher_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="status", type="boolean", format="status", example="true/false"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Twilio Number has been created successfully!',
     *       'data': {
     *          'twilioNumber': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'number_sid': '71ab8e5c-9422-4ab2-a7af-5d713764ab72',
     *              'number': '+123456789',
     *              'country': 'canada',
     *              'bill_card': '1',
     *              'type': 'local',
     *              'name': 'test twilio number',
     *              'allocated': '12 Nov, 2022',
     *              'renews': '13 Nov, 2022',
     *              'last_call_date': '2022-11-14',
     *              'campaign_name': 'demo campaign',
     *              'campaign_id': '1',
     *              'number_pool': 'test number pool',
     *              'amount': '0',
     *              'publisher_id': 'null',
     *              'status': '1',
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
     *       @OA\Property(property="message", type="string", example="Twilio Number Not Found")
     *        )
     *     ),
     * )
     */

    public function storeTwilioNumber(Request $request)
    {
        $twilioNumber = new TwillioNumber($request->all());
        // $campaign = Campaign::getCampaignByUuid('uuid',$request->campaign_uuid);
        $publisherId = User::getIdByUuid($request->publisher_uuid);
        $twilioNumber->status = $request->has('status') ? $request->boolean('status') : false;
        $twilioNumber->bill_card = $request->has('bill_card') ? $request->boolean('bill_card') : true;
        // $twilioNumber->campaign_name = $campaign->name;
        $twilioNumber->campaign_name = 'test campaign';
        // $twilioNumber->campaign_id = 1;
        $twilioNumber->amount = $request->has('amount') ? $request->amount : 0;
        $twilioNumber->publisher_id = $publisherId;
        $twilioNumber->save();

        return $this->respond([
            'status' => true,
            'message' => 'Twilio Number has been created successfully!',
            'data' => [
                'twilioNumber' => new TwilioNumberResource($twilioNumber)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-twilio-number",
     * summary="Update Twilio Number",
     * description="Update Twilio Number",
     * operationId="updateTwilioNumber",
     * tags={"Twilio Number"},
     * security={{"sanctum": {}}},
     *  @OA\Parameter(
     *    description="Update Twilio Number by uuid param",
     *    in="query",
     *    name="uuid",
     *    example="7276eed0-1cd6-4b74-95f1-1f1633254d8f",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Twilio Number data",
     *    @OA\JsonContent(
     *       required={"uuid,name"},
     *       @OA\Property(property="uuid",type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="number_sid",type="string", format="number_sid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="number", type="string", format="number", example="+223432424"),
     *       @OA\Property(property="country", type="string", format="country", example="canada"),
     *       @OA\Property(property="bill_card", type="boolean", format="bill_card", example="true/false"),
     *       @OA\Property(property="type", type="enum", format="type", example="local/mobile/tollFree"),
     *       @OA\Property(property="name", type="string", format="name", example="test name"),
     *       @OA\Property(property="allocated", type="date", format="allocated", example="2022-07-19"),
     *       @OA\Property(property="renews", type="date", format="renews", example="2022-07-19"),
     *       @OA\Property(property="last_call_date", type="date", format="last_call_date", example="2022-07-19"),
     *       @OA\Property(property="campaign_name", type="string", format="campaign_name", example="test campaign"),
     *       @OA\Property(property="campaign_uuid", type="string", format="campaign_uuie", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="number_pool", type="string", format="number_pool", example="test number pool"),
     *       @OA\Property(property="amount", type="float", format="amount", example="10.00"),
     *       @OA\Property(property="publisher_uuid", type="string", format="publisher_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="status", type="boolean", format="status", example="true/false"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Twilio Number has been updated successfully!',
     *       'data': {
     *          'twilioNumber': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'number_sid': '71ab8e5c-9422-4ab2-a7af-5d713764ab72',
     *              'number': '+123456789',
     *              'country': 'canada',
     *              'bill_card': '1',
     *              'type': 'local',
     *              'name': 'test twilio number',
     *              'allocated': '12 Nov, 2022',
     *              'renews': '13 Nov, 2022',
     *              'last_call_date': '2022-11-14',
     *              'campaign_name': 'demo campaign',
     *              'campaign_id': '1',
     *              'number_pool': 'test number pool',
     *              'amount': '0',
     *              'publisher_id': 'null',
     *              'status': '1',
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
     *       @OA\Property(property="message", type="string", example="Twilio Number Not Found")
     *        )
     *     ),
     * )
     */

    public function updateTwilioNumber(Request $request)
    {
        $twilioNumber = TwillioNumber::getTwillioNumberByUuid('uuid', $request->uuid);
        $campaign = Campaign::getCampaignByUuid('uuid', $request->campaign_uuid);
        $publisherId = User::getIdByUuid($request->publisher_uuid);
        $data = $request->all();
        if ($request->has('status'))
            $data['status'] = $request->boolean('status');
        if ($request->has('bill_card'))
            $data['bill_card'] = $request->boolean('bill_card');
        if ($request->has('amount'))
            $data['amount'] = $request->amount;
        $twilioNumber->campaign_name = $campaign->name;
        $twilioNumber->campaign_id = $campaign->id;
        $twilioNumber->publisher_id = $publisherId;
        $twilioNumber->update($data);

        return $this->respond([
            'status' => true,
            'message' => 'Twilio Number has been updated successfully!',
            'data' => [
                'twilioNumber' => new TwilioNumberResource($twilioNumber)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-publishers-for-assign-to-campaign",
     * summary="Get Publishers for Assign to Campaign",
     * description="Get Publishers for Assign to Campaign",
     * operationId="getPublishersForAssignToCampaign",
     * tags={"Twilio Number"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Numbers has been fetched successfully!',
     *        'data': {
     *          'publishers': {
     *             'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
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
     *       @OA\Property(property="message", type="string", example="Numbers not found")
     *        )
     *     ),
     * )
     */

    public function getPublishersForAssignToCampaign(Request $request)
    {

        $role = 'publisher';
        $publishers = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })
            ->select("users.name as label", 'users.user_uuid as publisher_uuid')
            ->get();
        return $this->respond([
            'status' => true,
            'messsage' => 'Publishers For Campaign has been fetched successfully!',
            'data' => [
                'publishers' => $publishers,
            ],
        ]);
    }

    public function getAssignedPublisherNumbers($publisher_uuid)
    {
        $publisherId = User::getIdByUuid($publisher_uuid);
        $twilioNumbers = TwillioNumber::where([['publisher_id', $publisherId], ['campaign_id', null]])->select('uuid as number_uuid', 'number')->get();
        return $this->respond([
            'status' => true,
            'messsage' => 'Publishers Twillio Numbers has been fetched successfully!',
            'data' => [
                'numbers' => $twilioNumbers,
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/assigned-publisher-to-number",
     * summary="Assigned Publisher to Number",
     * description="Assigned Publisher to Number",
     * operationId="assignedPublisherToNumber",
     * tags={"Twilio Number"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass uuid and publisher_uuid data",
     *    @OA\JsonContent(
     *       required={"uuid,publisher_uuid"},
     *       @OA\Property(property="uuid",type="string", format="uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="publisher_uuid",type="string", format="publisher_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher has been successfully assigned to the Number',
     *       'data': {
     *          'twilioNumber': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'publisher_id': '1',
     *               'created_at': '2022-07-25T09:41:48.000000Z','
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
     *       @OA\Property(property="message", type="string", example="Number or Publisher Not Found")
     *        )
     *     ),
     * )
     */


    public function assignedPublisherToNumber(Request $request)
    {
        $twilioNumber = TwillioNumber::where('uuid', $request->uuid)->first();
        $twilioNumber->publisher_id = User::getIdByUuid($request->publisher_uuid);
        $twilioNumber->update();

        return $this->respond([
            'status' => true,
            'message' => 'Publisher has been successfully assigned to the Number',
            'data' => [
                'twilioNumber' => new TwilioNumberResource($twilioNumber)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/assigned-publisher-campaign",
     * summary="Assigned Campaign to Number",
     * description="Assigned Campaign to Number",
     * operationId="assignedCampaignToNumber",
     * tags={"Twilio Number"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass number_uuid and campaign_uuid data",
     *    @OA\JsonContent(
     *       required={"number_uuid,campaign_uuid"},
     *       @OA\Property(property="number_uuid",type="string", format="number_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Campaign has been successfully assigned to the Number against selected publisher',
     *       'data': {
     *          'twilioNumber': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'campaign_id': '1',
     *               'created_at': '2022-07-25T09:41:48.000000Z','
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
     *       @OA\Property(property="message", type="string", example="Number or Campaign Not Found")
     *        )
     *     ),
     * )
     */

    public function assignCampaignToNumber(Request $request)
    {
        $request->validate([
            'campaign_uuid' => 'required|uuid',
            'number_uuid' => 'required|uuid',
        ]);
        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $twilioNumber = TwillioNumber::where('uuid', $request->number_uuid)->first();
        $twilioNumber->campaign_id = $campaignId;
        $twilioNumber->update();
        return $this->respond([
            'status' => true,
            'message' => 'Campaign has been successfully assigned to the Number against selected publisher',
            'data' => [
                'twilioNumber' => new TwilioNumberResource($twilioNumber)
            ],
        ]);
    }
}
