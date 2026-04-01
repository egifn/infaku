<?php

return [
    'api_version' => env('WHATSAPP_API_VERSION', 'v19.0'),
    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'auto_send' => env('WHATSAPP_AUTO_SEND', false),
    'default_country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '62'),
];
