<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\QuestionOptionRequest;
use App\Http\Resources\LmsQuestionOptionResource;
use App\Models\LmsQuestion;
use App\Models\LmsQuestionOption;
use Illuminate\Http\Request;

class QuestionOptionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/question-options",
     * summary="Get Question Options",
     * description="Get Question Options",
     * operationId="getQuestionOptions",
     * tags={"Question Option"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort question_options by name param",
     *    in="query",
     *    name="name",
     *    example="test question_options",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort question_options by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort question_options by pagination",
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
     *       'message': 'Question Options has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question option',
     *          'is_active': 'false',
     *          'is_true': 'false',
     *          'question_id': '3',
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
     *       @OA\Property(property="message", type="string", example="Question Options Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $question_options = LmsQuestionOption::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($question_options)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Options Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Question Options has been Fetched Successfully!',
            'data' => [
                'question_options' => LmsQuestionOptionResource::collection($question_options)
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
     * path="/api/question-options",
     * summary="Create Question Option",
     * description="Create Question Option",
     * operationId="createQuestionOption",
     * tags={"Question Option"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Question Option data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test questions"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="is_true", type="boolean", format="is_true", example="true/false"),
     *       @OA\Property(property="question_id", type="integer", format="question_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Question Option has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question option',
     *          'is_active': 'false',
     *          'is_true': 'false',
     *          'question_id': '3',
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
     *       @OA\Property(property="message", type="string", example="Question Option Not Found")
     *        )
     *     ),
     * )
     */
    public function store(QuestionOptionRequest $request)
    {
        $question_option = new LmsQuestionOption($request->validated());
        $question_option->name = $request->name;
        $question_option->is_active = $request->has('is_active') ? $request->is_active : 'true';
        $question_option->is_true = $request->has('is_true') ? $request->is_active : 'false';
        $question_option->question_id = $request->question_id;
        $question_id = LmsQuestion::getIdByUuid($request->question_id);
        $question_option->question_id = $question_id;
        $question_option->save();

        if (empty($question_option)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Option Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Question Option has been Created Successfully!',
            'data' => [
                'question_option' => new LmsQuestionOptionResource($question_option)
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
     * path="/api/question-options/{question_options_uuid}",
     * summary="Get Question Option",
     * description="Get Question Option by question_options_uuid",
     * operationId="getQuestionOptionById",
     * tags={"Question Option"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="question_options_uuid of Question Option",
     *    in="path",
     *    name="question_options_uuid",
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
     *       'message': 'Question Option has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question option',
     *          'is_active': 'false',
     *          'is_true': 'false',
     *          'question_id': '3',
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
     *       @OA\Property(property="message", type="string", example="Question Option Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $question_option = LmsQuestionOption::where('lms_question_options_uuid', $id)->first();
        if (empty($question_option)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Option Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Question Option has been Fetched Successfully!',
            'data' => [
                'question_option' => new LmsQuestionOptionResource($question_option)
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
     * path="/api/questions/{question_options_uuid}",
     * summary="Update Question Option",
     * description="Update Question Option",
     * operationId="updateQuestion Option",
     * tags={"Question Option"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Question Option data",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="name", example="test questions"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="is_true", type="boolean", format="is_true", example="true/false"),
     *       @OA\Property(property="question_id", type="integer", format="question_id", example="1"),
     *    ),
     * ),
     * @OA\Parameter(
     *    description="question_options_uuid of Question Option",
     *    in="path",
     *    name="question_options_uuid",
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
     *       'message': 'Question Option has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test question option',
     *          'is_active': 'false',
     *          'is_true': 'false',
     *          'question_id': '3',
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
     *       @OA\Property(property="message", type="string", example="Question Option Not Found")
     *        )
     *     ),
     * )
     */
    public function update(QuestionOptionRequest $request, $id)
    {
        $question_option = LmsQuestionOption::where('lms_question_options_uuid', $id)->first();
        $data = $request->validated();
        if ($request->has('is_active'))
            $data['is_active'] = $request->is_active;
        if ($request->has('is_true'))
            $data['is_true'] = $request->is_true;
        if ($request->has('question_id'))
            $question_id = LmsQuestion::getIdByUuid($request->question_id);
        $data['question_id'] = $question_id;
        $question_option->update($data);

        if (empty($question_option)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Option Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Question Option has been Updated Successfully!',
            'data' => [
                'question_option' => new LmsQuestionOptionResource($question_option)
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
     * path="/api/question-options/{question_options_uuid}",
     * summary="Delete Question Option",
     * description="Delete existing Question Option",
     * operationId="deleteQuestion Option",
     * tags={"Question Option"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="question_options_uuid of Question Option",
     *    in="path",
     *    name="question_options_uuid",
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
     *       'message': 'Question Option has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test quiz',
     *          'is_active': 'false',
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
     *       @OA\Property(property="message", type="string", example="Question Option Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $question_option = LmsQuestionOption::where('lms_question_options_uuid', $id)->first();
        if (empty($question_option)) {
            return $this->respond([
                'status' => false,
                'message' => 'Question Option Not Found',
                'data' =>  []
            ]);
        }
        $question_option->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Question Option has been Deleted Successfully!',
            'data' => [
                'question_option' => new LmsQuestionOptionResource($question_option)
            ],
        ]);
    }
}
