<?php

return [

    'identifiers' => [
      'api_url_followup' => env('REDCAP_IDENTIFIERS_API_URL_FOLLOWUP', ''),
      'api_token_followup' => env('REDCAP_IDENTIFIERS_API_TOKEN_FOLLOWUP', ''),

      'api_url_personal_data' => env('REDCAP_IDENTIFIERS_API_URL_PERSONAL_DATA', ''),
      'api_token_personal_data' => env('REDCAP_IDENTIFIERS_API_TOKEN_PERSONAL_DATA', ''),

      'patient' => [
        'id' => 'record_id',
        'lastName' => 'last_name',
        'firstName' => 'first_name',
        ],
      'followup' => [
        'id' => 'record_id',
        'patient_id' => 'patient_id',
        'date_of_consultation' => 'date_of_consultation',
      ]
    ],
];
