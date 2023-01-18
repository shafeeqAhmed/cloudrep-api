<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\QuizRequest;
use App\Http\Resources\LmsQuestionResource;
use App\Http\Resources\LmsQuizResource;
use App\Http\Resources\LmsResultResource;
use App\Models\LmsLesson;
use App\Models\LmsQuestion;
use App\Models\LmsQuestionOption;
use App\Models\LmsQuiz;
use App\Models\LmsQuize;
use App\Models\LmsResult;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;

class QuizController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/quizes",
     * summary="Get Quizes",
     * description="Get Quizes",
     * operationId="getQuizes",
     * tags={"Quiz"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort quizes by name param",
     *    in="query",
     *    name="name",
     *    example="test lessons",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort quizes by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort quizes by pagination",
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
     *       'message': 'Quizes has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *      'questions': [
     *          {
     *              'id': 3,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              'questionOptions': [
     *                 {
     *                   'id': '1',
     *                   'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *                   'name': 'test',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:04:56.000000Z',
     *                   'updated_at': '2022-06-21T18:15:55.000000Z',
     *                   'deleted_at': null
     *                  },
     *                  {
     *                   'id': 2,
     *                   'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *                   'name': 'test question opiton',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:16:29.000000Z',
     *                   'updated_at': '2022-06-21T18:16:29.000000Z',
     *                   'deleted_at': null
     *                  },
     *              ]
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *           },
     *           {
     *              'id': 4,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              'questionOptions': [],
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *          }
     *       ]
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Quizes Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {

        $quizes = LmsQuize::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($quizes)) {
            return $this->respond([
                'status' => false,
                'message' => 'Quizes Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Quizes has been Fetched Successfully!',
            'data' => [
                'quizes' => LmsQuizResource::collection($quizes)
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
     * path="/api/quizes",
     * summary="Create Quiz",
     * description="Create Quiz",
     * operationId="createQuiz",
     * tags={"Quiz"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Quiz data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test quiz"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Quiz has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *       'questions': [
     *          {
     *              'id': 3,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              'questionOptions': [
     *                 {
     *                   'id': '1',
     *                   'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *                   'name': 'test',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:04:56.000000Z',
     *                   'updated_at': '2022-06-21T18:15:55.000000Z',
     *                   'deleted_at': null
     *                  },
     *                  {
     *                   'id': 2,
     *                   'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *                   'name': 'test question opiton',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:16:29.000000Z',
     *                   'updated_at': '2022-06-21T18:16:29.000000Z',
     *                   'deleted_at': null
     *                  },
     *              ]
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *           },
     *           {
     *              'id': 4,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              questionOptions: [],
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *          }
     *       ]
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Quiz Not Found")
     *        )
     *     ),
     * )
     */
    public function store(QuizRequest $request)
    {
        $quiz = new LmsQuize($request->validated());
        $quiz->name = $request->name;
        $quiz->percentage = $request->percentage;
        $quiz->is_active = $request->has('is_active') ? $request->is_active : 'true';
        $lesson_id = LmsLesson::getIdByUuid($request->uuid);
        $quiz->lesson_id = $lesson_id;
        if ($request->has('duration')) {
            $quiz->duration = $request->duration * 60;
        }
        if ($request->has('noOfQuestion')) {
            $quiz->no_of_question = $request->noOfQuestion;
        }
        $quiz->save();

        if (empty($quiz)) {
            return $this->respond([
                'status' => false,
                'message' => 'Quiz Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Quiz has been Created Successfully!',
            'data' => [
                'quiz' => new LmsQuizResource($quiz)
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
     * path="/api/quizes/{quiz_uuid}",
     * summary="Get Quiz",
     * description="Get Quiz by quiz_uuid",
     * operationId="getQuizById",
     * tags={"Quiz"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="quiz_uuid of Quiz",
     *    in="path",
     *    name="quiz_uuid",
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
     *       'message': 'Quiz has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *       'questions': [
     *          {
     *              'id': 3,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              'questionOptions': [
     *                 {
     *                   'id': '1',
     *                   'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *                   'name': 'test',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:04:56.000000Z',
     *                   'updated_at': '2022-06-21T18:15:55.000000Z',
     *                   'deleted_at': null
     *                  },
     *                  {
     *                   'id': 2,
     *                   'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *                   'name': 'test question opiton',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:16:29.000000Z',
     *                   'updated_at': '2022-06-21T18:16:29.000000Z',
     *                   'deleted_at': null
     *                  },
     *              ]
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *           },
     *           {
     *              'id': 4,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              questionOptions: [],
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *          }
     *       ]
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Quiz Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $quiz = LmsQuize::where('lms_quiz_uuid', $id)->first();
        if (empty($quiz)) {
            return $this->respond([
                'status' => false,
                'message' => 'Quiz Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Quiz has been Fetched Successfully!',
            'data' => [
                'quiz' => new LmsQuizResource($quiz)
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
     * path="/api/quizes/{quize_uuid}",
     * summary="Update Quiz",
     * description="Update Quiz",
     * operationId="updateQuiz",
     * tags={"Quiz"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Quiz data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test quiz"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="quize_uuid of Quiz",
     *    in="path",
     *    name="quize_uuid",
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
     *       'message': 'Quiz has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *       'questions': [
     *          {
     *              'id': 3,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              'questionOptions': [
     *                 {
     *                   'id': '1',
     *                   'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *                   'name': 'test',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:04:56.000000Z',
     *                   'updated_at': '2022-06-21T18:15:55.000000Z',
     *                   'deleted_at': null
     *                  },
     *                  {
     *                   'id': 2,
     *                   'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *                   'name': 'test question opiton',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:16:29.000000Z',
     *                   'updated_at': '2022-06-21T18:16:29.000000Z',
     *                   'deleted_at': null
     *                  },
     *              ]
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *           },
     *           {
     *              'id': 4,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              questionOptions: [],
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *          }
     *       ]
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Quiz Not Found")
     *        )
     *     ),
     * )
     */
    public function update(QuizRequest $request, $id)
    {
        $quiz = LmsQuize::where('lms_quiz_uuid', $id)->first();
        $data = $request->validated();
        if ($request->has('is_active'))
            $data['is_active'] = $request->is_active;
        if ($request->has('lesson_id'))
            $lesson_id = LmsLesson::getIdByUuid($request->lesson_id);
        $data['lesson_id'] = $lesson_id;
        if ($request->has('percentage'))
            $data['percentage'] = $request->percentage;
        if ($request->has('duration'))
            $data['duration'] = $request->duration;

        $quiz->update($data);

        if (empty($quiz)) {
            return $this->respond([
                'status' => false,
                'message' => 'Quiz Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Quiz has been Updated Successfully!',
            'data' => [
                'quiz' => new LmsQuizResource($quiz)
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
     * path="/api/quizes/{quiz_uuid}",
     * summary="Delete Quiz",
     * description="Delete existing Quiz",
     * operationId="deleteQuiz",
     * tags={"Quiz"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="quiz_uuid of Quiz",
     *    in="path",
     *    name="quiz_uuid",
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
     *       'message': 'Quiz has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *       'questions': [
     *          {
     *              'id': 3,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              'questionOptions': [
     *                 {
     *                   'id': '1',
     *                   'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *                   'name': 'test',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:04:56.000000Z',
     *                   'updated_at': '2022-06-21T18:15:55.000000Z',
     *                   'deleted_at': null
     *                  },
     *                  {
     *                   'id': 2,
     *                   'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *                   'name': 'test question opiton',
     *                   'is_active': 'true',
     *                   'question_id': '3',
     *                   'created_at': '2022-06-21T18:16:29.000000Z',
     *                   'updated_at': '2022-06-21T18:16:29.000000Z',
     *                   'deleted_at': null
     *                  },
     *              ]
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *           },
     *           {
     *              'id': 4,
     *              'uuid': 'd3d7fe27-7397-4e9f-a31a-3c7009054686',
     *              'name': 'test question',
     *              'is_active': false,
     *              questionOptions: [],
     *              'created_at': '2022-06-21T18:13:44.000000Z',
     *              'updated_at': '2022-06-21T18:13:44.000000Z',
     *              'deleted_at': null
     *          }
     *       ]
     *          'created_at': '2022-06-04T18:32:20.000000Z',
     *          'updated_at': '2022-06-04T18:36:16.000000Z',
     *          'deleted_at': null
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
     *       @OA\Property(property="message", type="string", example="Quiz Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $quiz = LmsQuize::where('lms_quiz_uuid', $id)->first();
        if (empty($quiz)) {
            return $this->respond([
                'status' => false,
                'message' => 'Quiz Not Found',
                'data' =>  []
            ]);
        }
        $quiz->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Quiz has been Deleted Successfully!',
            'data' => [
                'quiz' => new LmsQuizResource($quiz)
            ],
        ]);
    }

    // public function getTest(Request $request) {
    //         $quiz = LmsQuize::with('questions')->get();

    //         // $options = LmsQuestionOption::whereIn('lms_question_options_uuid',$request->option_uuid)->where('is_true',1)->query();
    //         // foreach($options as $option) {
    //             $options  = LmsQuestionOption::whereIn('lms_question_options_uuid',$request->option_uuid)->where('is_true',1)
    //             ->join('lms_questions', 'lms_questions.id', '=', 'lms_question_options.question_id')
    //             ->join('lms_quizes','lms_quizes.id', '=', 'lms_questions.quiz_id')
    //             ->select('lms_question_options.*','lms_quizes.duration')->get();
    //             foreach($options as $option) {
    //                 if($request->duration) {
    //                     foreach($request->duration as $duration) {
    //                         $option['attemptTime'] = $option->duration - intval($duration);
    //                     }
    //                 }
    //             $options['updatedTime'] = $option['attemptTime'];
    //             }
    //             $options['optionCount'] = count($options);
    //         return $this->respond([
    //             'status' => true,
    //             'message' => 'Result has been fetched successfully!',
    //             'data' => [
    //                 // 'quiz' => LmsQuizResource::collection($quiz),
    //                 'result' => [
    //                     'options' => $options,
    //                 ],
    //             ],
    //         ]);
    //     }

    public function getQuiz(Request $request, $uuid)
    {
        $quizId = LmsQuize::getIdByUuid($uuid);
        $quiz = LmsQuize::where('id', $quizId)->first();

        //lesson against quiz
        $lesson = LmsLesson::where('id', $quiz->lesson_id)->first();
        // $lesson_name = $lesson->name;

        //Question against quiz
        $question = LmsQuestion::where('quiz_id', $quizId)->inRandomOrder()->limit(1)->first();

        if (empty($quiz)) {
            return $this->respond([
                'status' => false,
                'message' => 'Quiz not Found',
            ]);
        } else {
            return $this->respond([
                'status' => true,
                'message' => 'Quiz has been Fetched successfully!',
                'data' => [
                    'quiz' => new LmsQuizResource($quiz),
                    // 'quiz' => $quiz,
                    'quiz_lesson' => $lesson,
                    'quiz_question' => new LmsQuestionResource($question),
                ],
            ]);
        }
    }

    public function postTest(Request $request)
    {
        $quizId = LmsQuize::getIdByUuid($request->quiz_uuid);
        $questionId = LmsQuestion::getIdByUuid($request->question_uuid);
        $option  = LmsQuestionOption::where('lms_question_options_uuid', $request->option_uuid)
            ->where('question_id', $questionId)
            ->where('is_true', 1)->first();

        // $ifExt = LmsResult::where(['quiz_id'=> $quizId, 'user_id'=> $request->user()->id, 'question_id'=> ])
        // $result = LmsResult::updateOrCreate(
        //     [
        //         'user_id' => $request->user()->id,
        //         'quiz_id' => $quizId,
        //         'question_id' => $questionId
        //     ],[
        //     'quiz_id' => $quizId,
        //     'question_id' => $questionId,
        //     'option_id' => !empty($option->id) ? $option->id : null,
        //     'user_id' => $request->user()->id
        // ]);

        $result = LmsResult::create([
            'quiz_id' => $quizId,
            'question_id' => $questionId,
            'option_id' => !empty($option->id) ? $option->id : null,
            'user_id' => $request->user()->id,
            'time_spend' => $request->time_spend
        ]);
        return $this->respond([
            'status' => true,
            'message' => 'Result has been saved successfully!',
            'data' => [
                'result' => new LmsResultResource($result),
            ],
        ]);
    }
    public function getQuizResult(Request $request)
    {
        // return response()->json($request->quiz_uuid);
        $quizId = LmsQuize::getIdByUuid($request->quiz_uuid);
        // $totalQuestion = LmsQuestion::where('quiz_id', $quizId)->count();
        $totalQuestion = LmsQuize::where('id', $quizId)->select('no_of_question')->first();
        $getResult = LmsResult::where('user_id', $request->user()->id)->where('quiz_id', $quizId)->get();
        $correctOptions = LmsResult::where('user_id', $request->user()->id)->where('quiz_id', $quizId)->whereNotNull('option_id')->count();
        $attemptedQuestions = LmsResult::where('user_id', $request->user()->id)->where('quiz_id', $quizId)->whereNotNull('question_id')->count();
        $totalDurationSpend = LmsResult::where('user_id', $request->user()->id)->where('quiz_id', $quizId)->whereNotNull('question_id')->pluck('time_spend');
        // return response()->json($totalDurationSpend);
        return $this->respond([
            'status' => true,
            'message' => 'Test Result has been Fetched successfully!',
            'data' => [
                'getResult' => LmsResultResource::collection($getResult),
                'attemptedQuestions' => $attemptedQuestions,
                'correctOptions' => $correctOptions,
                'totalQuestion' => $totalQuestion,
                'totalDurationSpend' => $totalDurationSpend
            ],
        ]);
    }

    public function reTakeQuiz(Request $request, $uuid)
    {
        $quizId = LmsQuize::getIdByUuid($uuid);
        $lmsResult = LmsResult::where(['quiz_id' => $quizId, 'user_id' => $request->user()->id])->forceDelete();
        // return response()->json($lmsResult);
        if ($lmsResult) {
            return $this->respond([
                'status' => true,
                'message' => 'Result has been deleted successfully!',
                'data' => [],
            ]);
        }
    }
}
