<?php

return [
    'authentication' => [
        'hub_callback_url' => env('HUB_CALLBACK_URL','127.0.0.1:5555'),
        'reader_callback_url' => env('READER_CALLBACK_URL','aaa://callback'),
    ],
    'creator' => [
        'url' => env('CREATOR_URL','https://liwi-test.wavelab.top'),
        'algorithms_endpoint' => env('CREATOR_ALGORITHM_ENDPOINT','/api/v1/algorithms'),
        'health_facility_endpoint' => env('CREATOR_HEALTH_FACILITY_ENDPOINT','/api/v1/health_facilities'),
        'medal_data_config_endpoint' => env('CREATOR_MEDAL_DATA_CONFIG_ENDPOINT',"/api/v1/versions/medal_data_config?version_id="),
        'versions_endpoint' => env('CREATOR_VERSIONS_ENDPOINT','/api/v1/versions'),
        'study_id' => env('STUDY_ID','Dynamic Tanzania'),
        'language' => env('LANGUAGE','en'),
    ],
];
