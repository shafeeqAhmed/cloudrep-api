<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomerInformationResource;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\ProductOrderResource;
use App\Http\Resources\ShippingAddressResource;
use App\Models\CustomerInformation;
use App\Models\Payment;
use App\Models\ProductOrder;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductOrderController extends ApiController
{

    public function storeCustomerInformation(Request $request) {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|min:10',
            'email' => 'required|string|email|max:255|unique:customer_informations',
            'customer_type' =>'required',Rule::in(['individual', 'business']),
        ]);

        $customerInformation = CustomerInformation::storeCustomerInfo($request);

        return $this->respond([
            'status' => true,
            'message' => 'Customer Information has been stored successfully!',
            'data' => [
                'customerInformation' => new CustomerInformationResource($customerInformation),
            ],
        ]);

    }

    public function getCustomerInfo(Request $request)
    {
        $customerInfo = CustomerInformation::getCustomerInfo($request);
        if (empty($customerInfo)) {
            return $this->respondNotFound('Customer Information not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Customer Information has been fetched successfully!',
            'data' => [
                'customerInformation' => $customerInfo
            ],
        ]);
    }

    public function getCustomerInfoByUuid(Request $request) {
        $customerInfo = CustomerInformation::getCustomerInfoByUuid('uuid',$request->customer_uuid);
        if (empty($customerInfo)) {
            return $this->respondNotFound('Customer Information not found');
        }
        return $this->respond([
            'status' => true,
            'message' => 'Customer Information has been fetched successfully!',
            'data' => [
                'customerInformation' => new CustomerInformationResource($customerInfo)
            ],
        ]);
    }
    
    public function storeProductOrder(Request $request) {
        $request->validate([
            'customer_uuid' => 'required|uuid',
            'brand' => 'string|required',
            'capacity' => 'string|required',
            'color' => 'string|required',
        ]);
    
        $productOrder = ProductOrder::storeProductOrder($request);
    
        return $this->respond([
            'status' => true,
            'message' => 'Product Order has been created successfully!',
            'data' => [
                'productOrder' => new ProductOrderResource($productOrder),
            ],
        ]);
    }

    public function storeShippingAddress(Request $request) {
        $request->validate([
            'customer_uuid' => 'required|uuid',
            'order_uuid' => 'required|uuid',
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'shipping_address' => 'string|required',
        ]);
    
        $shippingAddress = ShippingAddress::storeShippingAddress($request);
    
        return $this->respond([
            'status' => true,
            'message' => 'Shipping Address has been stored successfully!',
            'data' => [
                'shippingAddress' => new ShippingAddressResource($shippingAddress),
            ],
        ]);
    }

    public function storePayment(Request $request) {
        $request->validate([
            'order_uuid' => 'required|uuid',
            'card_number' => 'required',
            'expiry_date' => 'required',
            'cvv' => 'required'
        ]);
    
        $payment = Payment::storePayment($request);
    
        return $this->respond([
            'status' => true,
            'message' => 'Payment has been made successfully!',
            'data' => [
                'payment' => new PaymentResource($payment),
            ],
        ]);
    }
    
}
