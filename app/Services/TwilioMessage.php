<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioMessage
{

    private $client;
    public function __construct()
    {

        // twilio client intitialization
        $sid = config('general.twilio_account_sid');
        $token = config('general.twilio_token');
        $this->client = new Client($sid, $token);
    }
    public function sendMessage($message, $to, $from)
    {
        $data =  [
            "body" => $message,
            "from" =>  $from,
            "statusCallback" => config('general.web_hook')
        ];


        $result = $this->client->messages->create($to, $data);
    }
}
