<?php

return [
    'uuid' => env('APP_UUID'),
    'authentication' => [
        'hub_callback_url' => env('HUB_CALLBACK_URL','http://127.0.0.1:5555'),
        'reader_callback_url' => env('READER_CALLBACK_URL','aaa://callback'),
        'token_lifetime_days' => env('OAUTH_TOKEN_LIFETIME_DAYS',1),
        'refresh_token_lifetime_days' => env('OAUTH_REFRESH_TOKEN_LIFETIME_DAYS',30),
    ],
    'creator' => [
        'url' => env('CREATOR_URL', 'https://liwi-test.wavelab.top'),
        'algorithms_endpoint' => env('CREATOR_ALGORITHM_ENDPOINT', '/api/v1/algorithms'),
        'health_facility_endpoint' => env('CREATOR_HEALTH_FACILITY_ENDPOINT', '/api/v1/health_facilities'),
        'medal_data_config_endpoint' => env('CREATOR_MEDAL_DATA_CONFIG_ENDPOINT', "/api/v1/versions/medal_data_config?version_id="),
        'versions_endpoint' => env('CREATOR_VERSIONS_ENDPOINT', '/api/v1/versions'),
        'study_id' => env('STUDY_ID', 'Dynamic Tanzania'),
        'language' => env('LANGUAGE', 'en'),
    ],
    'urls' => [
        'creator_algorithm_url' => env('CREATOR_ALGORITHM_URL'),
        'creator_health_facility_url' => env('CREATOR_HEALTH_FACILITY_URL'),
        'creator_patient_url' => env('CREATOR_PATIENT_URL'),
    ],

    'global' => [
        'study_id' => env('STUDY_ID'),
        'language' => env('JSON_LANGUAGE'),
        'local_health_facility_management' => env('LOCAL_HEALTH_FACILITY_MANAGEMENT', true),
    ],

    'storage' => [
        'cases_zip_dir' => env('CASES_ZIP_DIR'),
        'json_extract_dir' => env('JSON_EXTRACT_DIR'),
        'json_success_dir' => env('JSON_SUCCESS_DIR'),
        'json_failure_dir' => env('JSON_FAILURE_DIR'),
        'consent_img_dir' => env('CONSENT_IMG_DIR'),
    ],

    'case_json_properties' => [
        'algorithm' => [
            'keys' => [
                'name' => 'algorithm_name',
                'medal_c_id' => 'algorithm_id'
            ]
        ],
        'activities' => [
          'keys' => [
            'medal_c_id' => 'id',
          ],
          'values' => [
            'step' => 'step',
            'clinician' => 'clinician',
            'mac_address' => 'mac_address'
          ],
        ],
        'version' => [
            'keys' => [
                'name' => 'version_name',
                'medal_c_id' => 'version_id',
            ]
        ],
        'node' => [
            'keys' => [
                'medal_c_id' => 'id',
            ],
            'values' => [
                'label' => [
                    'key' => 'label',
                    'modifiers' => ['language'],
                ],
                'type' => 'type',
                'category' => 'category',
                'priority' => 'is_mandatory',
                'reference' => [
                    'key' => 'reference',
                    'modifiers' => ['optional'],
                    'type' => 'string',
                ],
                'display_format' => [
                  'key' => 'display_format',
                  'modifiers' => ['optional'],
                  'type' => 'string',
                ],
                'stage' => [
                    'key' => 'stage',
                    'modifiers' => ['optional'],
                    'type' => 'string',
                ],
                'description' => [
                    'key' => 'description',
                    'modifiers' => ['language', 'optional'],
                    'type' => 'string',
                ],
                'formula' => [
                    'key' => 'formula',
                    'modifiers' => ['optional'],
                    'type' => 'string',
                ],
                'is_identifiable' => 'is_identifiable',
            ],
        ],
        'answer_type' => [
            'keys' => [
                'value' => 'value_format',
            ]
        ],
        'answer' => [
            'keys' => [
                'medal_c_id' => 'id',
            ],
            'values' => [
                'label' => [
                    'key' => 'label',
                    'modifiers' => ['language'],
                ]
            ]
        ],
        'patient_config' => [],
        'health_facility' => [
            'keys' => [
                'name' => 'name',
            ],
            'values' => [
                'group_id' => 'id',
                'long' => 'longitude',
                'lat' => 'latitude',
                'hf_mode' => 'architecture',
            ]
        ],
        'patient' => [
            'keys' => [
                'local_patient_id' => 'uid',
            ],
            'values' => [
                'first_name' => 'first_name',
                'last_name' => 'last_name',
                'birthdate' => [
                    'key' => 'birth_date',
                    'modifiers' => ['datetime-epoch'],
                ],
                'group_id' => 'group_id',
                'other_group_id' => 'other_group_id',
                'other_study_id' => 'other_study_id',
                'other_uid' => 'other_uid',
                //'related_ids' => 'other_uid',
                'created_at' => [
                    'key' => 'createdAt',
                    'modifiers' => ['datetime-epoch'],
                ],
                'updated_at' => [
                    'key' => 'updatedAt',
                    'modifiers' => ['datetime-epoch'],
                ],
            ]
        ],
        'diagnosis' => [
            'keys' => [
                'medal_c_id' => 'id',
                'diagnostic_id' => 'diagnosis_id',
            ],
            'values' => [
                'label' => [
                    'key' => 'label',
                    'modifiers' => ['language'],
                ],
                'type' => 'type',
            ],
        ],
        'drug' => [
            'keys' => [
                'medal_c_id' => 'id',
            ],
            'values' => [
                'type' => [
                    'key' => 'type',
                    'modifiers' => ['optional'],
                    'type' => 'string',
                ],
                'label' => [
                    'key' => 'label',
                    'modifiers' => ['language'],
                ],
                'description' => [
                    'key' => 'description',
                    'modifiers' => ['language', 'optional'],
                    'type' => 'string',
                ],
                'is_antibiotic' => [
                    'key' => 'is_antibiotic',
                    'modifiers' => ['optional'],
                    'type' => 'object',
                ],
                'is_anti_malarial' => [
                    'key' => 'is_anti_malarial',
                    'modifiers' => ['optional'],
                    'type' => 'object',
                ],
            ]
        ],
        'management' => [
            'keys' => [
                'medal_c_id' => 'id',
            ],
            'values' => [
                'type' => [
                    'key' => 'type',
                    'modifiers' => ['optional'],
                    'type' => 'string',
                ],
                'label' => [
                    'key' => 'label',
                    'modifiers' => ['language'],
                ],
                'description' => [
                    'key' => 'description',
                    'modifiers' => ['language', 'optional'],
                    'type' => 'string',
                ],
            ]
        ],
        'formulation' => [
            'keys' => [
                'medal_c_id' => 'id',
            ],
            'values' => [
                'medication_form' => 'medication_form',
                'administration_route_category' => 'administration_route_category',
                'administration_route_name' => 'administration_route_name',
                'liquid_concentration' => 'liquid_concentration',
                'dose_form' => 'dose_form',
                'unique_dose' => 'unique_dose',
                'by_age' => 'by_age',
                'minimal_dose_per_kg' => 'minimal_dose_per_kg',
                'maximal_dose_per_kg' => 'maximal_dose_per_kg',
                'maximal_dose' => 'maximal_dose',
                'doses_per_day' => 'doses_per_day',
                'description' => [
                    'key' => 'description',
                    'modifiers' => ['language', 'optional'],
                    'type' => 'string',
                ],
            ],
        ],
        'medical_case' => [
            'keys' => [
                'local_medical_case_id' => 'id',
            ],
            'values' => [
                //'version_id' => 'version_id',
                //'created_at' => 'created_at',
                //'updated_at' => 'updated_at',
                //'group_id'=>'group_id',
                // 'isEligible' => 'isEligible',
                'consent' => 'consent',
            ]
        ],
        'medical_case_answer' => [],
        'diagnosis_reference' => [],
        'drug_reference' => [
            'values' => [
                'duration' => [
                    'key' => 'duration',
                    'modifiers' => ['optional'],
                ]
            ]
        ],
        'management_reference' => [],
        'custom_diagnosis' => [
            'keys' => [
                'label' => 'name',
            ]
        ],
        'custom_drug' => [
            'keys' => [
                'name' => 'name',
            ],
            'values' => [
                'duration' => 'duration',
            ]
        ]
    ]
];
