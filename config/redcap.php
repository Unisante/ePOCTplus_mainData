<?php

return [

    'identifiers' => [
      'api_url_followup' => env('REDCAP_IDENTIFIERS_API_URL_FOLLOWUP', ''),
      'api_token_followup' => env('REDCAP_IDENTIFIERS_API_TOKEN_FOLLOWUP', ''),

      'api_url_patient' => env('REDCAP_IDENTIFIERS_API_URL_PATIENT', ''),
      'api_token_patient' => env('REDCAP_IDENTIFIERS_API_TOKEN_PATIENT', ''),

      'patient' => [
        'dyn_pat_study_id_patient' => 'dyn_pat_study_id_patient',
        'dyn_pat_first_name' => 'dyn_pat_first_name',
        'dyn_pat_middle_name'=> 'dyn_pat_middle_name',
        'dyn_pat_last_name' => 'dyn_pat_last_name',
        'dyn_pat_dob' => 'dyn_pat_dob',
        'dyn_pat_village' => 'dyn_pat_village',
        'dyn_pat_sex' => 'dyn_pat_sex',
        'dyn_pat_first_name_caregiver' => 'dyn_pat_first_name_caregiver',
        'dyn_pat_last_name_caregiver' => 'dyn_pat_last_name_caregiver',
        'dyn_pat_relationship_child' => 'dyn_pat_relationship_child',
        'dyn_pat_relationship_child_other' => 'dyn_pat_relationship_child_other',
        'dyn_pat_phone_caregiver' => 'dyn_pat_phone_caregiver',
        'dyn_pat_phone_owner'=>'dyn_pat_phone_owner',
        'dyn_pat_phone_caregiver_2' => 'dyn_pat_phone_caregiver_2',
        'dyn_pat_phone_owner2'=>'dyn_pat_phone_owner2'
        ],
      'followup' => [
        'redcap_event_name' => 'consultation_arm_1',
        'dyn_fup_study_id_consultation' => 'dyn_fup_study_id_consultation',
        'dyn_fup_study_id_patient' => 'dyn_fup_study_id_patient',
        'dyn_fup_id_health_facility' => 'dyn_fup_id_health_facility',
        'dyn_fup_date_time_consultation' => 'dyn_fup_date_time_consultation',
        'dyn_fup_group' => 'dyn_fup_group',
        'dyn_pat_sex_caregiver'=>'dyn_pat_sex_caregiver'
      ]
    ],
];
