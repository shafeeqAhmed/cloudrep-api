<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CourseOrderResource;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\PromoCodeResource;
use App\Models\CourseOrder;
use App\Models\LmsCourse;
use App\Models\OrderDetail;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends ApiController
{
    /**
     * @OA\Post(
     * path="/api/store-copon",
     * summary="Create Copon",
     * description="Create Copon",
     * operationId="storeCopon",
     * tags={"Promo Code"},
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Promo Code data",
     *    @OA\JsonContent(
     *       required={"title,type"},
     *       @OA\Property(property="title",type="string", format="title", example="winter2022"),
     *       @OA\Property(property="type",type="string", format="type", example="fixed/percentage"),
     *       @OA\Property(property="amount", type="integer", format="amount", example="400"),
     *       @OA\Property(property="frequency", type="integer", format="frequency", example="5"),
     *       @OA\Property(property="is_applied", type="boolen", format="is_applied", example="true/false"),
     *       @OA\Property(property="start_date", type="date", format="start_date", example="2022-10-19"),
     *       @OA\Property(property="end_date", type="date", format="end_date", example="2022-10-20"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Promo Code has been created successfully!',
     *       'data': {
     *          'promoCode': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'title': 'winter2022',
     *              'type': 'fixed',
     *              'amount': '500',
     *              'frequency': '5',
     *              'is_applied': 'true',
     *              'start_date': '2022-10-19',
     *              'end_date': '2022-10-20',
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
     *       @OA\Property(property="message", type="string", example="Promo Code Not Found")
     *        )
     *     ),
     * )
     */

    public function storeCopon(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5|max:15|unique:promo_codes',
            'type' => ['required', 'in:fixed,percentage'],
        ]);
        $promoCode = new PromoCode($request->all());
        $promoCode->type = $request->type;
        $promoCode->frequency = $request->has('frequency') ? $request->frequency : 0;
        $promoCode->is_applied = false;
        $promoCode->save();

        return $this->respond([
            'status' => true,
            'message' => 'Promo Code has been created successfully!',
            'data' => [
                'promoCode' => new PromoCodeResource($promoCode),
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-copon",
     * summary="Get Copon",
     * description="Get Copon",
     * operationId="getCopon",
     * tags={"Promo Code"},
     * security={ {"sanctum": {} }},
     *  * @OA\RequestBody(
     *    required=true,
     *    description="Pass Promo Code data",
     *    @OA\JsonContent(
     *       required={"title"},
     *       @OA\Property(property="title",type="title", format="title", example="winter2022"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Promo Code has been fetched successfully!',
     *       'data': {
     *          'promoCode': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'title': 'winter2022',
     *              'type': 'fixed',
     *              'amount': '500',
     *              'frequency': '5',
     *              'is_applied': 'true',
     *              'start_date': '2022-10-19',
     *              'end_date': '2022-10-20',
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
     *       @OA\Property(property="message", type="string", example="Promo Code not found")
     *        )
     *     ),
     * )
     */

    public function getCopon(Request $request)
    {
        if ($request->has('title')) {
            $promoCode = PromoCode::where('title', $request->title)->first();
            if (!empty($promoCode)) {
                return $this->respond([
                    'status' => true,
                    'message' => 'Promo Code has been fetched successfully!',
                    'data' => [
                        'promoCode' => new PromoCodeResource($promoCode),
                    ],
                ]);
            } else {
                return $this->respond([
                    'status' => false,
                    'message' => 'This Promo Code has been applied already',
                    'data' => [],
                ]);
            }
        }
    }

    // public function updateCopon(Request $request) {
    //     $course = LmsCourse::where('lms_course_uuid',$request->course_uuid)->first();
    //     $copon = PromoCode::where('uuid',$request->copon_uuid)->first();
    //     $promoCode = PromoCode::where('title',$request->title)->decrement('frequency', 1);
    //     //     'is_applied' => true,
    //     // ])->decrement('frequency', 1);

    //     $price_after_copen = null;
    //     if(!empty($copon) && $copon->type == 'fixed') {
    //         $price_after_copen = $course->price - $copon->amount;
    //     } else if(!empty($copon) && $copon->type == 'percentage') {
    //         $dicsountPercentage = ($course->price * $copon->amount) / 100 ;
    //         $price_after_copon = $course->price - $dicsountPercentage;
    //     }

    //     $orderDetail = CourseOrder::updateOrCreate([
    //         'user_id'   => $request->user()->id,
    //         'course_id' => $course->id,
    //     ],[
    //         'user_id' => $request->user()->id,
    //         'course_id' => $course->id,
    //         'copon_id' => $copon->id,
    //         'course_price' => $course->price,
    //         'price_after_copon' => $price_after_copon,
    //     ]);
    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Promo Code has been updated successfully!',
    //         'data' => [
    //                 'courseOrder' => new CourseOrderResource($orderDetail),
    //             ],
    //     ]);
    // }


}
