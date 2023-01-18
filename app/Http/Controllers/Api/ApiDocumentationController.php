<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ApiEndpointResource;
use App\Http\Resources\ApiListResource;
use App\Http\Resources\ApiParameterResource;
use App\Http\Resources\ApiResponseResource;
use App\Models\ApiEndpoint;
use App\Models\ApiList;
use App\Models\ApiParameter;
use App\Models\ApiResponse;
use Illuminate\Http\Request;

class ApiDocumentationController extends APiController
{
    public function storeApiList(Request $request) {
        $apiList = new ApiList($request->all());
        $apiList->name = ucfirst($request->name);
        $apiList->save();
        return $this->respond([
            'status' => true,
            'message' => 'Api List has been created successfully!',
            'data' => [
                'apiList' => $apiList
            ],
        ]);
    }

    public function getApiList() {
        $apiList = ApiList::all();
        return $this->respond([
            'status' => true,
            'message' => 'Api List has been fetched successfully!',
            'data' => [
                'apiList' => ApiListResource::collection($apiList),
            ],
        ]);
    }

    public function storeAPiEndpoint(Request $request) {
        $apiEndpoint = new ApiEndpoint($request->all());
        $apiListId = ApiList::getIdByUuid($request->api_list_uuid);
        $apiEndpoint->api_list_id = $apiListId;
        $apiEndpoint->type = $request->type;
        $apiEndpoint->url = '/api/' . $request->url;
        $apiEndpoint->title = $request->title;
        $apiEndpoint->description = $request->description;
        $apiEndpoint->save();

        return $this->respond([
            'status' => true,
            'message' => 'Api Endpoint has been created successfully!',
            'data' => [
                'apiEndpoint' => new ApiEndpointResource($apiEndpoint)
            ],
        ]);
    }

    public function storeAPiResponse(Request $request) {
        $apiResponse = new ApiResponse($request->all());
        if($request->has('api_endpoint_uuid')) {
            $apiResponse->api_endpoint_id = ApiEndpoint::getIdByUuid($request->api_endpoint_uuid);
        }
        $apiResponse->save();
        return $this->respond([
            'status' => true,
            'message' => 'Api Response has been created successfully!',
            'data' => [
                'apiResponse' => new ApiResponseResource($apiResponse)
            ],
        ]);

    }

    public function storeApiParameter(Request $request) {
        $apiParam = new ApiParameter($request->all());
        $api_endpoint_id = ApiEndpoint::getIdByUuid($request->api_endpoint_uuid);
        $apiParam->api_endpoint_id = $api_endpoint_id;
        $apiParam->name = $request->name;
        $apiParam->data_type = $request->data_type;
        $apiParam->description = $request->description;
        $apiParam->example_data = $request->example_data;
        $apiParam->save();
        return $this->respond([
            'status' => true,
            'message' => 'Api Param has been created successfully!',
            'data' => [
                'apiParam' => new ApiParameterResource($apiParam)
            ]
        ]);
    }
}
