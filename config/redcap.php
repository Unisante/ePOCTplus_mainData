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
        'redcap_event_name' => 'consultation_arm_1',
        'dyn_fup_study_id_consultation' => 'dyn_fup_study_id_consultation',
        'dyn_fup_study_id_patient' => 'dyn_fup_study_id_patient',
        'dyn_fup_id_health_facility' => 'dyn_fup_id_health_facility',
        'dyn_fup_date_time_consultation' => 'dyn_fup_date_time_consultation',
        'dyn_fup_group' => 'dyn_fup_group',
      ]
    ],
];
