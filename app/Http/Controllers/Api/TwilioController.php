<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TwilioNumberRequest;
use App\Models\TwillioNumber;
use App\Models\User;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Services\TwilioService;

class TwilioController extends ApiController
{

    // Required for all Twilio access tokens
    private $twilioAccountSid;
    private $twilioAccountAuthToken;
    private $twilioApiKey;
    private $twilioApiSecret;
    private $twilioTwimlAppSid;
    private $identity;


    public function __construct()
    {
        $this->twilioAccountSid = config('general.twilio_account_sid');
        $this->twilioAccountAuthToken = config('general.twilio_auth_token');
        $this->twilioTwimlAppSid = config('general.twilio_twiml_app_sid');

        $this->twilioApiKey = 'SK45e57c57f923e5c3c0903f48b70ba9de';
        $this->twilioApiSecret = 'uqDNnlnDZbWZCKBwlmMdlMIIonhh3X3K';
        // choose a random username for the connecting user
        // $this->identity = 'daffdfwerweds';
    }

    public function getCallAccessToken(Request $request)
    {
        $this->identity = $request->user()->user_uuid;
        $token = new AccessToken(
            $this->twilioAccountSid,
            $this->twilioApiKey,
            $this->twilioApiSecret,
            3600,
            $this->identity
        );

        // Create Voice grant
        $voiceGrant = new VoiceGrant();

        $voiceGrant->setOutgoingApplicationSid($this->twilioTwimlAppSid);
        // Optional: add to allow incoming calls
        $voiceGrant->setIncomingAllow(true);

        // Add grant to token
        $token->addGrant($voiceGrant);

        return $this->respond([
            'status' => true,
            'message' => '',
            'data' => [
                'accessToken' => $token->toJWT()
            ]
        ]);
    }

    public function getTwilioKey($frindlyName)
    {

        $twilio = new Client($this->twilioAccountSid, $this->twilioAccountAuthToken);
        return $twilio->newKeys->create(["friendlyName" => $frindlyName]);
    }
    public function getKeys()
    {
        $twilio = new Client($this->twilioAccountSid, $this->twilioAccountAuthToken);
        $keys = $twilio->keys
            ->read(20);

        foreach ($keys as $record) {
            $twilio->keys($record->sid)
                ->delete();
        }
    }
    public function getAllCalls(Request $request)
    {
        $twilio = new Client($this->twilioAccountSid, $this->twilioAccountAuthToken);
        $calls = $twilio->calls
            ->read(["status" => "busy", "to" => "client:f09ffc43-42b9-4fee-b0fb-bd867687867e"], 20);
        $records = [];
        foreach ($calls as $call) {
            $arr =  [];
            $arr['sid'] = $call->sid;
            $arr['date_created'] = $call->dateCreated;
            $arr['direction'] = $call->direction;
            $arr['start_time'] = $call->startTime;
            $arr['end_time'] = $call->endTime;
            $arr['from'] = $call->from;
            $arr['to'] = $call->to;
            $arr['price'] = $call->price;
            $arr['price_unit'] = $call->priceUnit;
            // $arr['status'] = $call->status;
            $arr['recordings'] = !empty($call->subresourceUris) ? $call->subresourceUris['recordings'] : null;
            $records[] = $arr;
        }

        return response()->json($records);
    }
    public function buyTwilioNumber(TwilioNumberRequest $request)
    {
        $data = $request->validated();

        // return response()->json($data);
        $numbers = (new TwilioService())->getAvailablePhoneNumbers($request->type, $request->country, $request->prefix);
        $result = TwillioNumber::buyAndStoreTwilioNumber($numbers, $data);
        return $this->respond(saveRecordResponseArray(!empty($result), $result));
    }
}
