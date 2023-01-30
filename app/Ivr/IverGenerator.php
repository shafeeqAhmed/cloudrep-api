<?php

namespace App\Ivr;

use App\Models\IvrNodesRetries;
use App\Models\Routing;
use Twilio\TwiML\VoiceResponse;
use App\Ivr\Tags\IvrFilter;
use App\Ivr\Tags\TargetFilter;

class IverGenerator
{
    private $response;
    private $record;
    private $gather;
    private $attempt;
    public $dialAttempt;
    private $gatherAttempt;
    private $voice;
    private $language;
    private $containFilter;
    private $numbers;
    public function __construct()
    {
        $this->response = new VoiceResponse;
        $this->attempt = 1;
        $this->dialAttempt = 1;
        $this->gatherAttempt = 1;
        $this->voice = 'woman';
        $this->language = 'en-US';
        $this->containFilter = true;
    }


    public function getTwiml($record)
    {
        $this->record = $record;

        if (method_exists(self::class, $this->record->node_type)) {
            call_user_func_array([$this, $this->record->node_type], [$this->record]);
        }
        return $this->response;
    }
    public function menu()
    {
        for ($i = 1; $i <=  $this->record->no_of_reproduce; $i++) {

            if ($this->record->no_of_reproduce > 1 && $i > 1) {
                $this->response->say('we did not receive any input in spacify time');
                $this->response->pause(['length' => 1]);
                $this->sayOrPlayWithGather($this->record);

                if ($i == $this->record->no_of_reproduce) {

                    $this->fail();
                }
            } else {
                $this->sayOrPlayWithGather($this->record);
            }
        }
    }

    public function hangup()
    {
        $i = 0;
        do {

            if ($this->record->hangup_message) {

                if ($this->record->no_of_reproduce > 0 && $i > 0) {
                    $this->response->pause(['length' => 1]);
                    $this->sayOrPlay($this->record);
                }
            }
            $i++;
        } while ($i <=  $this->record->no_of_reproduce);

        $this->response->hangup();
    }

    public function gather()
    {
        for ($i = 1; $i <=  $this->record->no_of_retries; $i++) {


            if ($i > 1) {
                $this->response->say('we did not receive any input in spacify time');
                $this->response->pause(['length' => 1]);
            }

            $this->getGatherContent();
            if ($i == $this->record->no_of_reproduce) {
                $this->fail();
            }
        }
    }
    private function getGatherContent()
    {
        $this->gather =  $this->response->gather([
            'finishOnKey' => $this->record->gather_finish_on_key,
            'timeout' => $this->record->timeout,
            'numDigits' => $this->record->gather_max_number_of_digits,
            'action' => secure_url("/api/our-ivr-action?uuid=" . $this->record->uuid . "&attempt=" . $this->gatherAttempt),
            'method' => "POST",
        ]);

        if ($this->record->node_content_type == 'say') {
            $this->gather->say(
                $this->record->text,
                [
                    'voice' => $this->record->text_voice,
                    'language' => $this->record->text_language,
                ]
            );
        }
        if ($this->record->node_content_type == 'play') {
            $this->gather->play($this->record->sound);
        }
    }

    public function dial()
    {
        $data = [];

        $data['action'] = secure_url("/api/our-ivr-action?uuid=" . $this->record->uuid . "&attempt=$this->dialAttempt");

        if ($this->record->dial_recording_setting == 'Off') {
            $data['record'] = 'do-not-record';
        } elseif ($this->record->dial_recording_setting == 'On Answer') {
            $data['record'] = 'record-from-answer-dual';
        } elseif ($this->record->dial_recording_setting == 'Entire Call') {
            $data['record'] = 'record-from-ringing-dual';
        }

        $data['timeout'] = $this->record->timeout;
        $data['timeLimit'] = $this->record->timeout;


        if ($this->record->dial_wishper) {
            for ($i = 1; $i <=  $this->record->no_of_reproduce; $i++) {
                $this->sayOrPlay($this->record);
                $this->response->pause(['length' => 1]);
            }
        }

        //add call back url
        $callBack['statusCallbackEvent'] = 'initiated ringing answered completed';
        $callBack['statusCallback'] = secure_url("/api/dial-number-status-call-back");
        $callBack['statusCallbackMethod'] = 'POST';


        // // get routing numbers targets or routing plans
        $this->numbers = Routing::getRoutingForAction($this->record->dial_routing_plan);

        // // get current number count
        // $currentCount = IvrNodesRetries::getCount(request('CallSid'), $this->record->uuid);

        // $number = $numbers[$currentCount]['destination'];

        // dd($this->getN());

        $number = $this->getNumber();

        if (is_null($number)) {
            $this->simpleHanup();
        } else {
            $dial = $this->response->dial('', $data);
            $dial->number($number, $callBack);
        }
    }

    public function getNumber()
    {
        $currentCount = IvrNodesRetries::getCount(request('CallSid'), $this->record->uuid);
        $targetFilter = new TargetFilter();
        $result =  $targetFilter->check($this->numbers[$currentCount]['id']);

        // dd($currentCount);
        if ($result['containFilter']) {
            if ($result['isCorrect']) {
                return $this->numbers[$currentCount]['destination'];
            } else {
                //move to the next number
                IvrNodesRetries::increament(request('CallSid'), 'dial', $this->record->uuid);
                // if filter does not full fill the condition
                $tries = IvrNodesRetries::getCount(request('CallSid'), $this->record->uuid);

                if ($tries >= count($this->numbers)) {
                    return null;
                } else {
                    return  $this->getNumber();
                }
            }
        } else {
            return $this->numbers[$currentCount]['destination'];
        }
    }


