<?php

return [
    'public_extract_name' => 'ibu.zip',
    'patient_discarded_names' => ['test'],

    'file_names' => [
        'patients' => 'patients.csv'
    ],

    'identifiers' => [
        'patient' => [
            'dyn_pat_study_id_patient' => 'id',
            'dyn_pat_first_name' => 'first_name',
            'dyn_pat_last_name' => 'last_name',
            => 'created_at',
            => 'updated_at',
            => 'birthdate',
            => 'gender',
            => 'local_patient_id',
            => 'group_id',
            => 'consent',
            => 'redcap',
            => 'duplicate',
            => 'other_uid',
            => 'other_study_id',
            => 'other_group_id',
            => 'merged_with',
            => 'merged',
            => 'status',
            => 'related_ids',
            => 'middle_name',
            => 'other_id'
        ]
        ,merged_with,merged,status,related_ids,middle_name,other_id
    ]
];