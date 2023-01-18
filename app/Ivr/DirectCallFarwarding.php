<?php

namespace App\Ivr;

use Twilio\TwiML\VoiceResponse;
use App\Models\Routing;
use App\Models\IvrNodesRetries;
use App\Models\TwillioNumber;


class DirectCallFarwarding
{
    private $response;
    private $record;
    public function __construct()
    {
        $this->response = new VoiceResponse;
    }

    public function directDialing()
    {
        $data = [];
        $number = $this->formatNumber();
        $data['action'] = secure_url("/api/our-ivr-action?To=$number&type=directCall");
        $data['record'] = 'record-from-answer-dual';
        $data['timeout'] = 50;
        $data1['statusCallbackEvent'] = 'initiated ringing answered completed';
        $data1['statusCallback'] = secure_url("/api/dial-number-status-call-back");
        $data1['statusCallbackMethod'] = 'POST';

        // get routing numbers targets or routing plans
        $numbers  = TwillioNumber::getDidDetail($this->formatNumber());
        // get current number count
        $currentCount = IvrNodesRetries::getCount(request('CallSid'), $numbers[0]['uuid']);

        $dial = $this->response->dial('', $data);
        $dial->number($this->formatNumber($numbers[$currentCount]['destination']), $data1);
        return $this->response;
    }
    private function formatNumber($number = null)
    {
        $dialingNumber  = !empty($number) ? $number : request('To');
        if (!str_contains($dialingNumber, '+')) {
            $dialingNumber = '+' . $dialingNumber;
        }
        return $dialingNumber;
    }
    public function directAction()
    {

        // get routing numbers targets or routing plans
        $numbers  = TwillioNumber::getDidDetail($this->formatNumber());

        //move to the next number
        IvrNodesRetries::increament(request('CallSid'), 'dial', $numbers[0]['uuid']);
        // get total numbers of retries
        $tries = IvrNodesRetries::getCount(request('CallSid'), $numbers[0]['uuid']);


        if ($tries < count($numbers)) {

            if (request('DialCallStatus') == 'completed') {
                return $this->response->hangup();
            }
            return $this->directDialing();
        } else {
            return $this->response->hangup();
        }
    }
}
