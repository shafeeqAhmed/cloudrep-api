<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Api\AgentController as ApiAgentController;

use App\Http\Controllers\Api\DropdownTypeController;
use App\Http\Controllers\Api\DropdownLabelController;
// use App\Http\Controllers\APi\InvoiceController;
use App\Http\Controllers\Api\CampaignPublisherPayoutSettingController;
use App\Http\Controllers\Api\CourseOrderController as ApiCourseOrderController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\EmailController;
use App\Http\Resources\ProductOrdereResource;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes just for new build
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// Route::group(['middleware' => ['auth:sanctum', 'verified', 'twofa']], function () {
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh-token', [AuthController::class, 'refresh']);
    Route::get('/auth/user', [AuthController::class, 'userDetail']);
    Route::post('login-as', [AuthController::class, 'loginAs']);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('update-basic-info', [AuthController::class, 'updateBasicInfo'])->middleware(['auth:sanctum', 'throttle:6,1']);

Route::post('email-verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware(['auth:sanctum', 'throttle:6,1']);
Route::post('/email/verify/{id}/{hash}', [AuthController::class, 'VerificationEmail'])->middleware(['auth:sanctum', 'throttle:6,1']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware(['auth:sanctum', 'throttle:6,1']);
Route::get('/get-2fa', [AuthController::class, 'getTwoFa'])->middleware(['auth:sanctum', 'throttle:6,1']);
Route::post('/verify-2fa', [AuthController::class, 'verifyTwoFa'])->middleware(['auth:sanctum', 'throttle:6,1']);
Route::post('/update-phone-no', [AuthController::class, 'updatePhoneNumber'])->middleware(['auth:sanctum', 'throttle:6,1']);
Route::post('/twilio_webhook', [AuthController::class, 'twilioWebhook']);


// ============================================  contact center api start =====================================================================


Route::post('/create-task', [TaskController::class, 'createTask']);
Route::post('/delete-task', [TaskController::class, 'deleteTask']);
Route::post('/update-task', [TaskController::class, 'updateTask']);

Route::get('/accept-reservation', [TaskController::class, 'acceptReservation']);
Route::get('/get-reservation', [TaskController::class, 'getReservation']);




// webhooks
Route::post('/workspace-callback-url', [TaskRouterController::class, 'workSpaceCallBackUrl']);
// when task is accept call is intiated
Route::post('/workflow-callback-url', [TaskRouterController::class, 'assigment']);

//response to incoming call
Route::post('/webhook-for-contact-center-base-number', [IncomingCallController::class, 'webhookForContactCenterBaseNumber']);
Route::post('/webhook-for-contact-center-ivr', [IncomingCallController::class, 'webhookForContactCenterIvr']);
Route::post('/webhook-for-agent', [IncomingCallController::class, 'webhookForAgent']);
Route::post('/webhook-for-out-going-call', [IncomingCallController::class, 'webhookForOutGoingCall']);


//dummy route
Route::get('/get-and-delete-all-call', [TwilioController::class, 'getAllCalls']);
Route::get('/get-call-logs', [TwilioController::class, 'getAllCalls']);



// ============================================  contact center api end =====================================================================



// ===================================);
Route::get('user/{user_uuid}', [UserController::class, 'show']);
Route::get('users/{role}', [UserController::class, 'getUsersByRole']);
Route::delete('user/{user_uuid}', [UserController::class, 'destroy']);
Route::middleware(['auth:sanctum'])->put('user/{user_uuid}', [UserController::class, 'update']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('update-number', [UserController::class, 'updateNumber']);
});


// Setting Apis
Route::post('save-setting', [SystemSettingController::class, 'saveSetting']);
Route::get('setting/{name}', [SystemSettingController::class, 'getSetting']);
Route::delete('setting/{setting_uuid}', [SystemSettingController::class, 'destroy']);
// Route::get('all-system-setting-data', [SystemSettingController::class, 'allSettingsData']);
Route::resource('system-settings', SystemSettingController::class);

//Pre Registration
Route::get('fetch-business-scale-type', [PreRegistrationController::class, 'fetchBusinessScaleType']);
Route::get('fetch-cloud-rep-work-type', [PreRegistrationController::class, 'fetchCloudrepWorkType']);
Route::get('get-selected-record', [PreRegistrationController::class, 'FetchSelectedRecord']);
Route::get('get-categories', [BussinesCategoryController::class, 'index']);
Route::get('/get-2fa-pre-reg', [PreRegistrationController::class, 'getTwoFa']);
Route::post('/verify-2fa-pre-reg', [PreRegistrationController::class, 'verifyTwoFa']);
Route::post('store-preg-step-one', [PreRegistrationController::class, 'storeStepOne']);
Route::post('store-preg-step-two', [PreRegistrationController::class, 'storeStepTwo']);
Route::post('store-preg-step-three', [PreRegistrationController::class, 'storeStepThree']);
Route::post('store-preg-step-four', [PreRegistrationController::class, 'storeStepFour']);
Route::post('store-preg-step-six', [PreRegistrationController::class, 'storeStepSix']);
Route::post('store-preg-step-eight', [PreRegistrationController::class, 'storeStepEight']);
Route::post('store-preg-step-nine', [PreRegistrationController::class, 'storeStepNine']);



Route::group(['middleware' => ['auth:sanctum']], function () {
    //Csv Upload Api's
    Route::post('upload-csv', [UploadCsvController::class, 'uploadCsv']);
    Route::post('remove-csv', [UploadCsvController::class, 'removeCsv']);

    // shafeeque Api
    Route::get('/get-call-access-token', [TwilioController::class, 'getCallAccessToken']);
    Route::get('/get-worker-capability-token', [TaskRouterController::class, 'getWorkerCapability']);
    Route::get('/get-work-space-capability-token', [TaskRouterController::class, 'getWorkSpaceCapability']);
    Route::post('register-by-admin', [AuthController::class, 'registerByAdmin']);

    // Campaign Api
    Route::resource('campaign_enrollment', CampaignEnrollmentController::class);



    // Categories Api
    Route::resource('categories', CategoryController::class);
    Route::get('parent-categories/{parent_id}', [CategoryController::class, 'getCategoryByParent']);
    Route::get('categories-list', [CategoryController::class, 'getCategoryList']);

    //Courses Api
    Route::resource('courses', CourseController::class);
    Route::post('course-published/{course_uuid}', [CourseController::class, 'coursePublished']);
    Route::get('get-course/{course_uuid}', [CourseController::class, 'getCourse']);
    Route::get('get-course-list', [CourseController::class, 'getCourseList']);
    Route::get('get-course-list-drafted', [CourseController::class, 'getCourseListDrafted']);

    // Lessons Api
    Route::resource('lessons', LessonController::class);
    Route::get('get-course-lesson/{course_uuid}', [LessonController::class, 'getCourseLesson']);
    Route::post('upload-lesson-video', [LessonController::class, 'uploadLessonVideos']);
    Route::get('get-lesson-video/{lesson_video_uuid}', [LessonController::class, 'getSingleVideo']);
    Route::post('update-lesson-video/{lesson_video_uuid}', [LessonController::class, 'updateLessonVideos']);
    Route::post('delete-lesson-video/{lesson_video_uuid}', [LessonController::class, 'deleteLessonVideos']);

    // Quiz APi
    Route::resource('quizes', QuizController::class);
    // Route::get('get-test',[QuizController::class, 'getTest']);
    Route::get('get-quiz/{uuid}', [QuizController::class, 'getQuiz']);
    Route::post('post-test', [QuizController::class, 'postTest']);
    Route::post('re-take-quiz/{uuid}', [QuizController::class, 'reTakeQuiz']);
    Route::get('get-quiz-result', [QuizController::class, 'getQuizResult']);

    // Questions APi
    Route::resource('questions', QuestionController::class);

    // Question Options Api
    Route::resource('question-options', QuestionOptionController::class);

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


    // Publisher OnBoarding Api's
    Route::resource('publisher-profile-items', PublisherProfileItemController::class);
    Route::get('my-publisher-profile-items', [PublisherProfileItemController::class, 'getPublisherProfileByUser']);
    Route::get('get-publisher-dropdowns-list', [PublisherProfileItemController::class, 'getPublishOptionDropdownList']);
    Route::post('store-publisher-options', [PublisherProfileItemController::class, 'storePublisherOption']);
    Route::get('get-publisher-selected-dropdowns', [PublisherProfileItemController::class, 'getPublisherSelectedDropdowns']);
    Route::post('store-publisher-competators', [PublisherCompetatorController::class, 'storePublisherCompetator']);
    Route::get('get-publisher-competators', [PublisherCompetatorController::class, 'getPublisherCompetator']);

    // Route::resource('dropdowns', DropDownController::class);
    // Route::get('publisher-options', [PublisherProfileItemController::class, 'getPublisherOption']);
    // Route::post('store-publisher-interests', [PublisherInterestController::class, 'storePublisherInterest']);
    // Route::get('get-publisher-interests', [PublisherInterestController::class, 'getPublisherInterest']);
    // Route::get('get-publisher-selected-dropdowns', [PublisherProfileItemController::class, 'getPublisherSelectedDropdowns']);


    // ============================================  shafeeque ahmad api =========================================================================
    // user account settings
    Route::post('single-image-upload', [FileController::class, 'singleImageUpload']);
    Route::post('remove-single-image', [FileController::class, 'removeSingleImage']);
    Route::post('upload-profile-image', [UserController::class, 'uploadProfileImage']);
    Route::post('remove-profile-image', [UserController::class, 'removeProfileImage']);
    Route::post('/update-general-information', [UserController::class, 'updateGeneralInformation']);
    Route::post('/update-personal-information', [UserController::class, 'personalInformation']);
    Route::post('/update-social-link', [UserController::class, 'updateSocialLink']);
    Route::get('my-detail', [UserController::class, 'myDetail']);

    //Video Uplaoding
    Route::post('video-upload', [FileController::class, 'videoUpload']);
    Route::post('video-remove', [FileController::class, 'removeVideo']);

    //Agent Onboarding
    Route::get('trafic-source', [AgentController::class, 'getAllTraficSource']);
    Route::get('my-agent-profile-items', [AgentController::class, 'getAgentProfileByUser']);
    Route::get('get-agent-sources', [AgentController::class, 'getAgentSources']);
    Route::post('store-agent-sources', [AgentController::class, 'storeAgentTraficSource']);
    Route::post('store-agent-location', [AgentController::class, 'storeAgentLocation']);
    Route::post('store-agent-device', [AgentController::class, 'storeAgentDevice']);
    Route::post('update-agent-sources', [AgentController::class, 'updateAgentSources']);
    Route::get('country-list', [AgentController::class, 'getCountryList']);
    Route::get('state-list', [IvrBuilderController::class, 'getStateList']);

    Route::get('get-campaigns-by-user', [CompaignController::class, 'getCampaignsByUser']);



    // Admin Create Campaign Api's
    // 1. getServices
    // Route::get('get-services', [ServiceController::class, 'index']);

    // Route::get('get-campaign-services', [CompaignController::class, 'getCampaignServices']);
    // Route::post('store-campaign-categories', [CampaignCategoryController::class, 'storeCampaignCategory']);
    Route::get('get-verticals/{business_category_uuid}', [CompanyVertialController::class, 'getBusinessCategoriesVerticals']);
    Route::get('get-all-verticals', [CompanyVertialController::class, 'getVerticals']);
    Route::get('search-user/{role}', [CompaignController::class, 'searchUser']);
    Route::get('get-client-detail/{uuid}', [UserController::class, 'getClientDetail']);
    Route::get('get-campaign/{uuid}', [CompaignController::class, 'getCampaign']);
    Route::post('delete-campaign/{uuid}', [CompaignController::class, 'deleteCampaign']);
    Route::get('convert-time-to-timezone', [CompaignController::class, 'convertTimeToTimeZone']);
    Route::get('get-currencies', [CompaignController::class, 'getCurrencies']);
    // Route::get('get-categories', [BussinesCategoryController::class, 'index']);
    // Get Single Campaign With user Object
    Route::get('get-single-campaign/{uuid}', [CompaignController::class, 'getSingleCampaign']);
    Route::post('assign-ivr-to-campaign', [CompaignController::class, 'assignIvrToCampaign']);
    Route::post('update-address-type-of-campaign', [CompaignController::class, 'updateAddressTypeOfCampaign']);
    Route::post('update-routing-type-of-campaign', [CompaignController::class, 'updateRoutingTypeOfCampaign']);
    Route::get('get-transactions', [TransactionController::class, 'getTransaction']);
    Route::get('get-transaction/{uuid}', [TransactionController::class, 'getSingleTransaction']);
    Route::get('get-wallet-balance', [UserController::class, 'getWalletBalance']);
    Route::post('add-wallet-balance', [UserController::class, 'addWalletBalance']);
    // Route::post('add-transaction', [UserController::class, 'addTransaction']);

    //Campaign Geo Location Api's
    Route::post('store-campaign-location', [CompaignController::class, 'storeCampaignLocation']);
    Route::get('get-campaign-location', [CompaignController::class, 'getCampaignLocationByCampaignId']);
    Route::put('update-campaign-location', [CompaignController::class, 'updateCampaignLocation']);
    Route::delete('delete-campaign-location', [CompaignController::class, 'deleteCampaignLocation']);

    //Campaign Reporting Api's
    Route::post('store-campaign-reporting', [CampaignReportingController::class, 'storeCampaignReporting']);
    Route::get('get-campaign-reporting', [CampaignReportingController::class, 'getCampaignReporting']);
    Route::post('update-campaign-reporting', [CampaignReportingController::class, 'updateCampaignReporting']);
    Route::get('get-timeline', [CampaignReportingController::class, 'getTimeline']);
    Route::get('get-timeline-summary', [CampaignReportingController::class, 'getTimeLineSummary']);
    Route::get('get-performance-summary', [CampaignReportingController::class, 'getPerformanceSumary']);
    Route::get('get-performance-report', [CampaignReportingController::class, 'getPerformanceReport']);
    Route::get('get-top-performers', [CampaignReportingController::class, 'getTopPerformers']);
    Route::get('get-call-count-of-coutries', [CampaignReportingController::class, 'getCallCountOfCountries']);
    Route::get('get-users', [UserController::class, 'getAllUsers']);
    Route::get('get-utc-list', [CampaignReportingController::class, 'getUtcList']);
    Route::get('get-campaign-users', [CampaignReportingController::class, 'GetCampaignUsers']);
    Route::post('store-campaign-filter-report', [CampaignReportingController::class, 'storeCampaignFilterReport']);
    Route::get('get-campaign-filter_reports', [CampaignReportingController::class, 'getCampaignFilterReports']);
    Route::post('update-campaign-filter-report', [CampaignReportingController::class, 'updateCampaignFilterReport']);
    Route::delete('delete-filter-report', [CampaignReportingController::class, 'deleteFilterReport']);
    Route::get('get-user-dashboard-record', [CampaignReportingController::class, 'getUserDashboardRecord']);
    //Twilio Number Api's
    Route::post('store-twilio-number', [TwilioNumberController::class, 'storeTwilioNumber']);
    Route::post('update-twilio-number', [TwilioNumberController::class, 'updateTwilioNumber']);
    Route::get('get-twilio-number', [TwilioNumberController::class, 'getTwilioNumber']);
    Route::get('get-publishers-for-assign-to-campaign', [TwilioNumberController::class, 'getPublishersForAssignToCampaign']);
    Route::get('get-assigned-publisher-numbers/{uuid}', [TwilioNumberController::class, 'getAssignedPublisherNumbers']);
    Route::post('assigned-publisher-to-number', [TwilioNumberController::class, 'assignedPublisherToNumber']);
    Route::post('assigned-publisher-campaign', [TwilioNumberController::class, 'assignCampaignToNumber']);
    Route::post('buy-twilio-number', [TwilioController::class, 'buyTwilioNumber']);

    //Twilio Number Tag Api's
    Route::post('store-twilio-number-tag', [TwilioNumberTagController::class, 'storeTwilioNumberTag']);
    Route::post('update-twilio-number-tag', [TwilioNumberTagController::class, 'updateTwilioNumberTag']);
    Route::get('get-twilio-number-tag', [TwilioNumberTagController::class, 'getTwilioNumberTag']);


    //Target Listing Api's
    Route::post('store-target', [TargetListingController::class, 'storeTarget']);
    Route::post('update-target', [TargetListingController::class, 'updateTarget']);
    Route::get('get-target-listing', [TargetListingController::class, 'getTargets']);
    Route::get('get-target-detail/{uuid}', [TargetListingController::class, 'getTargetDetail']);
    Route::delete('delete-target', [TargetListingController::class, 'deleteTarget']);


    //Routing Api's
    Route::get('routings', [RoutingPlanController::class, 'routings']);
    Route::post('store-routing', [RoutingPlanController::class, 'storeRouting']);
    Route::post('update-routing', [RoutingPlanController::class, 'updateRouting']);
    Route::post('delete-routing', [RoutingPlanController::class, 'deleteRouting']);



    //Routing Plan Api's
    Route::post('store-routing-plan', [RoutingPlanController::class, 'storeRoutingPlan']);
    Route::post('update-routing-plan', [RoutingPlanController::class, 'updateRoutingPlan']);
    Route::get('get-routing-plan', [RoutingPlanController::class, 'getRoutingPlans']);
    Route::get('get-routing-plan/detail/{uuid}', [RoutingPlanController::class, 'getRoutingPlanDetail']);
    Route::delete('delete-routing-plan', [RoutingPlanController::class, 'deleteRoutingPlan']);
    Route::get('get-ivr-dial-routing', [RoutingPlanController::class, 'getIvrDialRouting']);

    //Customer Informtaion Api's
    Route::post('store-customer-information', [ProductOrderController::class, 'storeCustomerInformation']);
    Route::get('get-customer-info', [ProductOrderController::class, 'getCustomerInfo']);
    Route::get('get-single-customer-info', [ProductOrderController::class, 'getCustomerInfoByUuid']);
    Route::post('store-product-order', [ProductOrderController::class, 'storeProductOrder']);
    Route::post('store-shipping-address', [ProductOrderController::class, 'storeShippingAddress']);
    Route::post('store-payment', [ProductOrderController::class, 'storePayment']);


    //step 1 (Campaign Name)
    Route::post('store-campaign-name', [CompaignController::class, 'storeCampaignName']);
    Route::post('update-campaign-name', [CompaignController::class, 'updateCampaignName']);

    //Step 2 (Services)
    Route::post('store-services-against-campaign', [CompaignController::class, 'storeServiceAgainstCampaign']);
    Route::post('update-services-against-campaign', [CompaignController::class, 'updateServiceAgainstCampaign']);

    //Step 3 (Client Information)
    Route::post('store-campaign-client', [CompaignController::class, 'storeCampaignClient']);

    //Step 4 (Client Address)
    Route::post('store-campaign-client-address', [CompaignController::class, 'storeCampaignClientAddress']);

    //Step 5 (Business Category & Verticals)
    Route::post('store-campaign-business-cate-vertical', [CompaignController::class, 'storeCampaignBusinessCateVertical']);

    //Step 6 (Campaign Start & End Date/Time)
    Route::post('store-campaign-start-end-date-time', [CompaignController::class, 'storeCampaignStartEndDateTime']);
    Route::post('delete-end-date-time/{uuid}', [CompaignController::class, 'deleteEndDateTime']);
    Route::post('delete-campaign-date-time/{uuid}', [CompaignController::class, 'deleteCampaignDateTime']);

    //Step 7 (Social & Website Links)
    Route::post('store-campaign-social-website-links', [CompaignController::class, 'storeCampaignSocialWebsiteLinks']);

    //Step 8 (Campaign Rates)
    Route::post('store-campaign-rates', [CompaignController::class, 'storeCampaignRates']);

    //Step 9 (Campaign Images)
    Route::post('store-campaign-images', [CompaignController::class, 'storeCampaignImages']);
    Route::post('remove-campaign-image', [CompaignController::class, 'removeCampaignImage']);


    Route::get('get-campaign-publishers/{uuid}', [CompaignController::class, 'getCampaignsPublishers']);
    Route::post('store-campaign-publish/{uuid}', [CompaignController::class, 'storeCampaignPublish']);
    Route::post('store-campaign-single-zipcode', [CompaignController::class, 'storeCampaignSingleZipcode']);

    Route::post('update-camapgin-settings', [CompaignController::class, 'updateCampaignSettings']);

    Route::get('get-campaigns-completed', [CompaignController::class, 'getCampaignsCompleted']);
    Route::get('get-campaigns-drafted', [CompaignController::class, 'getCampaignsDarfted']);

    Route::get('get-campaign-filter-record', [CompaignController::class, 'getCampaignFilterRecord']);

    //Dropdown
    Route::post('store-dropdown-item', [DropDownController::class, 'storeDropdownItem']);
    Route::post('update-dropdown-item', [DropDownController::class, 'updateDropdownItem']);
    Route::get('delete-dropdown-item', [DropDownController::class, 'deleteDropdownItem']);
    Route::get('get-all-dropdown', [DropDownController::class, 'allDropdownList']);
    Route::get('get-single-dropdown', [DropDownController::class, 'getSingleDropdown']);
    Route::get('get-dropdown-by-type', [DropDownController::class, 'getDropDownByType']);


    Route::get('get-course-category-dropdown', [CourseController::class, 'getCourseCategoryDropdown']);
    Route::get('get-course-by-category/{uuid}', [CourseController::class, 'getCourseByCategory']);


    //Resource Controller
    Route::resource('dropdowns', DropDownController::class);
    Route::resource('types', DropdownTypeController::class);
    Route::resource('labels', DropdownLabelController::class);
    Route::resource('campaign-lms', CampaignLmsController::class);
    Route::get('get-courses-by-campaign', [CampaignLmsController::class, 'getCoursesByCampaign']);
    Route::get('get-all-courses-by-campaign', [CampaignLmsController::class, 'getAllCoursesByCampaign']);

    //Publisher Payout Settings Api's
    Route::post('store-publisher-payout-settings', [CampaignPublisherPayoutSettingController::class, 'storeCampaignPublisherPayoutSetting']);
    Route::get('get-single-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'getSingleCampaignPublisherPayoutSetting']);
    Route::get('get-publisher-payout-settings', [CampaignPublisherPayoutSettingController::class, 'getCampaignPublisherPayoutSetting']);
    Route::get('my-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'getPublisherPayoutByUser']);
    Route::post('update-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'updateCampaignPublisherPayoutSetting']);
    Route::delete('delete-publisher-payout-setting', [CampaignPublisherPayoutSettingController::class, 'deleteCampaignPublisherPayoutSetting']);

    //Agent Payout Settings Api's
    Route::post('store-agent-payout-settings', [CampaignAgentPayoutController::class, 'storeCampaignAgentPayoutSetting']);
    Route::post('update-agent-payout-setting', [CampaignAgentPayoutController::class, 'updateCampaignAgentPayoutSetting']);
    Route::get('get-single-agent-payout-setting', [CampaignAgentPayoutController::class, 'getSingleCampaignAgentPayoutSetting']);
    // Route::get('get-agent-payout-settings', [CampaignAgentPayoutController::class, 'getCampaignAgentPayoutSetting']);

    Route::get('campaign-agent-payout-setting', [CampaignAgentPayoutController::class, 'campaignAgentPayoutSetting']);

    Route::delete('delete-agent-payout-setting', [CampaignAgentPayoutController::class, 'destroyCampaignAgentPayoutSetting']);
    Route::get('get-bonus-types', [CampaignAgentPayoutController::class, 'getBonusType']);
    Route::get('get-agent-payout-on', [CampaignAgentPayoutController::class, 'getAgentPayoutOn']);

    //Invoice Api's
    Route::get('get-company-info', [InvoiceController::class, 'getCompanyInfo']);
    Route::get('search-users/{role}', [InvoiceController::class, 'searchUsers']);
    Route::get('get-user-detail', [InvoiceController::class, 'getUserDetail']);
    Route::get('my-campaign-data', [InvoiceController::class, 'getCampaignByUser']);
    Route::post('store-invoice', [InvoiceController::class, 'storeInvoice']);
    Route::get('get-user-invoices', [InvoiceController::class, 'getUserInvoices']);
    Route::get('get-invoice', [InvoiceController::class, 'getInvoice']);
    Route::get('get-invoice-terms', [InvoiceController::class, 'getInvoiceTermOptions']);


    //Campaign Registration Api's
    Route::post('store-campaign-registration', [CampaignRegistrationController::class, 'storeCampaignRegistration']);
    Route::post('update-campaign-registration', [CampaignRegistrationController::class, 'updateCampaignRegistration']);
    Route::get('get-campaign-registration', [CampaignRegistrationController::class, 'getCampaignRegistration']);
    Route::get('get-single-campaign-registration', [CampaignRegistrationController::class, 'getSingleCampaignRegistration']);
    Route::delete('delete-campaign-registration', [CampaignRegistrationController::class, 'deleteCampaignRegistration']);
    Route::get('get-working-state', [DropDownController::class, 'getDropDownByType']);
    // ============================================  end shafeeque ahmad api ======================================================;===============

    Route::get('get-gamification-points', [GamificationController::class, 'getGamificationPoint']);
    //User Application Setting Routes
    Route::post('store-user-appilcation-setting', [UserApplicationSettingController::class, 'storeUserApplicationSetting']);
    Route::post('update-user-appilcation-setting', [UserApplicationSettingController::class, 'updateUserApplicationSetting']);
    Route::get('get-user-appilcation-setting', [UserApplicationSettingController::class, 'getUserApplicationSetting']);


    Route::post('store-course-order', [ApiCourseOrderController::class, 'StoreCourseOrder']);
    // Route::get('get-course-order/{uuid}', [ApiCourseOrderController::class, 'getCourseOrder']);
    Route::get('get-course-order', [ApiCourseOrderController::class, 'getCourseOrder']);
    // Route::post('update-copon', [PromoCodeController::class, 'updateCopon']);
    Route::post('update-course-order', [ApiCourseOrderController::class, 'updateCourseOrder']);


    // validation for nodes
    Route::post('create-dial-validation', [IvrBuilderController::class, 'createDialValidation']);
    Route::post('create-gather-validation', [IvrBuilderController::class, 'createGatherValidation']);
    Route::post('create-goto-validation', [IvrBuilderController::class, 'createGotoValidation']);
    Route::post('create-hangup-validation', [IvrBuilderController::class, 'createHangupValidation']);
    Route::post('create-menu-validation', [IvrBuilderController::class, 'createMenuValidation']);
    Route::post('create-play-validation', [IvrBuilderController::class, 'createPlayValidation']);
    Route::post('create-voicemail-validation', [IvrBuilderController::class, 'createVoicemailValidation']);
    Route::post('save-ivr-nodes', [IvrBuilderController::class, 'saveIvrNodes']);

    // // IVR API's
    // Route::post('store-ivr', [IvrController::class, 'storeIvr']);
    // Route::get('ivrs', [IvrController::class, 'index']);
    // Route::post('duplicate-ivr', [IvrController::class, 'duplicateIvr']);
    // Route::delete('delete-ivr', [IvrController::class, 'deleteIvr']);
    // Route::get('get-ivr', [IvrController::class, 'getIvr']);



    // Route::post('register-node', [IvrBuilderController::class, 'registerNode']);
    // Route::post('remove-node', [IvrBuilderController::class, 'removeNode']);

    // IVR API's
    Route::post('store-ivr', [IvrController::class, 'storeIvr']);
    Route::get('ivrs', [IvrController::class, 'index']);
    Route::post('duplicate-ivr', [IvrController::class, 'duplicateIvr']);
    Route::delete('delete-ivr', [IvrController::class, 'deleteIvr']);
    Route::get('get-ivr', [IvrController::class, 'getIvr']);
    Route::get('get-ivr-filter-record', [IvrController::class, 'getIvrFilterRecord']);
    Route::get('get-ivr-routing-target-filter-record', [IvrController::class, 'getIvrRoutingTargetFilterRecord']);

    //ivr builder filter considions API's
    Route::get('get-tags', [IvrController::class, 'getTags']);
    Route::get('get-tag-operators', [IvrController::class, 'getTagOperators']);
    Route::post('store-tag-filter-conditions', [IvrController::class, 'storeTagFilterConditions']);

    Route::post('register-node', [IvrBuilderController::class, 'registerNode']);
    Route::post('remove-node', [IvrBuilderController::class, 'removeNode']);
    Route::post('reorder-router-filters', [IvrBuilderController::class, 'reorderRouterFilters']);


    Route::post('upload-app-logo', [FileController::class, 'uploadAppLogo']);
    Route::post('remove-app-logo', [FileController::class, 'removeAppLogo']);
    Route::get('get-app-logo', [FileController::class, 'getAppLogo']);

    //Api Documentation Routes
    Route::post('store-api-list', [ApiDocumentationController::class, 'storeApiList']);
    Route::get('get-api-list', [ApiDocumentationController::class, 'getApiList']);
    Route::post('store-api-endpoint', [ApiDocumentationController::class, 'storeApiEndpoint']);
    Route::post('store-api-parameter', [ApiDocumentationController::class, 'storeApiParameter']);
    Route::post('store-api-response', [ApiDocumentationController::class, 'storeApiResponse']);
    Route::get('send-mail', [EmailController::class, 'sendEmail']);

    Route::post('store-copon', [PromoCodeController::class, 'storeCopon']);
    Route::get('get-copon', [PromoCodeController::class, 'getCopon']);
});





Route::get('Duck.gltf', [ThreeJsController::class, 'duckGltf']);
Route::get('Duck0.bin', [ThreeJsController::class, 'duckBin']);
Route::get('DuckCM.png', [ThreeJsController::class, 'duckPng']);
Route::get('scene.gltf', [ThreeJsController::class, 'sceneGltf']);
Route::get('scene.bin', [ThreeJsController::class, 'sceneBin']);
Route::get('textures/Twall_baseColor.jpeg', [ThreeJsController::class, 'tunnelTextures']);



// php artisan workspace:create host=https://backend.cloudrep.ai/api/twilio_webhook bob_phone=+18735030331 alice_phone=+16725721405


Route::post('file-upload', [FileController::class, 'fileUpload']);
Route::get('csv-file-download', [UploadCsvController::class, 'downloadFile']);
// ============================================  IVR BULDER API ===================================================


Route::post('our-ivr', [IvrBuilderController::class, 'ourIvr']);
Route::post('our-ivr-action', [IvrBuilderController::class, 'ourIvrAction']);
Route::post('dial-number-status-call-back', [IvrBuilderController::class, 'dialNumberStatusCallBack']);

Route::post('send-email-notification-of-voice-mail', [IvrBuilderController::class, 'sendMailNotificationOfVoiceMail']);

Route::post(
    'upload-audio',
    [IvrBuilderController::class, 'audioUpload']
);

// this is the comment
// Route::post('create-dial', [IvrBuilderController::class, 'createDial']);
// Route::post('create-gather', [IvrBuilderController::class, 'createGather']);
// Route::post('create-voicemail', [IvrBuilderController::class, 'createVoicemail']);
// Route::post('create-hours', [IvrBuilderController::class, 'createHours']);
// Route::post('create-pixel', [IvrBuilderController::class, 'createPixel']);
// Route::post('create-hangup', [IvrBuilderController::class, 'createHangup']);
// Route::post('create-play', [IvrBuilderController::class, 'createPlay']);
// Route::post('create-goto', [IvrBuilderController::class, 'createGoto']);
// Route::post('create-menu', [IvrBuilderController::class, 'createMenu']);


// Route::post('create-hours', [IvrBuilderController::class, 'createHours']);
// Route::post('create-pixel', [IvrBuilderController::class, 'createPixel']);




// Edit
// Route::post('update-menu', [IvrBuilderController::class, 'updateMenu']);
// Route::post('update-hangup', [IvrBuilderController::class, 'updateHangup']);
// Route::post('update-dial', [IvrBuilderController::class, 'updateDial']);
// Route::post('update-gather', [IvrBuilderController::class, 'updateGather']);
// Route::post('update-play', [IvrBuilderController::class, 'updatePlay']);
// Route::post('update-voicemail', [IvrBuilderController::class, 'updateVoicemail']);

// Route::post('create-hours', [IvrBuilderController::class, 'createHours']);
// Route::post('create-pixel', [IvrBuilderController::class, 'createPixel']);
// Route::post('create-goto', [IvrBuilderController::class, 'createGoto']);
//just for new build
Route::get('get-ivr-filter-conditions-api', [IvrController::class, 'getIvrFilterConditions']);
