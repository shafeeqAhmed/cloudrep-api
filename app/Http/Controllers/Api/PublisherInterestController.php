<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PublisherInterestResource;
use App\Models\DropDown;
use App\Models\PublisherInterest;
use App\Models\PublisherProfileItem;
use Illuminate\Http\Request;

class PublisherInterestController extends APiController
{

    /**
     * @OA\Post(
     * path="/api/store-publisher-interests",
     * summary="Create Publisher Interest",
     * description="Create Publisher Interest",
     * operationId="createPublisherInterest",
     * tags={"Publisher Interest"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Interest data",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", format="name", example="test"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true"),
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="1"),
     *       @OA\Property(property="dropdown_id", type="integer", format="dropdown_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Interest has been Created Successfully!',
     *       'data': {
     *          'publisher_interest': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'name': 'test',
     *             'is_active': 'true',
     *             'user_id': '1',
     *             'dropdown_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
     *             'updated_at': '2022-06-04T18:36:16.000000Z',
     *             'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Publisher Interest Not Found")
     *        )
     *     ),
     * )
     */
    public function storePublisherInterest(Request $request)
    {
        $publisher_interest = new PublisherInterest();
        $publisher_interest->name = $request->name;
        $publisher_interest->is_active = $request->has('is_active') ? $request->is_active : true;
        $publisher_interest->user_id = $request->user()->id;
        $dropdown = DropDown::getIdByUuid($request->dropdown_id);
        $publisher_interest->dropdown_id = $dropdown;
        $publisher_interest->save();
        // if ($request->has('dropdowns')) {
        //     $dropdowns = explode(',', $request->dropdowns);
        //     foreach ($dropdowns as $dropdown) {
        //         $dropdowns = DropDown::getIdByUuid($dropdown);
        //         $publisher_interest->dropdowns()->attach($dropdown);
        //     }
        // }
        if (empty($publisher_interest)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Insterest Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Insterest has been Created Successfully!',
            'data' => [
                'publisher_interest' => new PublisherInterestResource($publisher_interest)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-publisher-interests",
     * summary="Get Publisher Interest By Current User",
     * description="Get Publisher Interest by login user",
     * operationId="getPublisherInterest",
     * tags={"Publisher Interest"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Interest has been Fetched Successfully!',
     *       'data': {
     *          'publisher_interest': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'name': 'test',
     *             'is_active': 'true',
     *             'user_id': '1',
     *             'dropdown_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
     *             'updated_at': '2022-06-04T18:36:16.000000Z',
     *             'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Publisher Interest Item Not Found")
     *        )
     *     ),
     * )
     */
    public function getPublisherInterest(Request $request)
    {
        $publisher_interest = PublisherInterest::join('dropdowns', 'dropdowns.id', '=', 'publisher_interests.dropdown_id')
            // ->where('publisher_interests.user_id',$request->user()->id)
            ->with('dropdown')
            ->select()->first('dropdowns.*');
        if (empty($publisher_interest)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Insterest Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Insterest has been Fetched Successfully!',
            'data' => [
                'publisher_interest' => $publisher_interest
            ],
        ]);
    }

}
