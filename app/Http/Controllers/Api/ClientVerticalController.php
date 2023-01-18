<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientProfileItem;
use App\Models\ClientVertical;
use App\Models\CompanyVertical;
use Illuminate\Http\Request;
use Illuminate\support\Str;

class ClientVerticalController extends ApiController
{
    /**
     * @OA\Post(
     * path="/api/client-verticals",
     * summary="Create Client Vertical",
     * description="Create Client Vertical",
     * operationId="createClientVertical",
     * tags={"Client Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Vertical data",
     *    @OA\JsonContent(
     *       @OA\Property(property="user_id", type="integer", format="user_id", example="auth user (1)"),
     *       @OA\Property(property="vertical_id", type="integer", format="vertical_id", example="1,2"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Vertical has been Created Successfully!',
     *       'data': []
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
     *       @OA\Property(property="message", type="string", example="Client Vertical Not Found")
     *        )
     *     ),
     * )
     */

    public function storeClientVertical(Request $request)
    {
        $record = [];
        if ($request->has('verticals')) {
            ClientProfileItem::where('user_id',$request->user()->id)->update(['step'=>3]);
            $verticals = explode(',', $request->verticals);
            foreach ($verticals as $vertical) {
                $vertical_id = CompanyVertical::getIdByUuid($vertical);
                $temp = [];
                $temp['uuid'] = Str::uuid()->toString();
                $temp['user_id'] = $request->user()->id;
                $temp['vertical_id'] = $vertical_id;
                $temp['created_at'] = now();
                $record[] = $temp;
            }
            ClientVertical::insert($record);
        }
        if (empty($record)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Vertical Not Created',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Vertical has been Created Successfully!',
            'data' => []
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-client-verticals",
     * summary="Update Client Verticals of Current User",
     * description="Update Client Verticals",
     * operationId="updateClientVerticals",
     * tags={"Client Vertical"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Verticals data",
     *    @OA\JsonContent(
     *       @OA\Property(property="vertical_id", type="string", format="vertical_id", example="4677b61d-67b5-46b6-9353-b60c02698e85, d4312726-61a4-4ac0-968d-860fd5762061"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Vertical been Updated Successfully!',
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
     *       @OA\Property(property="message", type="string", example="Client Vertical Item Not Found")
     *        )
     *     ),
     * )
     */

    public function updateClientVerticals(Request $request)
    {

        // in activate all client vertical where user_id = {id}
        ClientVertical::where('client_verticals.user_id', $request->user()->id)
            ->update(['deleted_at' => now()]);
            ClientProfileItem::where('user_id',$request->user()->id)->update(['step'=>3]);

        if ($request->has('verticals')) {
            $verticals = explode(',', $request->verticals);
            foreach ($verticals as $uuid) {
                $client_vertical = ClientVertical::withTrashed()->join('company_verticals', 'company_verticals.id', '=', 'client_verticals.vertical_id')
                    ->where('client_verticals.user_id', $request->user()->id)
                    ->where('company_verticals.uuid', $uuid)
                    ->select('client_verticals.*')->first();
                if ($client_vertical) {
                    $client_vertical->restore();
                }
                else {
                    $vertical_id = CompanyVertical::getIdByUuid($uuid);
                    $newVertical = new ClientVertical();
                    $newVertical->uuid = generateUuid();
                    $newVertical->user_id = $request->user()->id;
                    $newVertical->vertical_id = $vertical_id;
                    $newVertical->created_at = now();
                    $newVertical->save();
                }
                // else {
                //     return $this->respond([
                //         'status' => false,
                //         'message' => 'Verticals Not Update'
                //     ]);
                // }
            }
            return $this->respond([
                'status' => true,
                'message' => 'Client Vertical has been updated Successfully!'
            ]);
        }
    }

    /**
     * @OA\Get(
     * path="/api/client-verticals",
     * summary="Get Client Verticals of Current User",
     * description="Get Client Verticals of Current User",
     * operationId="getClientVerticals",
     * tags={"Client Vertical"},
     * security={ {"sanctum": {} }},
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *      [
     *          '4677b61d-67b5-46b6-9353-b60c02698e85',
     *          'd4312726-61a4-4ac0-968d-860fd5762061',
     *          '7f31b0c7-10b4-4e71-a803-040e11a12fd0'
     *      ]
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
     *       @OA\Property(property="message", type="string", example="User Vertical Not Found")
     *        )
     *     ),
     * )
     */

    public function getClientVerticals(Request $request)
    {
        $user_verticals = ClientVertical::join('company_verticals', 'company_verticals.id', '=', 'client_verticals.vertical_id')
            ->where('client_verticals.user_id', $request->user()->id)
            ->where('client_verticals.is_active','=','1')
            ->pluck('company_verticals.uuid');
        if (empty($user_verticals)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Verticals Not Found',
                'data' =>  []
            ]);
        }
        return $user_verticals;
    }
}
