<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LessonRequest;
use App\Http\Resources\LmsLessonResource;
use App\Http\Resources\LmsLessonVideoResource;
use App\Models\LmsCourse;
use App\Models\LmsLesson;
use App\Models\LmsLessonVideo;
use Illuminate\Http\Request;

class LessonController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/lessons",
     * summary="Get Lessons",
     * description="Get Lessons",
     * operationId="getLessons",
     * tags={"Lesson"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort lessons by name param",
     *    in="query",
     *    name="name",
     *    example="test lessons",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort lessons by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort lessons by pagination",
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
     *       'message': 'Lessons has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test lesson',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'course_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Lessons Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {

        $lessons = LmsLesson::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($lessons)) {
            return $this->respond([
                'status' => false,
                'message' => 'Lessons Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'lessons has been Fetched  Successfully!',
            'data' => [
                'lessons' => LmsLessonResource::collection($lessons)
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
     * path="/api/lessons",
     * summary="Create Lesson",
     * description="Create Lesson",
     * operationId="createLesson",
     * tags={"Lesson"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Lesson data",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="name", example="test category"),
     *       @OA\Property(property="description", type="string", format="description", example="test description"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="course_id", type="integer", format="course_id", example="1"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Lesson has been Created Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test lesson',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'course_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Lesson Not Found")
     *        )
     *     ),
     * )
     */
    public function store(LessonRequest $request)
    {
        // return response()->json($request->course_id);
        $lesson = new LmsLesson($request->validated());
        $lesson->is_active = $request->has('is_active') ? $request->is_active : 'true';
        $lesson->description = $request->description;
        $lesson->course_id = LmsCourse::getIdByUuid($request->course_uuid);
        // $course_id = LmsCourse::getIdByUuid($request->course_id);
        // $lesson->course_id = $course_id;
        $lesson->save();
        // $this->uploadLessonVideos($request, $lesson->id);


        if (empty($lesson)) {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Lesson has been Created Successfully!',
            'data' => [
                'lesson' => new LmsLessonResource($lesson)
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
     * path="/api/lessons/{lesson_uuid}",
     * summary="Get Lesson",
     * description="Get Lesson by lesson_uuid",
     * operationId="getLessonById",
     * tags={"Lesson"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="lesson_uuid of Lesson",
     *    in="path",
     *    name="lesson_uuid",
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
     *       'message': 'Lesson has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test lesson',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'course_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Lesson Not Found")
     *        )
     *     ),
     * )
     */
    public function show($id)
    {
        $lesson = LmsLesson::where('lms_lesson_uuid', $id)->first();
        if (empty($lesson)) {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Lesson has been Fetched Successfully!',
            'data' => [
                'lesson' => new LmsLessonResource($lesson)
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
     * path="/api/lessons/{lesson_uuid}",
     * summary="Update Lesson",
     * description="Update Lesson",
     * operationId="updateLesson",
     * tags={"Lesson"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Lesson data",
     *    @OA\JsonContent(
     *       required={"name","description"},
     *       @OA\Property(property="name", type="string", format="name", example="test category"),
     *       @OA\Property(property="description", type="string", format="description", example="test description"),
     *       @OA\Property(property="is_active", type="boolean", format="is_active", example="true/false"),
     *       @OA\Property(property="course_id", type="integer", format="course_id", example="1"),
     *     ),
     * ),
     * @OA\Parameter(
     *    description="lesson_uuid of Lesson",
     *    in="path",
     *    name="lesson_uuid",
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
     *       'message': 'Lesson has been Updated Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test lesson',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'course_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Lesson Not Found")
     *        )
     *     ),
     * )
     */
    public function update(LessonRequest $request, $id)
    {
        $lesson = LmsLesson::where('lms_lesson_uuid', $id)->first();
        $data = $request->validated();
        if ($request->has('is_active'))
            $data['is_active'] = $request->is_active;
        if ($request->has('description'))
            $data['description'] = $request->description;
        if ($request->has('course_id'))
            $course_id = LmsCourse::getIdByUuid($request->course_id);
        $data['course_id'] = $course_id;
        $lesson->update($data);
        // $this->uploadLessonVideos($request, $lesson->id);

        if (empty($lesson)) {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Lesson has been Updated Successfully!',
            'data' => [
                'lesson' => new LmsLessonResource($lesson)
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
     * path="/api/lessons/{lesson_uuid}",
     * summary="Delete Lesson",
     * description="Delete existing Lesson",
     * operationId="deleteLesson",
     * tags={"Lesson"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="lesson_uuid of Lesson",
     *    in="path",
     *    name="lesson_uuid",
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
     *       'message': 'Lesson has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test lesson',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'course_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Lesson Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($id)
    {
        $lesson = LmsLesson::where('lms_lesson_uuid', $id)->first();
        if (empty($lesson)) {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Not Found',
                'data' =>  []
            ]);
        }
        $lesson->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Lesson has been Deleted Successfully!',
            'data' => [
                'lesson' => new LmsLessonResource($lesson)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-course-lesson/{course_id}",
     * summary="Get Course Lesson",
     * description="Get Lesson Course by course_id",
     * operationId="getCourseLessonById",
     * tags={"Lesson"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="course_id of Course",
     *    in="path",
     *    name="course_id",
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
     *       'message': 'Lesson has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'name': 'test lesson',
     *          'description': 'test description',
     *          'is_active': 'false',
     *          'course_id': '1',
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
     *       @OA\Property(property="message", type="string", example="Lesson Not Found")
     *        )
     *     ),
     * )
     */

    public function getCourseLesson($uuid)
    {
        $lessons = LmsLesson::where('course_id', LmsCourse::getIdByUuid($uuid))->get();
        if (empty($lessons)) {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Not Found',
                'data' =>  []
            ]);
        }

        $list = [];
        foreach ($lessons as $lesson) {
            $list[] = new LmsLessonResource($lesson);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Lesson has been Fetched Successfully!',
            'data' => [
                'lesson' => $list
            ],
        ]);
    }

    public function uploadLessonVideos(Request $request)
    {
        $this->validate($request, [
            'video' => 'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040',
            'title' => 'required',
            'lesson_uuid' => 'required'
        ]);
        if ($request->hasFile('video')) {

            $video = uploadVideo('video', 'uploads/lessons/videos');
            $url = uploadBase64Image('thumbnail', "uploads/lessons/thumbnails");

            $lesson_video = new LmsLessonVideo();
            $lesson_video->lesson_id = LmsLesson::getIdByUuid($request->lesson_uuid);
            $lesson_video->video_url = $video;
            $lesson_video->video_thumbnail = $url;
            $lesson_video->duration = $request->duration;
            $lesson_video->title = $request->title;
            $lesson_video->description = $request->description;
            $lesson_video->is_free = $request->has('isFree') ? $request->boolean('isFree') : false;
            $lesson_video->save();
            return $this->respond([
                'status' => true,
                'message' => 'Lesson Video has been Uploaded Successfully!',
                'data' => [
                    'lesson_video' => new LmsLessonVideoResource($lesson_video)
                ],
            ]);
        } else {
            return NULL;
        }
    }

    public function getSingleVideo($uuid)
    {
        // return response()->json($uuid);
        $video = LmsLessonVideo::where('lms_lesson_video_uuid', $uuid)->first();
        // return response()->json($video);
        if ($video) {
            return $this->respond([
                'status' => true,
                'message' => 'Lesson Video has been Fetch Successfully!',
                'data' => [
                    'lesson_video' => new LmsLessonVideoResource($video)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Video Not Found!',
            ]);
        }
    }

    public function updateLessonVideos(Request $request, $lesson_video_uuid)
    {
        // $video = uploadVideo('video', 'uploads/lessons/videos');
        // $url = getVideoThumbnail('video',"uploads/lessons/thumbnails");
        // $url = uploadBase64Image('thumbnail',"uploads/lessons/thumbnails");
        // $duration = getVideoDuration('video');

        $lesson_video = new LmsLessonVideo();
        $lesson_video->lesson_id = LmsLesson::getIdByUuid($request->lesson_uuid);
        // $lesson_video->video_url = $video;
        // $lesson_video->video_thumbnail = $url;
        $lesson_video->duration = $request->duration;
        $lesson_video = LmsLessonVideo::where('lms_lesson_video_uuid', $lesson_video_uuid)->first();
        // $lesson_video->lesson_id = $request->lessonId;
        $lesson_video->title = $request->title;
        $lesson_video->description = $request->description;
        if ($request->hasFile('video')) {
            removeImage('uploads/lessons/videos', $lesson_video->video_url);
            $video = uploadVideo('video', 'uploads/lessons/videos');
            $lesson_video->video_url = $video;

            removeImage("uploads/lessons/thumbnails", $lesson_video->video_thumbnail);
            $url = uploadBase64Image('thumbnail', "uploads/lessons/thumbnails");
            $lesson_video->video_thumbnail = $url;
            $lesson_video->duration = $request->duration;
            $lesson_video->is_free = $request->boolean('isFree');
        }

        $lesson_video->update();
        if ($lesson_video) {
            return $this->respond([
                'status' => true,
                'message' => 'Lesson Video has been Updated Successfully!',
                'data' => [
                    'lesson_video' => new LmsLessonVideoResource($lesson_video)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Video Not Found!',
            ]);
        }
    }

    public function deleteLessonVideos($uuid)
    {
        // return response()->json($uuid);

        $record = LmsLessonVideo::where('lms_lesson_video_uuid', $uuid)->first();
        $record->delete();
        // return response()->json($record);
        if ($record) {
            return $this->respond([
                'status' => true,
                'message' => 'Lesson Video has been deleted Successfully!',
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Lesson Video not Found!',
            ]);
        }
    }
}
