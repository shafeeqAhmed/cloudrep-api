<?php

namespace App\Http\Controllers\Api;

use Twilio\TwiML;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Http\Request;

class IncomingCallController extends ApiController
{
    public function respondToUser()
    {
        $response = new Twiml();
        $response->enqueue();
        $params = array();
        $params['action'] = '/call/enqueue';
        $params['numDigits'] = 1;
        $params['timeout'] = 10;
        $params['method'] = "POST";

        $params = $response->gather($params);
        $params->say(
            'For Programmable SMS, press one. For Voice, press any other key.'
        );
        return $this->respond($response, ['Content-Type', 'application/json']);

        // return response($response)->header('Content-Type', 'text/xml');
    }
    public function webhookForContactCenterBaseNumber(Request $request)
    {
        $response = new VoiceResponse();
        $params = array();
        $params['action'] = secure_url('/api/webhook-for-contact-center-ivr');
        $params['numDigits'] = 1;
        $params['timeout'] = 10;
        $params['method'] = "POST";

        $gather = $response->gather($params);

        $gather->say('For Spanish, please press one.', ['language' => 'es']);
        $gather->say('For Enghlish,please press two.', ['language' => 'en']);

        return $response;
    }
    public function webhookForContactCenterIvr(Request $request)
    {
        // dd($request->all(), $request->CallSid);
        $response = new VoiceResponse();
        $digits = $request['Digits'];
        $language = $digits == 1 ? 'es' : 'en';

        switch ($digits) {
            case 1 || 2:
                $response->enqueue(null, [
                    'waitUrl' => 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.classical',
                    'workflowSid' => 'WW456fb07f4fdc4f55779dcb6bd90f9273'
                ])
                    ->task(json_encode([
                        'selected_language' => $language,
                    ]));
                break;
            default:
                $response->say("Sorry, Caller. You can only press 1 for spanish, or 2 for english.");
                break;
        }

        return $response;
    }
    public function webhookForAgent()
    {
        $response = new VoiceResponse();
        return $response;
    }
    public function webhookForOutGoingCall(Request $request)
    {
        $response = new VoiceResponse();
        $dial = $response->dial('', ['callerId' => '+16472944676']);
        $dial->number($request->To);
        return $response;
    }
}
