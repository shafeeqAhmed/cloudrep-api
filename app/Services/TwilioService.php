<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    public $clicent;
    public function __construct()
    {
        $sid = config('general.twilio_account_sid');
        $token = config('general.twilio_auth_token');
        // instantiate a new Twilio Rest Client
        $this->client = new Client($sid, $token);
    }
    public function getAvailablePhoneNumbers($type = 'local', $countryCode = 'CA', $prefix = null, $limit = null): array
    {
        $params = [];
        if ($prefix) {
            $params['contain'] = $prefix;
        }
        $params['smsEnabled'] = true;
        $params['voiceEnabled'] = true;
        $params['excludeAllAddressRequired'] = true;

        $numbers = $this->client->availablePhoneNumbers($countryCode)->$type
            ->read($params, $limit);
        $list = [];
        foreach ($numbers as $no) {
            $tmp = [];
            $tmp['phoneNumber'] = $no->phoneNumber;
            $list[] = $tmp;
        }
        return $list;
    }

    public function buyTwilioNumber($number)
    {
        return $this->client->incomingPhoneNumbers
            ->create(
                [
                    // "voiceUrl" => config('general.action_web_hook'),
                    "voiceUrl" => 'https://development-backend.cloudrep.ai/api/our-ivr',
                    "phoneNumber" => $number
                ]
            );
    }
}
