<?php

return [
  'public_extract_name' => 'ibu.zip',
  'patient_discarded_names' => ['test'],

  'file_names' => [
    'patients'              => 'patients.csv',
    'medical_cases'         => 'medical_cases.csv',
    'medical_case_answers'  => 'medical_case_answers.csv',
    'nodes'                 => 'nodes.csv',
    'versions'              => 'versions.csv',
    'algorithms'            => 'algorithms.csv',
    'activities'            => 'activities.csv',
    'diagnoses'             => 'diagnoses.csv',
    'custom_diagnoses'      => 'custom_diagnoses.csv',
    'diagnosis_references'  => 'diagnosis_references.csv',
    'drugs'                 => 'drugs.csv',
    'additional_drugs'      => 'additional_drugs.csv',
    'drug_references'       => 'drug_references.csv',
    'managements'           => 'managements.csv',
    'management_references' => 'management_references.csv',
    'answer_types'          => 'answer_types.csv',
    'formulations'          => 'formulations.csv'
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
      'dyn_pat_redcap'            => 'redcap',
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
    ],

    'medical_case' => [
      'dyn_mc_id'                     => 'id',
      'dyn_mc_version_id'             => 'version_id',
      'dyn_mc_patient_id'             => 'patient_id',
      'dyn_mc_created_at'             => 'created_at',
      'dyn_mc_updated_at'             => 'updated_at',
      'dyn_mc_local_medical_case_id'  => 'local_medical_case_id',
      'dyn_mc_consent'                => 'consent',
      'dyn_mc_isEligible'             => 'isEligible',
      'dyn_mc_group_id'               => 'group_id',
      'dyn_mc_redcap'                 => 'redcap',
      'dyn_mc_consultation_date'      => 'consultation_date',
      'dyn_mc_closedAt'               => 'closedAt',
      'dyn_mc_force_close'            => 'force_close',
      'dyn_mc_mc_redcap_flag'         => 'mc_redcap_flag'
    ],

    'medical_case_answer' => [
      'dyn_mca_id'              => 'id',
      'dyn_mca_medical_case_id' => 'medical_case_id',
      'dyn_mca_answer_id'       => 'answer_id',
      'dyn_mca_node_id'         => 'node_id',
      'dyn_mca_value'           => 'value',
      'dyn_mca_created_at'      => 'created_at',
      'dyn_mca_updated_at'      => 'updated_at'
    ],

    'node' => [
      'dyn_nod_id'              => 'id',
      'dyn_nod_medal_c_id'      => 'medal_c_id',
      'dyn_nod_reference'       => 'reference',
      'dyn_nod_label'           => 'label',
      'dyn_nod_type'            => 'type',
      'dyn_nod_category'        => 'category',
      'dyn_nod_priority'        => 'priority',
      'dyn_nod_stage'           => 'stage',
      'dyn_nod_description'     => 'description',
      'dyn_nod_formula'         => 'formula',
      'dyn_nod_answer_type_id'  => 'answer_type_id',
      'dyn_nod_algorithm_id'    => 'algorithm_id',
      'dyn_nod_created_at'      => 'created_at',
      'dyn_nod_updated_at'      => 'updated_at',
      'dyn_nod_is_identifiable' => 'is_identifiable',
      'dyn_nod_display_format'  => 'display_format'
    ],

    'version' => [
      'dyn_ver_id'                  => 'id',
      'dyn_ver_medal_c_id'          => 'medal_c_id',
      'dyn_ver_name'                => 'name',
      'dyn_ver_algorithm_id'        => 'algorithm_id',
      'dyn_ver_created_at'          => 'created_at',
      'dyn_ver_updated_at'          => 'updated_at',
      'dyn_ver_consent_management'  => 'consent_management',
      'dyn_ver_study'               => 'study',
      'dyn_ver_is_arm_control'      => 'is_arm_control'
    ],

    'algorithm' => [
      'dyn_alg_id'              => 'id',
      'dyn_alg_medal_c_id'      => 'medal_c_id',
      'dyn_alg_name'            => 'name',
      'dyn_alg_created_at'      => 'created_at',
      'dyn_alg_updated_at'      => 'updated_at',
    ],

    'activity' => [
      'dyn_act_id'              => 'id',
      'dyn_act_medical_case_id' => 'medical_case_id',
      'dyn_act_medal_c_id'      => 'medal_c_id',
      'dyn_act_step'            => 'step',
      'dyn_act_clinician'       => 'clinician',
      'dyn_act_mac_address'     => 'mac_address',
      'dyn_act_created_at'      => 'created_at',
      'dyn_act_updated_at'      => 'updated_at'
    ],

    'diagnosis' => [
      'dyn_dia_id'            => 'id',
      'dyn_dia_medal_c_id'    => 'medal_c_id',
      'dyn_dia_label'         => 'label',
      'dyn_dia_diagnostic_id' => 'diagnostic_id',
      'dyn_dia_created_at'    => 'created_at',
      'dyn_dia_updated_at'    => 'updated_at',
      'dyn_dia_type'          => 'type',
      'dyn_dia_version_id'    => 'version_id'
    ],

    'custom_diagnosis' => [
      'dyn_cdi_id'              => 'id',
      'dyn_cdi_label'           => 'label',
      'dyn_cdi_drugs'           => 'drugs',
      'dyn_cdi_created_at'      => 'created_at',
      'dyn_cdi_updated_at'      => 'updated_at',
      'dyn_cdi_medical_case_id' => 'medical_case_id'
    ],

    'diagnosis_reference' => [
      'dyn_dre_id'              => 'id',
      'dyn_dre_agreed'          => 'agreed',
      'dyn_dre_additional'      => 'additional',
      'dyn_dre_diagnosis_id'    => 'diagnosis_id',
      'dyn_dre_medical_case_id' => 'medical_case_id',
      'dyn_dre_created_at'      => 'created_at',
      'dyn_dre_updated_at'      => 'updated_at'
    ],

    'drug' => [
      'dyn_dru_id'                => 'id',
      'dyn_dru_medal_c_id'        => 'medal_c_id',
      'dyn_dru_type'              => 'type',
      'dyn_dru_label'             => 'label',
      'dyn_dru_description'       => 'description',
      'dyn_dru_diagnosis_id'      => 'diagnosis_id',
      'dyn_dru_created_at'        => 'created_at',
      'dyn_dru_updated_at'        => 'updated_at',
      'dyn_dru_is_anti_malarial'  => 'is_anti_malarial',
      'dyn_dru_is_antibiotic'     => 'is_antibiotic',
      'dyn_dru_duration'          => 'duration'
    ],

    'additional_drug' => [
      'dyn_adr_id'                  => 'id',
      'dyn_adr_drug_id'             => 'drug_id',
      'dyn_adr_medical_case_id'     => 'medical_case_id',
      'dyn_adr_formulationSelected' => 'formulationSelected',
      'dyn_adr_agreed'              => 'agreed',
      'dyn_adr_version_id'          => 'version_id',
      'dyn_adr_created_at'          => 'created_at',
      'dyn_adr_updated_at'          => 'updated_at'
    ],

    'drug_reference' => [
      'dyn_dre_id'                  => 'id',
      'dyn_dre_drug_id'             => 'drug_id',
      'dyn_dre_diagnosis_id'        => 'diagnosis_id',
      'dyn_dre_agreed'              => 'agreed',
      'dyn_dre_created_at'          => 'created_at',
      'dyn_dre_updated_at'          => 'updated_at',
      'dyn_dre_formulationSelected' => 'formulationSelected',
      'dyn_dre_formulation_id'      => 'formulation_id',
      'dyn_dre_additional'          => 'additional',
      'dyn_dre_duration'            => 'duration'
    ],

    'management' => [
      'dyn_man_id'                  => 'id',
      'dyn_man_drug_id'             => 'drug_id',
      'dyn_man_diagnosis_id'        => 'diagnosis_id',
      'dyn_man_agreed'              => 'agreed',
      'dyn_man_created_at'          => 'created_at',
      'dyn_man_updated_at'          => 'updated_at',
      'dyn_man_formulationSelected' => 'formulationSelected',
      'dyn_man_formulation_id'      => 'formulation_id',
      'dyn_man_additional'          => 'additional',
      'dyn_man_duration'            => 'duration'
    ],

    'management_reference' => [
      'dyn_mre_id'            => 'id',
      'dyn_mre_agreed'        => 'agreed',
      'dyn_mre_diagnosis_id'  => 'diagnosis_id',
      'dyn_mre_created_at'    => 'created_at',
      'dyn_mre_updated_at'    => 'updated_at',
      'dyn_mre_management_id' => 'management_id'
    ],

    'answer_type' => [
      'dyn_aty_id'          => 'id',
      'dyn_aty_value'       => 'value',
      'dyn_aty_created_at'  => 'created_at',
      'dyn_aty_updated_at'  => 'updated_at'
    ],

    'formulation' => [
      'dyn_for_id'                            => 'id',
      'dyn_for_medication_form'               => 'medication_form',
      'dyn_for_administration_route_name'     => 'administration_route_name',
      'dyn_for_liquid_concentration'          => 'liquid_concentration',
      'dyn_for_dose_form'                     => 'dose_form',
      'dyn_for_unique_dose'                   => 'unique_dose',
      'dyn_for_by_age'                        => 'by_age',
      'dyn_for_minimal_dose_per_kg'           => 'minimal_dose_per_kg',
      'dyn_for_maximal_dose'                  => 'maximal_dose',
      'dyn_for_description'                   => 'description',
      'dyn_for_doses_per_day'                 => 'doses_per_day',
      'dyn_for_created_at'                    => 'created_at',
      'dyn_for_updated_at'                    => 'updated_at',
      'dyn_for_drug_id'                       => 'drug_id',
      'dyn_for_administration_route_category' => 'administration_route_category',
      'dyn_for_medal_c_id'                    => 'medal_c_id'
    ]
  ]
];