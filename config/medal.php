<?php

return [
    'authentication' => [
        'hub_callback_url' => env('HUB_CALLBACK_URL','127.0.0.1:5555'),
        'reader_callback_url' => env('READER_CALLBACK_URL','aaa://callback'),
    ],
    'creator' => [
        'algorithms_url' => env('CREATOR_ALGORITHM_URL','https://liwi-test.wavelab.top/api/v1/versions/'),
        'health_facility_url' => env('CREATOR_HEALTH_FACILITY_URL','https://liwi-test.wavelab.top/api/v1/health_facilities/'),
        'patient_url' => env('CREATOR_PATIENT_URL',"https://liwi-test.wavelab.top/api/v1/versions/medal_data_config?version_id="),
        'study_id' => env('STUDY_ID','Dynamic Tanzania'),
        'language' => env('LANGUAGE','en'),
        
    ],
];
