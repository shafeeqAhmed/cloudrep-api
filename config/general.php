<?php

return [

    'twilio_account_sid' => env('TWILIO_ACCOUNT_SID'),
    'twilio_auth_token' => env('TWILIO_AUTH_TOKEN'),
    'twilio_twiml_app_sid' => env('TWILIO_TWIML_APP_SID'),
    'web_hook' => env('WEB_HOOK', ''),
    'action_web_hook' => env('ACTION_WEB_ACTION', ''),

];
