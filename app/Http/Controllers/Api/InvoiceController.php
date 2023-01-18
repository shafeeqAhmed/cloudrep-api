<?php

namespace App\Http\Controllers\APi;

use App\Http\Resources\CampaignResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\UserResource;
use App\Models\Campaign;
use App\Models\CourseOrder;
use App\Models\DropDown;
use App\Models\Invoice;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends ApiController
{
    /**
     * @OA\Get(
     * path="/api/get-company-info",
     * summary="Get Company Info",
     * description="Get Company Info ny name",
     * operationId="getAgentPayoutByName",
     * tags={"Invoice Creation"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="sort company info by name param",
     *    in="query",
     *    name="name",
     *    example="company name,company address",
     *    @OA\Schema(
     *       type="string"
     *    )
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Company Info has been Fetched Successfully!',
     *       'data': {
     *          'company_info': [
     *             'cloudrepai',
     *             'canada'
     *          ]
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
     *       @OA\Property(property="message", type="string", example="Company Info Not Found")
     *        )
     *     ),
     * )
     */

    public function getCompanyInfo(Request $request)
    {
        // $company_info = SystemSetting::where('name','company address')->first('value');
        if ($request->has('name')) {
            $name = explode(',', $request->name);
            $company_info = getSystemSetting($name);
        }
        if (empty($company_info)) {
            return $this->respondNotFound('Company Info not found');
        }
        // $company_info['name'] = 'Coudrepai';
        return $this->respond([
            'status' => true,
            'message' => 'Company Info has been fetched successfully',
            'date' => [
                'company_info' => $company_info
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/search-users/{role}",
     * summary="Search User By Role",
     * description="Get User By role",
     * operationId="searchUsersByRole",
     * tags={"Invoice Creation"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="role of User",
     *    in="path",
     *    name="role",
     *    required=true,
     *    example="agent/publisher/client",
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
     *       'message': 'User has been Fetched Successfully!',
     *       'data': {
     *          'users': [
     *              {
     *                  'user_uuid': '6e3cd84f-b3e5-4387-87da-0b7654f248d2',
     *                  'name': 'Publisher'
     *              },
     *              {
     *                  'user_uuid': '2b57d931-d73e-41e7-b278-c2272c2513c4',
     *                  'name': 'Agent'
     *              },
     *              {
     *                  'user_uuid': 'e611d3f1-3dbc-4130-96fb-210a7ec20b04',
     *                  'name': 'Tara Michael'
     *              }
     *          ]
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
     *       @OA\Property(property="message", type="string", example="User Not Found")
     *        )
     *     ),
     * )
     */
    public function searchUsers($role, Request $request)
    {

        $user = User::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        })
            ->when($request->q, function ($query, $q) {
                return $query->where('name', 'LIKE', "%{$q}");
            })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get(['user_uuid', 'name']);

        if (empty($user)) {
            return $this->respondNotFound('User not found');
        }
        return $this->respond([
            'date' => [
                'user' => $user
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-user-detail",
     * summary="Get User Detail",
     * description="Get User Detail by user_uuid",
     * operationId="getUserDetailByUuid",
     * tags={"Invoice Creation"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="user_uuid of User",
     *    in="path",
     *    name="user_uuid",
     *    required=true,
     *    example="6e3cd84f-b3e5-4387-87da-0b7654f248d2",
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
     *       'message': 'User data has been Fetched Successfully!',
     *       'data': {
     *          'user_data': {
     *              'uuid': 'fdeac036-8250-4c42-8ec0-1ae84688a713',
     *              'name': 'Admin',
     *              'first_name': 'test',
     *              'last_name': 'admin',
     *              'email': 'admin@gmail.com',
     *              'phone_no': '+VEuq6Gzp2m',
     *              'role': null,
     *              'profile_photo': {},
     *              'created_at': '2022-08-04T16:12:39.000000Z'
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
     *       @OA\Property(property="message", type="string", example="User Data Not Found")
     *        )
     *     ),
     * )
     */

    public function getUserDetail(Request $request)
    {
        $user_data = User::where('user_uuid', $request->user_uuid)->first();
        if (empty($user_data)) {
            return $this->respondNotFound('User not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'User data has been fetched successfully',
            'data' => [
                'user_data' => new UserResource($user_data)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/my-campaign-data",
     * summary="Get Campaign By Current User",
     * description="Get Campaign By Current User",
     * operationId="getCampaignByUserId",
     * tags={"Invoice Creation"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="user_uuid of User",
     *    in="path",
     *    name="user_uuid",
     *    required=true,
     *    example="6e3cd84f-b3e5-4387-87da-0b7654f248d2",
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
     *       'message': 'Campaign has been Fetched Successfully!',
     *       'data': {
     *          'campaign': {
     *              'uuid': '86662f9c-8e04-45bd-b0a5-ff2bcea776c7',
     *              'step': '1',
     *              'user_uuid': null,
     *              'service_uuid': 'ietritetuirweti',
     *              'name': test campaign,
     *              'phone_no': null,
     *              'title': null,
     *              'email': null,
     *              'address': null,
     *              'country': null,
     *              'state': null,
     *              'city': null,
     *              'zipcode': null,
     *              'service_id': '2',
     *              'category_id': null,
     *              'vertical_id': null,
     *              'language': null,
     *              'currency': null,
     *              'start_date': null,
     *              'start_time': null,
     *              'end_date': null,
     *              'end_time': null,
     *              'description': null,
     *              'website_url': null,
     *              'deeplink': null,
     *              'blog_url': null,
     *              'facebook_url': null,
     *              'twitter_url': null,
     *              'linkedin_url': null,
     *              'publisher_per_call_duration': 5,
     *              'payout_per_call': 1.5,
     *              'client_per_call_duration': 5,
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
     *       @OA\Property(property="message", type="string", example="Campaign Not Found")
     *        )
     *     ),
     * )
     */

    public function getCampaignByUser(Request $request)
    {
        $user_id = User::getIdByUuid($request->user_uuid);
        $campain_data = Campaign::where('user_id', $user_id)->first();
        if (empty($campain_data)) {
            return $this->respondNotFound('Campaign Data not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Data has been fetched successfully',
            'data' => [
                'campaign_data' => new CampaignResource($campain_data)
            ],
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/store-invoice",
     * summary="Create Invoice",
     * description="Create Invoice",
     * operationId="createInvoice",
     * tags={"Invoice Creation"},
     * security={ {"sanctum": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass Publisher Payout Setting data",
     *    @OA\JsonContent(
     *       required={"user_uuid"},
     *       @OA\Property(property="invoice_number", type="string", format="invoice_number", example="INV0001"),
     *       @OA\Property(property="date", type="date", format="date", example="2022-08-08"),
     *       @OA\Property(property="terms", type="string", format="terms", example="custom/7 days"),
     *       @OA\Property(property="due_date", type="date", format="due_date", example="2022-08-08"),
     *       @OA\Property(property="description", type="string", format="description", example="test description"),
     *       @OA\Property(property="rate", type="doubleInt", format="rate", example="5.2"),
     *       @OA\Property(property="quantity", type="double", format="quantity", example="5"),
     *       @OA\Property(property="amount", type="double", format="amount", example="5.5"),
     *       @OA\Property(property="tax", type="double", format="tax", example="5.2"),
     *       @OA\Property(property="discount", type="double", format="discount", example="5.3"),
     *       @OA\Property(property="additional_detail", type="string", format="additional_detail", example="test detail"),
     *       @OA\Property(property="note", type="string", format="note", example="test note"),
     *       @OA\Property(property="user_id", type="string", format="user_id", example="8a1b2fd7-0ffc-43e6-8172-aa937583ad74"),
     *       @OA\Property(property="campaign_id", type="string", format="campaign_id", example="8a1b2fd7-0ffc-43e6-8172-aa937583ad74"),
     *    ),
     * ),
     *    @OA\Response(
     *      response=201,
     *       description="
     * {
     *       'status': true,
     *       'message': 'Invoice has been Created Successfully!',
     *       'data': {
     *          'invoice': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'date': 'August 8, 2022',
     *             'terms', 'custom/7 days',
     *             'due_date': August 8, 2022,
     *             'description': test description,
     *             'rate': 5.2,
     *             'quantity': '5',
     *             'amount': '5.5',
     *             'tax': '5.2',
     *             'discount': '5.5',
     *             'addition_detail': 'test detail',
     *             'note': 'test note',
     *             'user_id': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'campaign_id': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Invoice Not Found")
     *        )
     *     ),
     * )
     */

    public function storeInvoice(Request $request)
    {
        $userId = User::getIdByUuid($request->user_uuid);
        $order = CourseOrder::where('uuid', $request->order_uuid)->first();
        $request->validate([
            'user_uuid' => 'required|uuid',
            'order_uuid' => 'required'
        ]);
        $invoice = new Invoice($request->all());
        $invoice = Invoice::updateOrCreate([
            'user_id' => $userId,
            'order_id' => $order->id
        ], [
            'user_id' => $userId,
            'order_id' => $order->id,
            'date' => date('Y-m-d H:i:s', strtotime($request->date)),
            'description' => $request->description,
            'rate' => $request->rate,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'discount' => $request->discount
        ]);
        // $invoice->user_id = $userId;
        // $invoice->order_id = $order->id;
        // $invoice->date = date('Y-m-d H:i:s', strtotime($request->date));
        // $invoice->save();
        return $this->respond([
            'status' => true,
            'message' => 'Invoice has been created successfully',
            'data' => [
                'invoice' => new InvoiceResource($invoice)
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/get-invoice-terms",
     * summary="Get invoice terms by dropdown type",
     * description="Get invoice terms by dropdown typw",
     * operationId="getInvoiceTermsByType",
     * tags={"Invoice Creation"},
     * security={ {"sanctum": {} }},
     * @OA\Parameter(
     *    description="type of Dropdown",
     *    in="path",
     *    name="type",
     *    required=true,
     *    example="custom/7 days",
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
     *       'message': 'Invoice Term has been Fetched Successfully!',
     *       'data': {
     *          'invoice_terms': {
     *             'uuid': '7276eed0-1cd6-4b74-95f1-1f1633254d8f',
     *             'label': 'Custom',
     *             'value', 'custom',
     *             'created_at': '2022-06-04T18:32:20.000000Z',
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
     *       @OA\Property(property="message", type="string", example="Invoice Term Not Found")
     *        )
     *     ),
     * )
     */

    public function  getInvoiceTermOptions(Request $request)
    {
        $invoice_terms = DropDown::where('type', 'invoice terms')->pluck('label', 'value');
        if (empty($invoice_terms)) {
            return $this->respondNotFound('Invoice Terms not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Invoice Terms has been fetched successfully',
            'data' => [
                'invoice_terms' => $invoice_terms
            ],
        ]);
    }

    public function getUserInvoices(Request $request)
    {
        $userId = User::getIdByUuid($request->user_uuid);
        // $orderId = CourseOrder::getIdByUuid($request->order_uuid);
        $userInvoices = Invoice::where('user_id', $userId)->get();
        if (empty($userInvoices)) {
            return $this->respondNotFound('User Invoice not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'User Invoices has been fetched successfully',
            'data' => [
                'invoice' => InvoiceResource::collection($userInvoices)
            ],
        ]);
    }

    public function getInvoice(Request $request)
    {
        $invoice = Invoice::where('uuid', $request->invoice_uuid)->first();
        if (empty($invoice)) {
            return $this->respondNotFound('Invoice not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Invoice has been fetched successfully',
            'data' => [
                'invoice' => new InvoiceResource($invoice)
            ],
        ]);
    }
}
