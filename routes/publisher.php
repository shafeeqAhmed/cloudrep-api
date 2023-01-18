<?php

use App\Http\Controllers\Api\CampaignPublisherPayoutSettingController;
use App\Http\Controllers\Api\PublisherProfileItemController;
use Illuminate\Support\Facades\Route;

// Publisher OnBoarding Api's
 Route::resource('publisher-profile-items', PublisherProfileItemController::class);
 Route::get('my-publisher-profile-items', [PublisherProfileItemController::class, 'getPublisherProfileByUser']);
 Route::get('get-publisher-dropdowns-list', [PublisherProfileItemController::class, 'getPublishOptionDropdownList']);
 Route::post('store-publisher-options', [PublisherProfileItemController::class, 'storePublisherOption']);
 Route::get('get-publisher-selected-dropdowns', [PublisherProfileItemController::class, 'getPublisherSelectedDropdowns']);
 Route::post('store-publisher-competators', [PublisherCompetatorController::class, 'storePublisherCompetator']);
 Route::get('get-publisher-competators', [PublisherCompetatorController::class, 'getPublisherCompetator']);

 //Publisher Payout Settings Api's
 Route::post('store-publisher-payout-settings', [CampaignPublisherPayoutSettingController::class, 'storeCampaignPublisherPayoutSetting']);
 Route::get('get-single-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'getSingleCampaignPublisherPayoutSetting']);
 Route::get('get-publisher-payout-settings', [CampaignPublisherPayoutSettingController::class, 'getCampaignPublisherPayoutSetting']);
 Route::get('my-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'getPublisherPayoutByUser']);
 Route::post('update-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'updateCampaignPublisherPayoutSetting']);
 Route::delete('delete-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'deleteCampaignPublisherPayoutSetting']);