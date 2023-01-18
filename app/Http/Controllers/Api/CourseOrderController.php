<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CourseOrderResource;
use App\Models\Campaign;
use App\Models\CourseOrder;
use App\Models\LmsCourse;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;

class CourseOrderController extends ApiController
{
    /**
     * @OA\Post(
     * path="/api/store-course-order",
     * summary="Create Course Order",
     * description="Create Course Order",
     * operationId="storeCourseOrder",
     * tags={"Course Order"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Course Order data",
     *    @OA\JsonContent(
     *       required={"user_uuid,course_uuid"},
     *       @OA\Property(property="user_uuid",type="string", format="user_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_uuid", type="string", format="course_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="copon_uuid", type="string", format="copon_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_price", type="integer", format="course_price", example="500"),
     *       @OA\Property(property="course_quantity", type="integer", format="course_quantity", example="1"),
     *       @OA\Property(property="price_after_copon", type="integer", format="price_after_copon", example="300"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been ordered course successfully!',
     *       'data': {
     *          'courseOrder': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'user_id': '1',
     *              'course_id': '1',
     *              'campaign_id': '2',
     *              'copon_id': '2',
     *              'course_price': '500',
     *              'course_quantity': '1',
     *              'price_after_copon': '300',
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
     *       @OA\Property(property="message", type="string", example="Course Order Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCourseOrder(Request $request) {
        // $courseOrder = new CourseOrder($request->all());
        $courseId = LmsCourse::getIdByUuid($request->course_uuid);
        $campaignId = Campaign::getIdByUuid($request->campaign_uuid);
        $coponId = PromoCode::getIdByUuid($request->copon_uuid);
        // $courseOrder->user_id = $request->user()->id;
        // $courseOrder->campaign_id = $request->has('campaign_uuid') ?  $campaignId : null;
        // $courseOrder->course_id = $courseId;
        // $courseOrder->copon_id = $request->has('copon_uuid') ?  $coponId : null;;
        // $courseOrder->course_price = $request->course_price;
        // $courseOrder->course_price = $request->has('price_after_copon') ? $request->price_after_copon : null;
        $courseOrder = CourseOrder::updateOrCreate([
            'user_id'   => $request->user()->id,
            'course_id' => $courseId,
        ],[
            'user_id' => $request->user()->id,
            'course_id' => $courseId,
            'campaign_id' => $request->has('campaign_uuid') ?  $campaignId : null,
            'copon_id' => $request->has('copon_uuid') ?  $coponId : null,
            'course_price' => $request->course_price,
            'course_quantity' => 1,
            'price_after_copon' => $request->has('price_after_copon') ? $request->price_after_copon : null,
        ]);
        // $courseOrder->save();
        return $this->respond([
            'status' => true,
            'message' => 'User has been ordered course successfully!',
            'data' => [
                'courseOrder' => new CourseOrderResource($courseOrder),
            ],
        ]);
    }

    // public function getCourseOrder($uuid) {
    //     $courseId = LmsCourse::getIdByUuid($uuid);
    //     $courseOrder = CourseOrder::where('course_id',$courseId)->first();
    //     if(!empty($courseOrder)) {
    //         return $this->respond([
    //             'status' => true,
    //             'message' => 'Course Order has been fetched successfully!',
    //             'data' => [
    //                 'courseOrder' => new CourseOrderResource($courseOrder),
    //             ],
    //         ]);
    //     }
    // }

    /**
     * @OA\Get(
     * path="/api/get-course-order",
     * summary="Get Course Order",
     * description="Get Course Order",
     * operationId="getCourseOrder",
     * tags={"Course Order"},
     * security={ {"sanctum": {} }},
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Pass Course Order data",
     *    @OA\JsonContent(
     *       required={"user_uuid,course_uuid"},
     *       @OA\Property(property="user_uuid",type="string", format="user_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_uuid", type="string", format="course_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Course Order has been fetched successfully against user!',
     *       'data': {
     *          'courseOrder': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'user_id': '1',
     *              'course_id': '1',
     *              'campaign_id': '2',
     *              'copon_id': '2',
     *              'course_price': '500',
     *              'course_quantity': '1',
     *              'price_after_copon': '300',
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
     *       @OA\Property(property="message", type="string", example="Course Order not found")
     *        )
     *     ),
     * )
     */

    public function getCourseOrder(Request $request) {
        $userId = User::getIdByUuid($request->user_uuid);
        $courseId = LmsCourse::getIdByUuid($request->course_uuid);
        $courseOrder = CourseOrder::where('user_id',$userId)->where('course_id',$courseId)->with('copon','course','user')->first();
        if(!empty($courseOrder)) {
            return $this->respond([
                'status' => true,
                'message' => 'Course Order has been fetched successfully against the user!',
                'data' => [
                    'courseOrder' => $courseOrder,
                ],
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/update-course-order",
     * summary="Update Course Order",
     * description="Update Course Order",
     * operationId="updateCourseOrder",
     * tags={"Course Order"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Course Order data",
     *    @OA\JsonContent(
     *       required={"user_uuid,course_uuid"},
     *       @OA\Property(property="user_uuid",type="string", format="user_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="campaign_uuid",type="string", format="campaign_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_uuid", type="string", format="course_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="copon_uuid", type="string", format="copon_uuid", example="7276eed0-1cd6-4b74-95f1-1f1633254d8f"),
     *       @OA\Property(property="course_price", type="integer", format="course_price", example="500"),
     *       @OA\Property(property="course_quantity", type="integer", format="course_quantity", example="1"),
     *       @OA\Property(property="price_after_copon", type="integer", format="price_after_copon", example="300"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'User has been ordered course successfully!',
     *       'data': {
     *          'courseOrder': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'user_id': '1',
     *              'course_id': '1',
     *              'campaign_id': '2',
     *              'copon_id': '2',
     *              'course_price': '500',
     *              'course_quantity': '1',
     *              'price_after_copon': '300',
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
     *       @OA\Property(property="message", type="string", example="Course Order Not Found")
     *        )
     *     ),
     * )
     */

    public function updateCourseOrder(Request $request) {
        $course = LmsCourse::where('lms_course_uuid',$request->course_uuid)->first();
        $copon = PromoCode::where('uuid',$request->copon_uuid)->first();
        PromoCode::where('title',$request->title)->decrement('frequency', 1);

        if(!empty($copon) && $copon->type == 'fixed') {
            $price_after_copon = $course->price - $copon->amount;
        } else if(!empty($copon) && $copon->type == 'percentage') {
            $dicsountPercentage = ($course->price * $copon->amount) / 100 ;
            $price_after_copon = $course->price - $dicsountPercentage;
        }
        if($copon->amount > $course->price) {
            $price_after_copon = 0;
        }

        $courseOrder = CourseOrder::updateOrCreate([
            'user_id'   => $request->user()->id,
            'course_id' => $course->id,
        ],[
            'user_id' => $request->user()->id,
            'course_id' => $course->id,
            'copon_id' => $copon->id,
            'course_price' => $course->price,
            'course_quantity' => 1,
            'price_after_copon' => $price_after_copon,
        ]);
        return $this->respond([
            'status' => true,
            'message' => 'Promo Code has been updated successfully!',
            'data' => [
                    'courseOrder' => new CourseOrderResource($courseOrder),
                ],
        ]);
    }
}