    public function play()
    {
        for ($i = 1; $i <=  $this->record->no_of_reproduce; $i++) {
            $this->sayOrPlay($this->record);
            if ($i == $this->record->no_of_reproduce) {
                $this->response->pause(['length' => 1]);
                $this->success();
            }
        }
    }

    public function voicemail()
    {
        if ($this->record->voicemail_message) {
            for ($i = 1; $i <=  $this->record->no_of_reproduce; $i++) {

                if ($this->record->no_of_reproduce > 1 && $i > 1) {
                    // $this->response->say('we did not receive any input in spacify time');
                    $this->response->pause(['length' => 1]);
                    $this->sayOrPlay($this->record);

                    if ($i == $this->record->no_of_reproduce) {

                        $this->response->pause(['length' => 1]);
                    }
                } else {
                    $this->sayOrPlay($this->record);
                }
            }
        }

        if ($this->record->voicemail_email_notification) {
            $data['action'] =   secure_url("/api/send-email-notification-of-voice-mail?uuid=" . $this->record->uuid . "");
        }
        $data['timeout'] = $this->record->timeout;
        $data['maxLength'] = $this->record->voicemail_max_length;
        $data['finishOnKey'] = $this->record->voicemail_finish_on_key;
        $data['playBeep'] = $this->record->voicemail_play_beep;

        return  $this->response->record($data);
    }
    public function goto()
    {
        $gotoRecord = $this->record->goto;

        //get target node record of goto node
        if ($gotoRecord) {
            $callSid = request('CallSid');
            //if call is in process
            //increament the retries count
            IvrNodesRetries::increament($callSid, 'goto', $gotoRecord->uuid);

            $tries = IvrNodesRetries::getCount($callSid, $gotoRecord->uuid);
            // if retries county does not exceed
            if ($tries <= $this->record->goto_count) {
                return   $this->getTwiml($gotoRecord);
            } else {
                // if retries completed then fail case will run
                if ($this->record->fail) {

                    return  $this->getTwiml($this->record->fail);
                }
            }
        }
    }
    public function router()
    {
        //get next route
        $ivrNodeFilter = new IvrFilter($this->record);


        //getNextNode(id)
        $nextNode = $ivrNodeFilter->getNextNode();
        if ($nextNode) {
            $this->getTwiml($nextNode);
        } else {
            $this->directHangupWithMessage('Call did not pass the router node Thanks for calling Good Bye!');
        }
    }
    public function successOrFail()
    {
        $this->response->say('we did not receive any input withing spacify time. Good Bye', [
            'voice' => "man",
            'language' => "en-US"
        ]);
        $this->response->hangup();
    }
    public function success()
    {
        if ($this->record->success) {
            return $this->getTwiml($this->record->success);
        } else {

            $this->response->hangup();
        }
    }
    public function fail()
    {
        if ($this->record['fail']) {
            return $this->getTwiml($this->record['fail']);
        } else {
            $this->response->say('we did not receive any input withing spacify time. Good Bye', [
                'voice' => "man",
                'language' => "en-US"
            ]);
            $this->response->hangup();
        }
    }
    public function generalHanupMessage()
    {
        $this->response->say('we did not receive any input withing spacify time. Good Bye', [
            'voice' => "man",
            'language' => "en-US"
        ]);
        $this->response->hangup();
    }

    public function sayOrPlayWithGather($record)
    {
        $this->gather =  $this->response->gather([
            'timeout' => $record->timeout,
            'numDigits' => 1,
            'action' =>  secure_url("/api/our-ivr-action?uuid=$record->uuid&attempt=$this->attempt"),
            'method' => "POST",
        ]);

        if ($record->node_content_type == 'say') {
            $this->gather->say(
                $record->text,
                [
                    'voice' => $record->text_voice,
                    'language' => $record->text_language,
                ]
            );
        } elseif ($record->node_content_type == 'play') {
            $this->gather->play($record->sound);
        }
    }

    public function generalPaused($length = 1)
    {
        $this->response->pause(['length' => $length]);
    }
    public function warningOnInvalidInput()
    {
        $this->response->say('You have entered an invalid number.', [
            'voice' => $this->voice,
            'language' => $this->language
        ]);
        $this->generalPaused();
    }
    public function hangupOnInvalidInput()
    {
        $this->response->say('You have exceeded the limit of the maximum number of Attempts. Thank You for Calling CloudRep, Goodbye!', [
            'voice' => $this->voice,
            'language' => $this->language
        ]);
        $this->response->hangup();
        return $this->response;
    }
    private function sayOrPlay($record)
    {
        if ($record->node_content_type == 'say') {
            $this->response->say(
                $record->text,
                [
                    'voice' => $record->text_voice,
                    'language' => $record->text_language,
                ]
            );
        }
        if ($record->node_content_type == 'play') {
            $this->response->play($record->sound);
        }
        return $this->response;
    }
    public function hangupAfterRecording()
    {
        $this->response->say(
            'Thank you for Calling us, we will get back to you soon, GoodBye',
            [
                'voice' => 'woman',
                'language' => 'en-US',
            ]
        );
        $this->response->hangup();

        return  $this->response;
    }
    public function simpleHanup()
    {
        $this->response->hangup();
        return $this->response;
    }
    public function increaseAttempt($attempt)
    {
        $this->attempt  = ++$attempt;
    }
    public function increaseDialAttempt($attempt)
    {

        $this->dialAttempt  = ++$attempt;
    }
    public function increaseGatherAttempt($attempt)
    {
        $this->gatherAttempt = ++$attempt;
    }
    public function directHangupWithMessage($message)
    {
        $this->response->say($message, [
            'voice' => "man",
            'language' => "en-US"
        ]);
        $this->response->hangup();
        return $this->response;
    }
}
