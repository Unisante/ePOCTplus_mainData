<?php

return [

    'identifiers' => [

      'api_url_followup' => env('REDCAP_IDENTIFIERS_API_URL_FOLLOWUP', ''),
      'api_token_followup' => env('REDCAP_IDENTIFIERS_API_TOKEN_FOLLOWUP', ''),

      'api_url_patient' => env('REDCAP_IDENTIFIERS_API_URL_PATIENT', ''),
      'api_token_patient' => env('REDCAP_IDENTIFIERS_API_TOKEN_PATIENT', ''),

      'api_url_medical_case' => env('REDCAP_IDENTIFIERS_API_URL_MEDICAL_CASE', ''),
      'api_token_medical_case' => env('REDCAP_IDENTIFIERS_API_TOKEN_MEDICAL_CASE', ''),


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
        'dyn_pat_phone_owner2'=>'dyn_pat_phone_owner2',
        'complete'=>'patient_information_complete',
        ],

      'followup' => [
        'redcap_event_name' => 'consultation_arm_1',
        'dyn_fup_study_id_consultation' => 'dyn_fup_study_id_consultation',
        'dyn_fup_study_id_patient' => 'dyn_fup_study_id_patient',
        'dyn_fup_firstname'=>'dyn_fup_firstname',
        'dyn_fup_middlename'=>'dyn_fup_middlename',
        'dyn_fup_lastname'=>'dyn_fup_lastname',
        'dyn_fup_sex'=>'dyn_fup_sex',
        'dyn_fup_first_name_caregiver'=>'dyn_fup_first_name_caregiver',
        'dyn_fup_last_name_caregiver'=>'dyn_fup_last_name_caregiver',
        'dyn_fup_birth_date'=>'dyn_fup_birth_date',
        'dyn_pat_village' => 'dyn_pat_village',
        'dyn_fup_relationship_child'=>'dyn_fup_relationship_child',
        'dyn_fup_phone_caregiver'=>'dyn_fup_phone_caregiver',
        'dyn_fup_phone_owner'=>'dyn_fup_phone_owner',
        'dyn_fup_phone_caregiver_2'=>'dyn_fup_phone_caregiver_2',
        'dyn_fup_phone_owner2'=>'dyn_fup_phone_owner2',
        'dyn_fup_id_health_facility' => 'dyn_fup_id_health_facility',
        'dyn_fup_date_time_consultation' => 'dyn_fup_date_time_consultation',
        'dyn_fup_group' => 'dyn_fup_group',
        'dyn_fup_sex_caregiver'=>'dyn_fup_sex_caregiver',
        'dyn_fup_consultation_id'=>'dyn_fup_consultation_id',
        'identification_complete'=>'identification_information_complete',
        'dyn_fup_followup_status'=>'dyn_fup_followup_status',
        'dyn_fup_landmark_inst' => 'dyn_fup_landmark_inst',
        'dyn_fup_subvillage' => 'dyn_fup_subvillage',
        'dyn_fup_address' => 'dyn_fup_address',

        'dyn_fup_relationship_child_other' => 'dyn_fup_relationship_child_other',
        'dyn_fup_nb_days_since_consult' => 'dyn_fup_nb_days_since_consult',
        'dyn_fup_cured' => 'dyn_fup_cured',
        'dyn_fup_improved' => 'dyn_fup_improved',
        'dyn_fup_improved_specify' => 'dyn_fup_improved_specify',
        'dyn_fup_fever' => 'dyn_fup_fever',
        'dyn_fup_warning' => 'dyn_fup_warning',
        'dyn_fup_other_medics' => 'dyn_fup_other_medics',
        'dyn_fup_other_medics_where' => 'dyn_fup_other_medics_where',
        'dyn_fup_other_medics_where_specify' => 'dyn_fup_other_medics_where_specify',
        'dyn_fup_hosp' => 'dyn_fup_hosp',
        'dyn_fup_hosp_date' => 'dyn_fup_hosp_date',
        'dyn_fup_hosp_bn_nights' => 'dyn_fup_hosp_bn_nights',
        'dyn_fup_list_fup_reason' => 'dyn_fup_list_fup_reason',
        'dyn_fup_followup_type' => 'dyn_fup_followup_type',
        'dyn_fup_followup_bn_attempts' => 'dyn_fup_followup_bn_attempts',
        'dyn_fup_remarks' => 'dyn_fup_remarks',
      ],

      'medical_case' => [
        // baseline
        'dyn_mc_patient_id' => 'dyn_mc_patient_id',
        'dyn_mc_datetime_consultation' => 'dyn_mc_datetime_consultation',

        // variable
        'dyn_mc_medalc_question_id' => 'dyn_mc_medalc_question_id',
        'dyn_mc_medalc_question_label' => 'dyn_mc_medalc_question_label',
        'dyn_mc_medalc_answer_id' => 'dyn_mc_medalc_answer_id',
        'dyn_mc_medalc_answer_value' => 'dyn_mc_medalc_answer_value',


        // Diagnose
        'dyn_mc_medalc_diag_id' => 'dyn_mc_medalc_diag_id',
        'dyn_mc_medal_data_diag_id' => 'dyn_mc_medal_data_diag_id',
        'dyn_mc_medal_data_diag_additional' => 'dyn_mc_medal_data_diag_additional',
        'dyn_mc_medalc_diag_label' => 'dyn_mc_medalc_diag_label',
      ]

    ],
];
