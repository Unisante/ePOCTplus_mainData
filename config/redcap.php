<?php

return [

    'identifiers' => [
      'api_url_personal_data' => env('REDCAP_IDENTIFIERS_API_URL_PERSONAL_DATA', ''),
      'api_token_personal_data' => env('REDCAP_IDENTIFIERS_API_TOKEN_PERSONAL_DATA', ''),

      'api_url_study_data' => env('REDCAP_IDENTIFIERS_API_URL_STUDY_DATA', ''),
      'api_token_study_data' => env('REDCAP_IDENTIFIERS_API_TOKEN_STUDY_DATA', ''),

      'api_url_followup' => env('REDCAP_IDENTIFIERS_API_URL_STUDY_DATA', ''),
      'api_token_followup' => env('REDCAP_IDENTIFIERS_API_TOKEN_STUDY_DATA', ''),

      'patient' => [
        'id' => 'record_id',
        'lastName' => 'last_name',
        'firstName' => 'first_name',
        ],
      'medicalCase' => [
        'id' => 'record_id',
        'patientID' => 'patient_id',
      ],
      'followup' => [
        'id' => 'record_id',
        'patient_id' => 'patient_id',
      ]
    ],
];
