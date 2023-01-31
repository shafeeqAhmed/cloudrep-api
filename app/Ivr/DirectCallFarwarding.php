<?php

namespace App\Ivr;

use Twilio\TwiML\VoiceResponse;
use App\Models\Routing;
use App\Models\IvrNodesRetries;
use App\Models\TwillioNumber;
use App\Ivr\Tags\TargetFilter;
use Illuminate\Support\Facades\Log;

class DirectCallFarwarding
{
    private $response;
    private $numbers;
    private $uuid;
    public function __construct()
    {
        $this->response = new VoiceResponse;
    }

    public function directDialing()
    {
        $data = [];
        $this->numbers  = TwillioNumber::getDetail($this->formatNumber());
        $this->uuid = $this->numbers[0]['uuid'];
        $number = $this->getNumber();


        $data['action'] = secure_url("/api/our-ivr-action?To=$number&type=directCall");
        $data['record'] = 'record-from-answer-dual';
        $data['timeout'] = 50;


        $callBack['statusCallbackEvent'] = 'initiated ringing answered completed';
        $callBack['statusCallback'] = secure_url("/api/dial-number-status-call-back");
        $callBack['statusCallbackMethod'] = 'POST';

        // get routing numbers targets or routing plans
        // get current number count
        // $currentCount = IvrNodesRetries::getCount(request('CallSid'), $this->numbers[0]['uuid']);

        // $number = $this->getNumber();
        if (is_null($number)) {
            $this->response->hangup();
        } else {
            $dial = $this->response->dial('', $data);
            $dial->number($number, $callBack);
        }

        return $this->response;

        // $dial = $this->response->dial('', $data);
        // $dial->number($this->formatNumber($this->numbers[$currentCount]['destination']), $callBack);
        // return $this->response;
    }

    public function directAction()
    {

        // get routing numbers targets or routing plans
        $numbers  = TwillioNumber::getDetail($this->formatNumber());

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
            $this->response->hangup();
            return $this->response;
        }
    }
    public function getNumber()
    {
        // dd($this->numbers->pluck('destination', 'id'));
        // get current number count
        $currentCount = IvrNodesRetries::getCount(request('CallSid'), $this->uuid);
        $targetFilter = new TargetFilter();

        $result =  $targetFilter->check($this->numbers[$currentCount]['id']);
        if ($result['containFilter']) {
            if ($result['isCorrect']) {
                return  $this->formatNumber($this->numbers[$currentCount]['destination']);
            } else {
                //move to the next number
                IvrNodesRetries::increament(request('CallSid'), 'dial', $this->uuid);
                // if filter does not full fill the condition
                $tries = IvrNodesRetries::getCount(request('CallSid'), $this->uuid);
                if ($tries >= count($this->numbers)) {
                    Log::alert('this is the hangup call');
                    return null;
                } else {
                    return  $this->getNumber();
                }
            }
        } else {
            return $this->formatNumber($this->numbers[$currentCount]['destination']);
        }
    }
    private function formatNumber($number = null)
    {
        $dialingNumber  = !empty($number) ? $number : request('To');
        if (!str_contains($dialingNumber, '+')) {
            $dialingNumber = '+' . $dialingNumber;
        }
        return $dialingNumber;
    }
}
