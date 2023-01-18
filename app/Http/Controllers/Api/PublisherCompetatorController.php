<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PublisherCompetatorRequest;
use App\Http\Resources\PublisherCompetatorResource;
use App\Models\PublisherCompetator;
use App\Models\PublisherProfileItem;
use Illuminate\Http\Request;

class PublisherCompetatorController extends ApiController
{

    /**
     * @OA\Post(
     * path="/api/store-publisher-competators",
     * summary="Create Publisher Competator",
     * description="Create Publisher Competator",
     * operationId="createPublisherCompetator",
     * tags={"Publisher Competator"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Competator data",
     *    @OA\JsonContent(
     *       required={"url"},
     *       @OA\Property(property="url", type="string", format="url", example="test.com"),
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Competator has been Created Successfully!',
     *       'data': {
     *          'publisher_competator': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'url': 'test.com',
     *             'user_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Competator Not Found")
     *        )
     *     ),
     * )
     */
    public function storePublisherCompetator(PublisherCompetatorRequest $request)
    {
        $publisher_competator = new PublisherCompetator($request->validated());
        $publisher_competator->url = $request->url;
        $publisher_competator->user_id = $request->user()->id;
        $publisher_competator->save();
        if (empty($publisher_competator)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Competator Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Competator has been Created Successfully!',
            'data' => [
                'publisher_competator' => new PublisherCompetatorResource($publisher_competator)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-publisher-competators",
     * summary="Get Publisher Competator By Current User",
     * description="Get Publisher Competator by login user",
     * operationId="getPublisherCompetator",
     * tags={"Publisher Competator"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Competator has been Fetched Successfully!',
     *       'data': {
     *          'publisher_competator': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'ulr': 'test.com',
     *             'user_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Competator Item Not Found")
     *        )
     *     ),
     * )
     */
    public function getPublisherCompetator(Request $request) {
        $publisher_competator = PublisherCompetator::where('user_id',$request->user()->id)->get();
        // $publisher_competator->url = $request->url;
        // $publisher_competator->user_id = $request->user()->id;
        if (empty($publisher_competator)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Competator Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Competator has been Fetched Successfully!',
            'data' => [
                'publisher_competator' => PublisherCompetatorResource::collection($publisher_competator)
            ],
        ]);
    }


}
