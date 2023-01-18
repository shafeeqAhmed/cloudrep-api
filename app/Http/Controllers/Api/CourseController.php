<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CourseRequest;
use App\Http\Resources\LmsCourceResource;
use App\Models\CourseLesson;
use App\Models\LmsCategory;
use App\Models\LmsCourse;
use App\Models\LmsCourseCategory;
use App\Models\LmsCourseLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\support\Str;

class CourseController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     * path="/api/courses",
     * summary="Get Courses",
     * description="Get Courses",
     * operationId="getCourses",
     * tags={"Course"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort course by name param",
     *    in="query",
     *    name="name",
     *    example="test courses",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort courses by sortBy param",
     *    in="query",
     *    name="sortBy",
     *    example="asc/desc",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     * @OA\Parameter(
     *    description="sort courses by pagination",
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
     *       'message': 'Courses has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'title': 'test course',
     *          'price': '200',
     *          'thumbnail': 'http://127.0.0.1:8000/uploads/courses/1655323647974907019.jpg',
     *           'category': {
     *                'id': 1,
     *                'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                'name': 'test category',
     *                'description': 'test description',
     *                'is_active': 'false',
     *                'created_at': '2022-06-04T18:32:20.000000Z',
     *                'updated_at': '2022-06-04T18:36:16.000000Z',
     *                'deleted_at': null
     *              },
     *           'lessons': [
     *              {
     *                 'id': 1,
     *                 'uuid': 'ae647e69-f8cf-49ad-8676-a5d0f1c33d5f',
     *                 'name': 'test lesson',
     *                 'description': null,
     *                 'is_active': true,
     *                 'created_at': '2022-06-14T20:07:38.000000Z',
     *                 'updated_at': '2022-06-14T20:07:38.000000Z',
     *                 'deleted_at': null
     *              },
     *              {
     *                  'id': 2,
     *                  'uuid': '7a2aea1a-5f60-4ffa-bccb-0fc938fe0d5c',
     *                  'name': 'ameer lesson',
     *                  'description': 'test description',
     *                  'is_active': true,
     *                  'created_at': '2022-06-14T20:08:51.000000Z',
     *                  'updated_at': '2022-06-14T20:22:34.000000Z',
     *                  'deleted_at': null
     *              }
     *          ],
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
     *       @OA\Property(property="message", type="string", example="Courses Not Found")
     *        )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $courses = LmsCourse::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->orderBy('id', 'DESC')->paginate($request->perPage);
        if (empty($courses)) {
            return $this->respond([
                'status' => false,
                'message' => 'Courses Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Courses has been Fetched  Successfully!',
            'data' => [
                'courses' => LmsCourceResource::collection($courses)
            ],
        ]);
    }

    public function getCourseList(Request $request)
    {
        $courses = LmsCourse::when($request->q, function ($query, $q) {
            return $query->where('title', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->where('deleted_at', null)
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')->paginate($request->perPage);
        foreach ($courses as $course) {
            $course['lesson_count'] = count($course->lessons);
        }
        return $this->respond([
            'status' => true,
            'message' => 'User has been Fetched Successfully!',
            'data' => [
                'courses' => $courses
            ]
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
     * path="/api/courses",
     * summary="Create Course",
     * description="Create Course",
     * operationId="createCourse",
     * tags={"Course"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Course data",
     *    @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title", type="string", format="title", example="test course"),
     *       @OA\Property(property="category_id", type="integer", format="category_id", example="1"),
     *       @OA\Property(property="price", type="integer", format="price", example="200"),
     *       @OA\Property(property="course_image", type="string", format="course_image", example="20220528_214823.jpg"),
     *       @OA\Property(property="lessons", type="integer", format="lessons", example="1,2,3"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Course has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'title': 'test course',
     *          'price': '200',
     *          'thumbnail': 'http://127.0.0.1:8000/uploads/courses/1655323647974907019.jpg',
     *           'category': {
     *                'id': 1,
     *                'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                'name': 'test category',
     *                'description': 'test description',
     *                'is_active': 'false',
     *                'created_at': '2022-06-04T18:32:20.000000Z',
     *                'updated_at': '2022-06-04T18:36:16.000000Z',
     *                'deleted_at': null
     *              },
     *           'lessons': [
     *              {
     *                 'id': 1,
     *                 'uuid': 'ae647e69-f8cf-49ad-8676-a5d0f1c33d5f',
     *                 'name': 'test lesson',
     *                 'description': null,
     *                 'is_active': true,
     *                 'created_at': '2022-06-14T20:07:38.000000Z',
     *                 'updated_at': '2022-06-14T20:07:38.000000Z',
     *                 'deleted_at': null
     *              },
     *              {
     *                  'id': 2,
     *                  'uuid': '7a2aea1a-5f60-4ffa-bccb-0fc938fe0d5c',
     *                  'name': 'ameer lesson',
     *                  'description': 'test description',
     *                  'is_active': true,
     *                  'created_at': '2022-06-14T20:08:51.000000Z',
     *                  'updated_at': '2022-06-14T20:22:34.000000Z',
     *                  'deleted_at': null
     *              }
     *          ],
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
     *       @OA\Property(property="message", type="string", example="Category Not Found")
     *        )
     *     ),
     * )
     */
    public function store(CourseRequest $request)
    {
        $course = new LmsCourse($request->validated());
        $category_id = LmsCategory::getIdByUuid($request->categories);
        $course->price = $request->has('price') ? $request->price : 0;
        if ($request->has('course_image')) {

            $course->course_image = uploadImage('course_image', 'uploads/courses', 300, 300);
        }

        if ($request->has('description')) {
            $course->description = $request->description;
        }

        if ($request->has('tag_line')) {
            $course->tag_line = $request->tag_line;
        }
        $course->is_published = false;
        $course->save();


        // if ($request->has('categories')) {
        //     $categories = explode(',', $request->categories);
        //     foreach ($categories as $category) {
        //         $categories = LmsCategory::getIdByUuid($category);
        //         $course->categories()->attach($categories);
        //     }
        // }

        if ($request->has('categories')) {
            $this->assignCourseCategory($course->id, $category_id);
        }
        // if($request->has('lessons')) {
        //     $course->lessons()->attach($request->lessons);
        // }
        // $this->assignCourseLessons($course->id, $request);
        // $this->assignCourseCategories($course->id, $request);

        // if (empty($course)) {
        //     return $this->respond([
        //         'status' => false,
        //         'message' => 'Course Not Found',
        //         'data' =>  []
        //     ]);
        // }
        return $this->respond([
            'status' => true,
            'message' => 'Course has been Created Successfully!',
            'data' => [
                'course' => new LmsCourceResource($course)
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
     * path="/api/courses/{course_uuid}",
     * summary="Get Courses By course_uuid",
     * description="Get Courses By course_uuid",
     * operationId="getCoursesById",
     * tags={"Course"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="course_uuid of Category",
     *    in="path",
     *    name="course_uuid",
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
     *       'message': 'Course has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'title': 'test course',
     *          'price': '200',
     *          'thumbnail': 'http://127.0.0.1:8000/uploads/courses/1655323647974907019.jpg',
     *           'category': {
     *                'id': 1,
     *                'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                'name': 'test category',
     *                'description': 'test description',
     *                'is_active': 'false',
     *                'created_at': '2022-06-04T18:32:20.000000Z',
     *                'updated_at': '2022-06-04T18:36:16.000000Z',
     *                'deleted_at': null
     *              },
     *           'lessons': [
     *              {
     *                 'id': 1,
     *                 'uuid': 'ae647e69-f8cf-49ad-8676-a5d0f1c33d5f',
     *                 'name': 'test lesson',
     *                 'description': null,
     *                 'is_active': true,
     *                 'created_at': '2022-06-14T20:07:38.000000Z',
     *                 'updated_at': '2022-06-14T20:07:38.000000Z',
     *                 'deleted_at': null
     *              },
     *              {
     *                  'id': 2,
     *                  'uuid': '7a2aea1a-5f60-4ffa-bccb-0fc938fe0d5c',
     *                  'name': 'ameer lesson',
     *                  'description': 'test description',
     *                  'is_active': true,
     *                  'created_at': '2022-06-14T20:08:51.000000Z',
     *                  'updated_at': '2022-06-14T20:22:34.000000Z',
     *                  'deleted_at': null
     *              }
     *          ],
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
     *       @OA\Property(property="message", type="string", example="Course Not Found")
     *        )
     *     ),
     * )
     */
    public function show($uuid)
    {
        $course = LmsCourse::withCount(["lessons", "videos"])
            ->where('lms_course_uuid', $uuid)
            ->first();

        if (empty($course)) {
            return $this->respond([
                'status' => false,
                'message' => 'Cource Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Cource has been Fetched Successfully!',
            'data' => [
                'course' => new LmsCourceResource($course)
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
     * path="/api/courses/{course_uuid}",
     * summary="Update Course",
     * description="Update Course",
     * operationId="updateCourse",
     * tags={"Course"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Course data",
     *    @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title", type="string", format="title", example="test course"),
     *       @OA\Property(property="category_id", type="integer", format="category_id", example="1"),
     *       @OA\Property(property="price", type="integer", format="price", example="200"),
     *       @OA\Property(property="course_image", type="string", format="course_image", example="20220528_214823.jpg"),
     *       @OA\Property(property="lessons", type="integer", format="lessons", example="1,2,3"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Course has been Fetched Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'title': 'test course',
     *          'price': '200',
     *          'thumbnail': 'http://127.0.0.1:8000/uploads/courses/1655323647974907019.jpg',
     *           'category': {
     *                'id': 1,
     *                'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                'name': 'test category',
     *                'description': 'test description',
     *                'is_active': 'false',
     *                'created_at': '2022-06-04T18:32:20.000000Z',
     *                'updated_at': '2022-06-04T18:36:16.000000Z',
     *                'deleted_at': null
     *              },
     *           'lessons': [
     *              {
     *                 'id': 1,
     *                 'uuid': 'ae647e69-f8cf-49ad-8676-a5d0f1c33d5f',
     *                 'name': 'test lesson',
     *                 'description': null,
     *                 'is_active': true,
     *                 'created_at': '2022-06-14T20:07:38.000000Z',
     *                 'updated_at': '2022-06-14T20:07:38.000000Z',
     *                 'deleted_at': null
     *              },
     *              {
     *                  'id': 2,
     *                  'uuid': '7a2aea1a-5f60-4ffa-bccb-0fc938fe0d5c',
     *                  'name': 'ameer lesson',
     *                  'description': 'test description',
     *                  'is_active': true,
     *                  'created_at': '2022-06-14T20:08:51.000000Z',
     *                  'updated_at': '2022-06-14T20:22:34.000000Z',
     *                  'deleted_at': null
     *              }
     *          ],
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
     *       @OA\Property(property="message", type="string", example="Course Not Found")
     *        )
     *     ),
     * )
     */
    public function update(CourseRequest $request, $uuid)
    {

        $category_id = LmsCategory::getIdByUuid($request->categories);
        $course = LmsCourse::where('lms_course_uuid', $uuid)->first();
        $data = $request->validated();

        if ($request->has('price')) {
            $data['price'] = $request->price;
        }


        if ($request->has('categories')) {
            $this->assignCourseCategory($course->id, $category_id);
        }

        if ($request->has('course_image')) {
            removeImage('uploads/courses', $course->course_image);
            $data['course_image'] = uploadImage('course_image', 'uploads/courses', 250, 250);
        }
        // return $data;
        // return response()->json($data);
        $course->update($data);
        // $course->categories()->detach();
        // if ($request->has('categories')) {
        //     $course->categories()->attach($request->categories);
        // }
        // $this->assignCourseLessons($course->id, $request);

        // if($request->has('lessons')) {
        //     $course->lessons()->detach();
        //     $course->lessons()->attach($request->lessons);
        // }

        if (!$course) {
            return $this->respond([
                'status' => false,
                'message' => 'Course Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Course has been Updated Successfully!',
            'data' => [
                'course' => new LmsCourceResource($course)
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
     * path="/api/courses/{course_uuid}",
     * summary="Delete existing Course",
     * description="Delete Course",
     * operationId="deleteCourse",
     * tags={"Course"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Course has been Deleted Successfully!',
     *       'data': {
     *          'id': 1,
     *          'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *          'title': 'test course',
     *          'price': '200',
     *          'thumbnail': 'http://127.0.0.1:8000/uploads/courses/1655323647974907019.jpg',
     *           'category': {
     *                'id': 1,
     *                'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *                'name': 'test category',
     *                'description': 'test description',
     *                'is_active': 'false',
     *                'created_at': '2022-06-04T18:32:20.000000Z',
     *                'updated_at': '2022-06-04T18:36:16.000000Z',
     *                'deleted_at': null
     *              },
     *           'lessons': [
     *              {
     *                 'id': 1,
     *                 'uuid': 'ae647e69-f8cf-49ad-8676-a5d0f1c33d5f',
     *                 'name': 'test lesson',
     *                 'description': null,
     *                 'is_active': true,
     *                 'created_at': '2022-06-14T20:07:38.000000Z',
     *                 'updated_at': '2022-06-14T20:07:38.000000Z',
     *                 'deleted_at': null
     *              },
     *              {
     *                  'id': 2,
     *                  'uuid': '7a2aea1a-5f60-4ffa-bccb-0fc938fe0d5c',
     *                  'name': 'ameer lesson',
     *                  'description': 'test description',
     *                  'is_active': true,
     *                  'created_at': '2022-06-14T20:08:51.000000Z',
     *                  'updated_at': '2022-06-14T20:22:34.000000Z',
     *                  'deleted_at': null
     *              }
     *          ],
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
     *       @OA\Property(property="message", type="string", example="Course Not Found")
     *        )
     *     ),
     * )
     */
    public function destroy($uuid)
    {
        $course = LmsCourse::where('lms_course_uuid', $uuid)->first();
        if (empty($course)) {
            return $this->respond([
                'status' => false,
                'message' => 'Category Not Found',
                'data' =>  []
            ]);
        }
        // $course->categories()->detach();
        $course->delete();
        return $this->respond([
            'status' => true,
            'message' => 'Category has been Deleted Successfully!',
            'data' => [
                'course' => new LmsCourceResource($course)
            ],
        ]);
    }

    public function assignCourseLessons($course_id, $request)
    {
        $course_lesson = new LmsCourseLesson();
        if ($request->has('lessons')) {
            $lessons = explode(',', $request->lessons);
            // dd($lesson);
            $record = [];
            foreach ($lessons as $lesson) {
                $temp = [];
                // $course_lesson->lesson_id = $lesson;
                // $course_lesson->course_id = $course_id;
                $temp['lms_course_lesson_uuid'] = Str::uuid()->toString();
                $temp['lesson_id'] = $lesson;
                $temp['course_id'] = $course_id;
                $temp['created_at'] = now();
                $record[] = $temp;
            }
            LmsCourseLesson::insert($record);
        }
    }

    public function assignCourseCategories($course_id, $request)
    {
        $course_lesson = new LmsCourseCategory();
        if ($request->has('categories')) {
            $categories = explode(',', $request->categories);
            // dd($lesson);
            $record = [];
            foreach ($categories as $category) {
                $temp = [];
                // $course_lesson->lesson_id = $lesson;
                // $course_lesson->course_id = $course_id;
                $temp['lms_course_category_uuid'] = Str::uuid()->toString();
                $temp['category_id'] = $category;
                $temp['course_id'] = $course_id;
                $temp['created_at'] = now();
                dd($temp);
                $record[] = $temp;
            }
            LmsCourseCategory::insert($record);
        }
    }

    public function uploadCourseImage(Request $request, $file_name)
    {
        $destinationPath = 'uploads/courses/';
        if ($request->hasFile($file_name)) {
            $file = $request->file($file_name);
            $name = time() . rand(1, 1000000000) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($destinationPath), $name);
            return $destinationPath . $name;
        } else {
            return NULL;
        }
    }

    public function getCourseCategoryDropdown()
    {
        $categories =  LmsCategory::select('lms_category_uuid as value', 'name')->get();
        return $this->respond([
            'status' => true,
            'message' => 'Category has been Fetched Successfully!',
            'data' => [
                'categories' => $categories
            ],
        ]);
    }

    public function getCourseByCategory(Request $request, $uuid)
    {
        $category_id = LmsCategory::getIdByUuid($uuid);
        $data = LmsCourseCategory::where('lms_course_categories.category_id', $category_id)
            ->join('lms_courses', 'lms_courses.id', '=', 'lms_course_categories.course_id')
            ->get(['lms_courses.lms_course_uuid', 'lms_courses.title']);

        return $this->respond([
            'status' => true,
            'message' => 'Courses has been fetched Successfully!',
            'data' => [
                'courses' => $data
            ],
        ]);
    }

    // get single course
    public function getCourse($uuid)
    {
        // return response()->json($uuid);
        $course = LmsCourse::where('lms_course_uuid', $uuid)->first();
        return $this->respond([
            'status' => true,
            'message' => 'Courses has been fetched Successfully!',
            'data' => [
                'course' => new LmsCourceResource($course)
            ],
        ]);
    }

    public function assignCourseCategory($courseId, $categoryId)
    {
        LmsCourseCategory::updateOrCreate([
            'course_id'   => $courseId,
        ], [
            'course_id' => $courseId,
            'category_id' => $categoryId
        ]);
    }

    public function getCourseListDrafted(Request $request)
    {
        $courses = LmsCourse::when($request->q, function ($query, $q) {
            return $query->where('title', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) use ($request) {
                return $query->orderBy($sortBy, $request->sortDesc ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->where('deleted_at', null)
            ->where('is_published', 0)
            ->orderBy('id', 'DESC')->paginate($request->perPage);
            foreach($courses as $course) {
                $course['lesson_count'] = count($course->lessons);
            }
        return $this->respond([
            'status' => true,
            'message' => 'User has been Fetched Successfully!',
            'data' => [
                'courses' => $courses
            ]
        ]);
    }

    public function coursePublished($uuid) {
        // return response()->json($uuid);
        $course = LmsCourse::where('lms_course_uuid', $uuid)->first();
        // $course
        if($course){
            $course->is_published = true;
            $course->update();
            return $this->respond([
                'status' => true,
                'message' => 'Courses has been fetched Successfully!',
                'data' => [
                    'course' => new LmsCourceResource($course)
                ],
            ]);
        }
    }
}
