<?php

namespace App\Http\Controllers\APi;

use App\Http\Requests\QuestionRequest;
use App\Http\Resources\LmsQuestionResource;
use App\Http\Resources\LmsQuizResource;
use App\Models\LmsQuestion;
use App\Models\LmsQuestionOption;
use App\Models\LmsQuize;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class QuestionController extends APiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/questions",
     * summary="Get Questions",
     * description="Get Questions",
     * operationId="getQuestions",
     * tags={"Question"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort questions by name param",
     *    in="query",
     *    name="name",
     *    example="test questions",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort questions by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort questions by pagination",
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
     *       'message': 'Questions has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question',
     *          'is_active': 'false',
     *       'questionOptions': [
     *           {
     *              'id': '1',
     *              'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *              'name': 'test',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:04:56.000000Z',
     *              'updated_at': '2022-06-21T18:15:55.000000Z',
     *              'deleted_at': null
     *          },
     *          {
     *              'id': 2,
     *              'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *              'name': 'test question opiton',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:16:29.000000Z',
     *              'updated_at': '2022-06-21T18:16:29.000000Z',
     *              'deleted_at': null
     *          },
     *       ]
     *          'quiz_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Questions Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $questions = LmsQuestion::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($questions)) {
            return $this->respond([
                'status' => false,
                'message' => 'Questions Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Questions has been Fetched Successfully!',
            'data' => [
                'questions' => LmsQuestionResource::collection($questions)
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
     * path="/api/questions",
     * summary="Create Question",
     * description="Create Question",
     * operationId="createQuestion",
     * tags={"Question"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Question data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test questions"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="quiz_id", type="integer", format="quiz_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Question has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question',
     *          'is_active': 'false',
     *       'questionOptions': [
     *           {
     *              'id': '1',
     *              'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *              'name': 'test',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:04:56.000000Z',
     *              'updated_at': '2022-06-21T18:15:55.000000Z',
     *              'deleted_at': null
     *          },
     *          {
     *              'id': 2,
     *              'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *              'name': 'test question opiton',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:16:29.000000Z',
     *              'updated_at': '2022-06-21T18:16:29.000000Z',
     *              'deleted_at': null
     *          },
     *       ]
     *          'quiz_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Question Not Found")
     *        )
     *     ),
     * )
     */
    public function store(QuestionRequest $request)
    {
        $question = new LmsQuestion($request->validated());
        $question->name = $request->name;
        $question->is_active = $request->has('is_active') ? $request->is_active : 'true';
        $quiz_id = LmsQuize::getIdByUuid($request->quiz_uuid);
        $question->quiz_id = $quiz_id;
        $question->save();

        $this->assignQuestionOptions($request, $question->id);

        // if(!empty($question) && $request->has('options')) {
        //     $question->questionOptions()->attach($request->options);
        // }


        if (empty($question)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Question has been Created Successfully!',
            'data' => [
                'question' => new LmsQuestionResource($question)
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
     * path="/api/questions/{question_uuid}",
     * summary="Get Question",
     * description="Get Question by questions_uuid",
     * operationId="getQuestionById",
     * tags={"Question"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="question_uuid of Quiz",
     *    in="path",
     *    name="question_uuid",
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
     *       'message': 'Question has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question',
     *          'is_active': 'false',
     *       'questionOptions': [
     *           {
     *              'id': '1',
     *              'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *              'name': 'test',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:04:56.000000Z',
     *              'updated_at': '2022-06-21T18:15:55.000000Z',
     *              'deleted_at': null
     *          },
     *          {
     *              'id': 2,
     *              'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *              'name': 'test question opiton',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:16:29.000000Z',
     *              'updated_at': '2022-06-21T18:16:29.000000Z',
     *              'deleted_at': null
     *          },
     *       ]
     *          'quiz_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Question Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $question = LmsQuestion::where('lms_question_uuid', $id)->first();
        if (empty($question)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Question has been Fetched Successfully!',
            'data' => [
                'question' => new LmsQuestionResource($question)
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
     * path="/api/questions/{question_uuid}",
     * summary="Update Question",
     * description="Update Question",
     * operationId="updateQuestion",
     * tags={"Question"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Question data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test question"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="quiz_id", type="integer", format="quiz_id", example="1"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="question_uuid of Question",
     *    in="path",
     *    name="question_uuid",
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
     *       'message': 'Question has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *       'questionOptions': [
     *           {
     *              'id': '1',
     *              'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *              'name': 'test',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:04:56.000000Z',
     *              'updated_at': '2022-06-21T18:15:55.000000Z',
     *              'deleted_at': null
     *          },
     *          {
     *              'id': 2,
     *              'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *              'name': 'test question opiton',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:16:29.000000Z',
     *              'updated_at': '2022-06-21T18:16:29.000000Z',
     *              'deleted_at': null
     *          },
     *       ]
     *          'quiz_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Question Not Found")
     *        )
     *     ),
     * )
     */
    public function update(QuestionRequest $request, $question_uuid)
    {
        // $data = $request->validated();
        $question = LmsQuestion::where('lms_question_uuid', $question_uuid)->first();
        $question->name = $request->name;
        $question->update();
        if (empty($question)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Not Found',
                'data' =>  []
            ]);
        }
        $options = $request->options;

        foreach ($options as $option) {
            $question_option = LmsQuestionOption::where('lms_question_options_uuid', $option['uuid'])->first();
            $question_option->name = $option['name'];
            $question_option->is_active = $option['is_active'];
            $question_option->is_true = $option['is_true'] == true ? 1 : 0;
            $question_option->update();
        }

        if (empty($question_option)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Options Not Found',
                'data' =>  []
            ]);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Question and options Updated Successfully!',
            'data' => [
                'question' => new LmsQuestionResource($question)
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
     * path="/api/questions/{question_uuid}",
     * summary="Delete Question",
     * description="Delete existing Question",
     * operationId="deleteQuestion",
     * tags={"Question"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="question_uuid of Question",
     *    in="path",
     *    name="Question_uuid",
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
     *       'message': 'Question has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
     *       'questionOptions': [
     *           {
     *              'id': '1',
     *              'uuid': '7609a726-8353-4617-8d47-ae3955d62fbb',
     *              'name': 'test',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:04:56.000000Z',
     *              'updated_at': '2022-06-21T18:15:55.000000Z',
     *              'deleted_at': null
     *          },
     *          {
     *              'id': 2,
     *              'uuid': '6f4cd3f1-2753-426b-9174-50ec9850a507',
     *              'name': 'test question opiton',
     *              'is_active': 'true',
     *              'question_id': '3',
     *              'created_at': '2022-06-21T18:16:29.000000Z',
     *              'updated_at': '2022-06-21T18:16:29.000000Z',
     *              'deleted_at': null
     *          },
     *       ]
     *          'quiz_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Question Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $question = LmsQuestion::where('lms_question_uuid', $id)->first();
        if (empty($question)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Not Found',
                'data' =>  []
            ]);
        }
        $question->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Question has been Deleted Successfully!',
            'data' => [
                'question' => new LmsQuestionResource($question)
            ],
        ]);
    }

    public function assignQuestionOptions($request, $question_id)
    {
        if ($request->has('options')) {
            $options = $request->options;
            foreach ($options as $option) {
                $question_option = new LmsQuestionOption();
                $question_option->lms_question_options_uuid = Str::uuid()->toString();
                $question_option->name = $option['name'];
                $question_option->is_active = $option['is_active'];
                $question_option->is_true = $option['is_true'] == true ? 1 : 0;
                $question_option->question_id = $question_id;
                $question_option->save();
            }
        }
    }
}
