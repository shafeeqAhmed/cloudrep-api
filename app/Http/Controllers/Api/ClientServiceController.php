<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientProfileItem;
use App\Models\ClientService;
use App\Models\Service;
use Illuminate\Http\Request;

class ClientServiceController extends APiController
{
    /**
     * @OA\Post(
     * path="/api/store-client-services",
     * summary="Create Client Service",
     * description="Create Client Service",
     * operationId="createClientService",
     * tags={"Client Service"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Service data",
     *    @OA\JsonContent(
     *       @OA\Property(property="service_id", type="string", format="services", example="4677b61d-67b5-46b6-9353-b60c02698e85, d4312726-61a4-4ac0-968d-860fd5762061"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Service has been Created Successfully!',
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
     *       @OA\Property(property="message", type="string", example="Client Service Not Found")
     *        )
     *     ),
     * )
     */
    public function storeClientService(Request $request)
    {

        $clientProfileItem = new ClientProfileItem();
        $clientProfileItem->uuid = generateUuid();
        $clientProfileItem->user_id = $request->user()->id;
        $clientProfileItem->step = 1;
        $clientProfileItem->save();



        if ($request->has('services')) {
            $services = explode(',', $request->services);
            $record = [];
            foreach ($services as $service) {
                $service_id = Service::getIdByUuid($service);
                $temp = [];
                $temp['uuid'] = generateUuid();
                $temp['user_id'] = $request->user()->id;
                $temp['service_id'] = $service_id;
                $temp['is_active'] = true;
                $temp['created_at'] = now();
                $record[] = $temp;
            }
            ClientService::insert($record);
        }
        if (empty($record)) {
            return $this->respond([
                'status' => false,
                'message' => 'Client Service Not Created',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Client Service been Created Successfully!',
            'data' => []
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/update-client-services",
     * summary="Update Client Services of Current User",
     * description="Update Client Services",
     * operationId="updateClientServices",
     * tags={"Client Service"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Client Profile Item data",
     *    @OA\JsonContent(
     *       @OA\Property(property="service_id", type="string", format="services", example="4677b61d-67b5-46b6-9353-b60c02698e85, d4312726-61a4-4ac0-968d-860fd5762061"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Client Service been Created Successfully!',
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
     *       @OA\Property(property="message", type="string", example="Client Profile Item Not Found")
     *        )
     *     ),
     * )
     */

    public function updateClientServices(Request $request)
    {
        ClientService::where('client_services.user_id', $request->user()->id)
            ->update(['deleted_at' => now()]);
        if ($request->has('services')) {
            $services = explode(',', $request->services);
            foreach ($services as $uuid) {
                $client_service = ClientService::withTrashed()->join('services', 'services.id', '=', 'client_services.service_id')
                    ->where('client_services.user_id', $request->user()->id)
                    ->where('services.service_uuid', $uuid)
                    ->select('client_services.*')->first();
                if ($client_service) {
                    $client_service->restore();
                } else {
                    $service_id = Service::getIdByUuid($uuid);
                    $newService = new ClientService();
                    $newService->uuid = generateUuid();
                    $newService->user_id = $request->user()->id;
                    $newService->service_id = $service_id;
                    $newService->created_at = now();
                    $newService->save();
                }
                // else {
                //     return $this->respond([
                //         'status' => false,
                //         'message' => 'Client Service Not Update'
                //     ]);
                // }

            }
            return $this->respond([
                'status' => true,
                'message' => 'Client Service been updated Successfully!'
            ]);
        }
    }

    /**
     * @OA\Get(
     * path="/api/client-services",
     * summary="Get Client Services of Current User",
     * description="Get Client Services of Current User",
     * operationId="getClientServices",
     * tags={"Client Service"},
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
     *       @OA\Property(property="message", type="string", example="User Services Not Found")
     *        )
     *     ),
     * )
     */
    public function getClientServices(Request $request)
    {
        $user_services = ClientService::getUserServices($request->user()->id);
        if (empty($user_services)) {
            return $this->respond([
                'status' => false,
                'message' => 'User Services uuids Not Found',
                'data' =>  []
            ]);
        }
        return $user_services;
    }
}
