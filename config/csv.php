<?php

return [
  'public_extract_name' => 'ibu.zip',
  'patient_discarded_names' => ['test'],

  'file_names' => [
    'patients' => 'patients.csv'
  ],

  'identifiers' => [
    'patient' => [
      'dyn_pat_study_id_patient'  => 'id',
      'dyn_pat_first_name'        => 'first_name',
      'dyn_pat_last_name'         => 'last_name',
      'dyn_pat_created_at'        => 'created_at',
      'dyn_pat_updated_at'        => 'updated_at',
      'dyn_pat_birth_date'        => 'birthdate',
      'dyn_pat_gender'            => 'gender',
      'dyn_pat_local_patient_id'  => 'local_patient_id',
      'dyn_pat_group_id'          => 'group_id',
      'dyn_pat_consent'           => 'consent',
      'dyn_pat_consent'           => 'redcap',
      'dyn_pat_duplicate'         => 'duplicate',
      'dyn_pat_other_uid'         => 'other_uid',
      'dyn_pat_other_study_id'    => 'other_study_id',
      'dyn_pat_other_group_id'    => 'other_group_id',
      'dyn_pat_merged_with'       => 'merged_with',
      'dyn_pat_merged'            => 'merged',
      'dyn_pat_status'            => 'status',
      'dyn_pat_related_ids'       => 'related_ids',
      'dyn_pat_middle_name'       => 'middle_name',
      'dyn_pat_other_id'          => 'other_id'
    ]
  ]
];