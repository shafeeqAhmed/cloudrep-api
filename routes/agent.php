
<?php 

 //Agent Payout Settings Api's

use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\CampaignAgentPayoutController;
use Illuminate\Support\Facades\Route;

//Agent Onboarding Api's
Route::get('trafic-source', [AgentController::class, 'getAllTraficSource']);
Route::get('my-agent-profile-items', [AgentController::class, 'getAgentProfileByUser']);
Route::get('get-agent-sources', [AgentController::class, 'getAgentSources']);
Route::post('store-agent-sources', [AgentController::class, 'storeAgentTraficSource']);
Route::post('store-agent-location', [AgentController::class, 'storeAgentLocation']);
Route::post('store-agent-device', [AgentController::class, 'storeAgentDevice']);
Route::post('update-agent-sources', [AgentController::class, 'updateAgentSources']);
Route::get('country-list', [AgentController::class, 'getCountryList']);


//Agent Payout setting Api's
 Route::post('store-agent-payout-settings', [CampaignAgentPayoutController::class, 'storeCampaignAgentPayoutSetting']);
 Route::get('get-single-agent-payout-setting', [CampaignAgentPayoutController::class, 'getSingleCampaignAgentPayoutSetting']);
 // Route::get('get-agent-payout-settings', [CampaignAgentPayoutController::class, 'getCampaignAgentPayoutSetting']);
 Route::get('campaign-agent-payout-setting', [CampaignAgentPayoutController::class, 'campaignAgentPayoutSetting']);
 Route::post('update-agent-payout-setting', [CampaignAgentPayoutController::class, 'updateCampaignAgentPayoutSetting']);
 Route::delete('delete-agent-payout-setting', [CampaignAgentPayoutController::class, 'destroyCampaignAgentPayoutSetting']);
 Route::get('get-bonus-types', [CampaignAgentPayoutController::class, 'getBonusType']);
 Route::get('get-agent-payout-on', [CampaignAgentPayoutController::class, 'getAgentPayoutOn']);