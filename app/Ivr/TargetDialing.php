<?php

namespace App\Ivr;

use Twilio\TwiML\VoiceResponse;

class TargetDialing
{

    private $response;
    private $record;
    private $attempt;
    private $dialAttempt;
    private $gatherAttempt;
    private $voice;
    private $language;
    private $url;
    public function __construct()
    {
        $this->response = new VoiceResponse;
        $this->attempt = 1;
        $this->dialAttempt = 1;
        $this->gatherAttempt = 1;
        $this->voice = 'woman';
        $this->language = 'en-US';
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

        $phoneNumber = getSetting('dial_node_phone_no') ?? '+923077020163';

        $this->response->dial($phoneNumber, $data);
    }
}
