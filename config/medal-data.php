<?php

return [
  'urls' => [
    'creator_algorithm_url' => env('CREATOR_ALGORITHM_URL'),
    'creator_health_facility_url' => env('CREATOR_HEALTH_FACILITY_URL'),
    'creator_patient_url' => env('CREATOR_PATIENT_URL'),
  ],

  'global' => [
    'study_id' =>env('STUDY_ID'),
    'language' =>env('JSON_LANGUAGE'),
  ],

  'storage' => [
    'cases_zip_dir' => env('CASES_ZIP_DIR'),
    'json_extract_dir' => env('JSON_EXTRACT_DIR'),
    'json_success_dir' => env('JSON_SUCCESS_DIR'),
    'json_failure_dir' => env('JSON_FAILURE_DIR'),
    'consent_img_dir' => env('CONSENT_IMG_DIR'),
  ]
];
