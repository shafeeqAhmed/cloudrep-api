<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Classes\Constants;
use App\Http\Requests\PublisherProfileItem as RequestsPublisherProfileItem;
use App\Http\Resources\DropDownResource;
use App\Http\Resources\PublisherProfileItemResource;
use App\Models\DropDown;
use App\Models\PublisherInterest;
use App\Models\PublisherProfileItem;
use Illuminate\Http\Request;

class PublisherProfileItemController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/publisher-profile-items",
     * summary="Get Publisher Profile Item",
     * description="Get Publisher Profile Item",
     * operationId="getPublisherProfileItem",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort publisher profile items by name param",
     *    in="query",
     *    name="name",
     *    example="test publisher profile item",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort publisher profile items by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort publisher profile items by pagination",
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
     *       'message': 'Publisher Profile Item has been Fetched Successfully!',
     *      'data': {
     *          'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $publisher_profile_items = PublisherProfileItem::when($request->q, function ($query, $q) {
            return $query->where('company_website', 'LIKE', "%{$q}%");
        })
            // ->when($request->type, function ($query, $type) {
            //     return $query->where('type', 'LIKE', "%{$type}%");
            // })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($publisher_profile_items)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Profile Items has been Fetched Successfully!',
            'data' => [
                'publisher_profile_items' => PublisherProfileItemResource::collection($publisher_profile_items)
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * path="/api/publisher-profile-items",
     * summary="Create Publisher Profile Item",
     * description="Create Publisher Profile Item",
     * operationId="createPublisherProfileItem",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Profile Item data",
     *    @OA\JsonContent(
     *       required={"company_website","belongs_to"},
     *       @OA\Property(property="company_website", type="string", format="company_website", example="test.com"),
     *       @OA\Property(property="belongs_to", type="enum", format="belongs_to", example="client_website"),
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Profile Item has been Created Successfully!',
     *       'data': {
     *          'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function store(RequestsPublisherProfileItem $request)
    {
        $exsist = PublisherProfileItem::where('user_id', $request->user()->id)->first();
        if ($exsist) {
            $exsist->company_website = $request->company_website;
            $exsist->update();
            return $this->respond([
                'status' => true,
                'message' => 'Publisher Company Website has been Created Successfully!',
                'data' => [
                    'publisher_profile_item' => new PublisherProfileItemResource($exsist)
                ],
            ]);
        } else {
            $newPublisher = new PublisherProfileItem($request->validated());
            $newPublisher->company_website = $request->company_website;
            $newPublisher->step = 1;
            $newPublisher->user_id = $request->user()->id;
            $newPublisher->save();
            return $this->respond([
                'status' => true,
                'message' => 'Publisher Company Website has been Created Successfully!',
                'data' => [
                    'publisher_profile_item' => new PublisherProfileItemResource($newPublisher)
                ],
            ]);
        }
        return $this->respond([
            'status' => false,
            'message' => 'Publisher Profile Item Not Found',
            'data' =>  []
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
     * path="/api/publisher-profile-items/{uuid}",
     * summary="Get Publisher Profile Item by uuid",
     * description="Get Publisher Profile Item by uuid",
     * operationId="getPublisherProfileItemById",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Publisher Profile Item",
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
     *       'message': 'Publisher Profile Item has been Fetched Successfully!',
     *       'data': {
     *          'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $publisher_profile_item = PublisherProfileItem::where('uuid', $id)->first();
        if (empty($publisher_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Profile Item has been Fetched Successfully!',
            'data' => [
                'publisher_profile_item' => new PublisherProfileItemResource($publisher_profile_item)
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * path="/api/publisher-profile-items",
     * summary="Update Publisher Profile Item of Current User",
     * description="Update Publisher Profile Item",
     * operationId="updatePublisherProfile",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Profile Item data",
     *     @OA\JsonContent(
     *       required={"company_website","belongs_to"},
     *       @OA\Property(property="company_website", type="string", format="company_website", example="test.com"),
     *       @OA\Property(property="belongs_to", type="enum", format="belongs_to", example="client_website"),
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Profile Item has been Updated Successfully!',
     *        'data': {
     *         'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function update(RequestsPublisherProfileItem $request, $id)
    {
        $belongs = [Constants::$PUBLISHER_BELONGS_TO_MY_WEBSITE, Constants::$PUBLISHER_BELONGS_TO_OTHER_WEBSITE, Constants::$PUBLISHER_BELONGS_TO_CLIENT_WEBSITE];
        $publisher_profile_item = PublisherProfileItem::where('uuid', $id)->first();
        $data = $request->validated();
        if ($request->has('company_website'))
            $data['company_website'] = $request->company_website;
        if ($request->has('belongs_to'))
            if (in_array($request->belongs_to, $belongs)) {
                $data['belongs_to'] = $request->belongs_to;
            } else {
                return $this->respond([
                    'status' => false,
                    'message' => 'Belongs to must be ' . implode(',', $belongs) . ''
                ]);
            }
        $publisher_profile_item->user_id = $request->user()->id;
        $publisher_profile_item->update($data);
        if (empty($publisher_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Profile Item has been Updated Successfully!',
            'data' => [
                'publisher_profile_item' => new PublisherProfileItemResource($publisher_profile_item)
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Delete(
     * path="/api/publisher-profile-items/{uuid}",
     * summary="Delete Publisher Profile Item",
     * description="Delete existing Publisher Profile Item",
     * operationId="deletePublisherProfileItem",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="uuid of Publisher Profile Item",
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
     *       'message': 'Publisher Profile Item has been Deleted Successfully!',
     *        'data': {
     *          'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $publisher_profile_item = PublisherProfileItem::where('uuid', $id)->first();
        if (empty($publisher_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Profile Item Not Found',
                'data' =>  []
            ]);
        }
        $publisher_profile_item->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Profile Item has been Fetched Successfully!',
            'data' => [
                'publisher_profile_item' => new PublisherProfileItemResource($publisher_profile_item)
            ],
        ]);
    }


    /**
     * @OA\Get(
     * path="/api/my-publisher-profile-items",
     * summary="Get Publisher Profile Item By Current User",
     * description="Get ublisher Profile Item by login user",
     * operationId="getPublisherProfile",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Profile Item has been Fetched Successfully!',
     *       'data': {
     *          'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    public function getPublisherProfileByUser(Request $request)
    {
        $publisher_profile_item = PublisherProfileItem::where('user_id', $request->user()->id)->first();
        if (empty($publisher_profile_item)) {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Profile Items has been Fetched Successfully!',
            'data' => [
                'publisher_profile_item' => new PublisherProfileItemResource($publisher_profile_item)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/publisher-options",
     * summary="Get Publisher Options",
     * description="Get Publisher Options",
     * operationId="getPublisherOption",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Options Item has been Fetched Successfully!',
     *       'data': {
     *          'publisher_options': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': ''Organic Search',
     *             'value': 'Organic Search',
     *             'type': 'publisher',
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
     *       @OA\Property(property="message", type="string", example="Publisher Profile Item Not Found")
     *        )
     *     ),
     * )
     */
    // public function getPublisherOption()
    // {
    //     $publisher_option = DropDown::where('type', 'publisher_website')->get();
    //     if (empty($publisher_option)) {
    //         return $this->respond([
    //             'status' => false,
    //             'message' => 'Publisher Option Not Found',
    //             'data' =>  []
    //         ]);
    //     }
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Pubisher Options has been Fetched Successfully!',
    //         'data' => [
    //             'publisher_options' => DropDownResource::collection($publisher_option)
    //         ],
    //     ]);
    // }

    /**
     * @OA\Post(
     * path="/api/store-publisher-options",
     * summary="Create Publisher Profile Option",
     * description="Create Publisher Option",
     * operationId="createPublisherOption",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Option data",
     *    @OA\JsonContent(
     *       @OA\Property(property="step", type="integer", format="step", example="2"),
     *       @OA\Property(property="dropdown_id", type="string", format="dropdown_id", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Option has been Created Successfully!',
     *       'data': {
     *          'publisher_profile_item': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'company_website': ''test.com',
     *             'belongs_to': 'client_website',
     *             'user_id': '1',
     *             'step' : '2',
     *             'dropdown_id': '1',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
     *
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
     *       @OA\Property(property="message", type="string", example="Publisher Option Not Found")
     *        )
     *     ),
     * )
     */
    public function storePublisherOption(Request $request)
    {
        $publisher = PublisherProfileItem::where('user_id', $request->user()->id)->first();
        if ($publisher) {
            if ($request->publisher_website) {
                $publisher->dropdown_id = $request->publisher_website;
                $publisher->step = 2;
                $publisher->update();
            }
            if ($request->publisher_interest) {
                $interest = PublisherInterest::where('user_id', $request->user()->id)->first();
                if ($interest) {
                    $interest->dropdown_id = $request->publisher_interest;
                    $interest->update();
                } else {
                    $publish_interest = new PublisherInterest();
                    $publish_interest->uuid = generateUuid();
                    $publish_interest->name = $request->publisher_interest_name;
                    $publish_interest->is_active = true;
                    $publish_interest->user_id = $request->user()->id;
                    $publish_interest->dropdown_id = $request->publisher_interest;
                    $publish_interest->save();
                }
            }
            return $this->respond([
                'status' => true,
                'message' => 'Publisher Options Saved Successfully!',
                'data' => [
                    'publisher_options' => new PublisherProfileItemResource($publisher)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Publisher Not Found',
            ]);
        }
    }


    /**
     * @OA\Get(
     * path="/api/selected-publisher-option",
     * summary="Get Selected Publisher Options",
     * description="Get Selected Publisher Options",
     * operationId="getSelectedPublisherOption",
     * tags={"Publisher Profile Item"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Publisher Selected Options has been Fetched Successfully!',
     *       'data': {
     *          'selected_publisher_option': {
     *             'label': 'Organic Search',
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
     *       @OA\Property(property="message", type="string", example="Publisher Selected Option Not Found")
     *        )
     *     ),
     * )
     */
    // public function getSelectedPublisherOption(Request $request)
    // {
    //     $selected_publisher_option = PublisherProfileItem::join('dropdowns', 'dropdowns.id', '=', 'publisher_profile_items.dropdown_id')
    //         ->where('publisher_profile_items.user_id', $request->user()->id)
    //         // ->where('agent_trafic_sources.is_active','=','1')
    //         ->first('dropdowns.label');
    //     if (empty($selected_publisher_option)) {
    //         return $this->respond([
    //             'status' => false,
    //             'message' => 'Selected Publisher Option Not Found',
    //             'data' =>  []
    //         ]);
    //     }
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Selected Pubisher Option has been Fetched Successfully!',
    //         'data' => [
    //             'selected_publisher_option' => $selected_publisher_option
    //         ],
    //     ]);
    // }

    public function getPublishOptionDropdownList()
    {
        $publish_website_dropdown = DropDown::where('type', 'publisher website')->get();
        $publish_interest_dropdown = DropDown::where('type', '=', 'publisher onboarding interest')->get();
        if ($publish_website_dropdown || $publish_interest_dropdown) {
            return $this->respond([
                'status' => true,
                'message' => 'Publish dropdowns List has been Fetched Successfully!',
                'data' => [
                    'publish_website_dropdown' => $publish_website_dropdown,
                    'publish_interest_dropdown' => $publish_interest_dropdown
                ],
            ]);
        }
    }

    public function getPublisherSelectedDropdowns(Request $request)
    {
        $publish = PublisherProfileItem::where('user_id', $request->user()->id)->first();
        $dropdown = DropDown::where('id', $publish->dropdown_id)->first();
        $publisher_interest = PublisherInterest::join('dropdowns', 'dropdowns.id', '=', 'publisher_interests.dropdown_id')
            ->where('publisher_interests.user_id', $request->user()->id)
            ->with('dropdown')
            ->select()->first('dropdowns.*');
        return $this->respond([
            'status' => true,
            'message' => 'Publisher Selected Website dropdown has been Fetched Successfully!',
            'data' => [
                'dropdown' => $dropdown,
                'publisher_interest' => $publisher_interest
            ],
        ]);
    }
}
