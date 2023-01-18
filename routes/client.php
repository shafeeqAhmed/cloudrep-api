<?php

use App\Http\Controllers\Api\BussinesCategoryController;
use App\Http\Controllers\Api\ClientServiceController;
use App\Http\Controllers\Api\CompanyVertialController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;


// Client OnBoarding Api's
    // services Api
    Route::resource('services', ServiceController::class);
    
    // client Api
    Route::resource('client-profile-items', CilentProfileItemController::class);
    Route::get('my-client-profile-items', [CilentProfileItemController::class, 'getClientProfileByUser']);
    Route::post('update-client-profile-item', [CilentProfileItemController::class, 'updateClientProfile']);
    Route::post('store-client-profile-item', [CilentProfileItemController::class, 'storeClientProfile']);
    Route::post('store-client-services', [ClientServiceController::class, 'storeClientService']);
    Route::get('client-services', [ClientServiceController::class, 'getClientServices']);
    Route::post('update-client-services', [ClientServiceController::class, 'updateClientServices']);
    // Business Categories Api
    Route::resource('bussines-categories', BussinesCategoryController::class);
    // Company Verticals APi
    Route::resource('company-verticals', CompanyVertialController::class);
    Route::get('business-verticals/{business_category_id}', [CompanyVertialController::class, 'getBusinessCategoriesVerticals']);
    Route::post('store-client-verticals', [ClientVerticalController::class, 'storeClientVertical']);
    Route::get('client-verticals', [ClientVerticalController::class, 'getClientVerticals']);
    Route::post('update-client-verticals', [ClientVerticalController::class, 'updateClientVerticals']);