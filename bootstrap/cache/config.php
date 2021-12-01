<?php return array (
  'adminlte' => 
  array (
    'title' => 'ePOCT+  Main data',
    'title_prefix' => '',
    'title_postfix' => '',
    'use_ico_only' => false,
    'use_full_favicon' => false,
    'logo' => '<b>ePOCT+</b>Main data',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image-xl',
    'logo_img_xl' => NULL,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'ePOCT+ Main data',
    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,
    'layout_topnav' => NULL,
    'layout_boxed' => NULL,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => NULL,
    'layout_fixed_footer' => NULL,
    'layout_dark_mode' => NULL,
    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',
    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => 'container-fluid',
    'classes_content' => 'container-fluid',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand-md',
    'classes_topnav_container' => 'container-fluid',
    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,
    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',
    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',
    'menu' => 
    array (
      0 => 
      array (
        'text' => 'Follow-Up',
        'url' => '/followUp',
        'icon' => 'fas fa-fw fa-check-circle',
        'can' => 'Merge_Duplicates',
      ),
      1 => 
      array (
        'text' => 'Duplicates',
        'url' => '/patients/duplicates',
        'icon' => 'fas fa-fw fa-users',
        'can' => 'Merge_Duplicates',
      ),
      2 => 
      array (
        'text' => 'Facilities',
        'url' => '/facilities/index',
        'icon' => 'fas fa-fw fa-cart-plus',
        'can' => 'View_Patient',
      ),
      3 => 
      array (
        'text' => 'Health Facilities',
        'url' => '/health-facilities',
        'icon' => 'fas fa-fw fa-cart-plus',
        'can' => 'Manage_Health_Facilities',
      ),
      4 => 
      array (
        'text' => 'Devices',
        'url' => '/devices',
        'icon' => 'fas fa-fw fa-tablet-alt',
        'can' => 'Manage_Devices',
      ),
      5 => 
      array (
        'text' => 'Medical staff',
        'url' => '/medical-staff',
        'icon' => 'fas fa-fw fa-users',
        'can' => 'Manage_Medical_Staff',
      ),
      6 => 
      array (
        'text' => 'Patient list',
        'url' => '/patients',
        'icon' => 'fas fa-fw fa-list',
        'can' => 'View_Patient',
      ),
      7 => 
      array (
        'text' => 'Medical Cases',
        'url' => '/medicalcases',
        'icon' => 'fas fa-fw fa-file',
        'can' => 'View_Case',
      ),
      8 => 
      array (
        'text' => 'Diagnosis List',
        'url' => '/exports/diagnosis_list',
        'can' => 'Export',
      ),
      9 => 
      array (
        'text' => 'Drug List',
        'url' => '/exports/drug_list',
        'can' => 'Export',
      ),
      10 => 
      array (
        'text' => 'Exports',
        'icon' => 'fas fa-fw fa-clone',
        'can' => 'Export',
        'url' => '/exports/exportZip',
      ),
      11 => 
      array (
        'text' => 'profile',
        'url' => '/user/profile',
        'icon' => 'fas fa-fw fa-user',
        'can' => 'Reset_Own_Password',
      ),
      12 => 
      array (
        'text' => 'change_password',
        'url' => '/user/password',
        'icon' => 'fas fa-fw fa-lock',
        'can' => 'Reset_Own_Password',
      ),
      13 => 
      array (
        'text' => 'Admin Corner',
        'icon' => 'fas fa-fw fa-cog',
        'can' => 'Access_ADMIN_PANEL',
        'submenu' => 
        array (
          0 => 
          array (
            'text' => 'Users Management',
            'url' => '/users',
            'icon' => 'fas fa-fw fa-users',
          ),
          1 => 
          array (
            'text' => 'Manage Roles',
            'url' => '/roles',
            'icon' => 'fas fa-fw fa-pencil-alt',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      0 => 'JeroenNoten\\LaravelAdminLte\\Menu\\Filters\\HrefFilter',
      1 => 'JeroenNoten\\LaravelAdminLte\\Menu\\Filters\\SearchFilter',
      2 => 'JeroenNoten\\LaravelAdminLte\\Menu\\Filters\\ActiveFilter',
      3 => 'JeroenNoten\\LaravelAdminLte\\Menu\\Filters\\ClassesFilter',
      4 => 'JeroenNoten\\LaravelAdminLte\\Menu\\Filters\\GateFilter',
      5 => 'JeroenNoten\\LaravelAdminLte\\Menu\\Filters\\LangFilter',
    ),
    'plugins' => 
    array (
      0 => 
      array (
        'name' => 'Datatables',
        'active' => false,
        'files' => 
        array (
          0 => 
          array (
            'type' => 'js',
            'asset' => false,
            'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
          ),
          1 => 
          array (
            'type' => 'js',
            'asset' => false,
            'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
          ),
          2 => 
          array (
            'type' => 'css',
            'asset' => false,
            'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
          ),
        ),
      ),
      1 => 
      array (
        'name' => 'Select2',
        'active' => false,
        'files' => 
        array (
          0 => 
          array (
            'type' => 'js',
            'asset' => false,
            'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
          ),
          1 => 
          array (
            'type' => 'css',
            'asset' => false,
            'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
          ),
        ),
      ),
      2 => 
      array (
        'name' => 'Chartjs',
        'active' => false,
        'files' => 
        array (
          0 => 
          array (
            'type' => 'js',
            'asset' => false,
            'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
          ),
        ),
      ),
      3 => 
      array (
        'name' => 'Sweetalert2',
        'active' => false,
        'files' => 
        array (
          0 => 
          array (
            'type' => 'js',
            'asset' => false,
            'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
          ),
        ),
      ),
      4 => 
      array (
        'name' => 'Pace',
        'active' => false,
        'files' => 
        array (
          0 => 
          array (
            'type' => 'css',
            'asset' => false,
            'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
          ),
          1 => 
          array (
            'type' => 'js',
            'asset' => false,
            'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
          ),
        ),
      ),
    ),
    'livewire' => false,
  ),
  'app' => 
  array (
    'name' => 'medal-data',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:CUUFSizhkulvSb93EPfz0/Z3j673+pqzS8xHaal7Gp4=',
    'cipher' => 'AES-256-CBC',
    'study_id' => 'Dynamic Tanzania',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'yajra\\Datatables\\DatatablesServiceProvider',
      23 => 'Spatie\\Permission\\PermissionServiceProvider',
      24 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      25 => 'Madnest\\Madzipper\\MadzipperServiceProvider',
      26 => 'Intervention\\Image\\ImageServiceProvider',
      27 => 'Barryvdh\\DomPDF\\ServiceProvider',
      28 => 'PragmaRX\\Google2FALaravel\\ServiceProvider',
      29 => 'App\\Providers\\AppServiceProvider',
      30 => 'App\\Providers\\AuthServiceProvider',
      31 => 'App\\Providers\\EventServiceProvider',
      32 => 'OwenIt\\Auditing\\AuditingServiceProvider',
      33 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Datatables' => 'yajra\\Datatables\\Datatables',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'Madzipper' => 'Madnest\\Madzipper\\Madzipper',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
      'Google2FA' => 'PragmaRX\\Google2FALaravel\\Facade',
    ),
  ),
  'audit' => 
  array (
    'enabled' => true,
    'implementation' => 'OwenIt\\Auditing\\Models\\Audit',
    'user' => 
    array (
      'morph_prefix' => 'user',
      'guards' => 
      array (
        0 => 'web',
        1 => 'api',
      ),
    ),
    'resolver' => 
    array (
      'user' => 'OwenIt\\Auditing\\Resolvers\\UserResolver',
      'ip_address' => 'OwenIt\\Auditing\\Resolvers\\IpAddressResolver',
      'user_agent' => 'OwenIt\\Auditing\\Resolvers\\UserAgentResolver',
      'url' => 'OwenIt\\Auditing\\Resolvers\\UrlResolver',
    ),
    'events' => 
    array (
      0 => 'created',
      1 => 'updated',
      2 => 'deleted',
      3 => 'restored',
    ),
    'strict' => false,
    'timestamps' => false,
    'threshold' => 0,
    'driver' => 'database',
    'drivers' => 
    array (
      'database' => 
      array (
        'table' => 'audits',
        'connection' => NULL,
      ),
    ),
    'console' => false,
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'passport',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'encrypted' => true,
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/home/vagrant/code/liwi-main-data/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
      ),
    ),
    'prefix' => 'medal_data_cache',
  ),
  'charts' => 
  array (
    'default_library' => 'Chartjs',
  ),
  'csv' => 
  array (
    'hide_str' => '###',
    'public_extract_name_flat' => 'export_flat',
    'public_extract_name_separated' => 'export_separated',
    'patient_discarded_names' => 
    array (
      0 => 'test',
    ),
    'file_names' => 
    array (
      'patients' => 'patients.csv',
      'medical_cases' => 'medical_cases.csv',
      'medical_case_answers' => 'medical_case_answers.csv',
      'nodes' => 'nodes.csv',
      'versions' => 'versions.csv',
      'algorithms' => 'algorithms.csv',
      'activities' => 'activities.csv',
      'diagnoses' => 'diagnoses.csv',
      'custom_diagnoses' => 'custom_diagnoses.csv',
      'diagnosis_references' => 'diagnosis_references.csv',
      'drugs' => 'drugs.csv',
      'additional_drugs' => 'additional_drugs.csv',
      'drug_references' => 'drug_references.csv',
      'managements' => 'managements.csv',
      'management_references' => 'management_references.csv',
      'answer_types' => 'answer_types.csv',
      'formulations' => 'formulations.csv',
      'answers' => 'answers.csv',
    ),
    'folder_separated' => '/separated/',
    'identifiers' => 
    array (
      'patient' => 
      array (
        'dyn_pat_study_id_patient' => 'id',
        'dyn_pat_first_name' => 'first_name',
        'dyn_pat_last_name' => 'last_name',
        'dyn_pat_created_at' => 'created_at',
        'dyn_pat_updated_at' => 'updated_at',
        'dyn_pat_birth_date' => 'birthdate',
        'dyn_pat_gender' => 'gender',
        'dyn_pat_local_patient_id' => 'local_patient_id',
        'dyn_pat_group_id' => 'group_id',
        'dyn_pat_consent' => 'consent',
        'dyn_pat_redcap' => 'redcap',
        'dyn_pat_duplicate' => 'duplicate',
        'dyn_pat_other_uid' => 'other_uid',
        'dyn_pat_other_study_id' => 'other_study_id',
        'dyn_pat_other_group_id' => 'other_group_id',
        'dyn_pat_merged_with' => 'merged_with',
        'dyn_pat_merged' => 'merged',
        'dyn_pat_status' => 'status',
        'dyn_pat_related_ids' => 'related_ids',
        'dyn_pat_middle_name' => 'middle_name',
        'dyn_pat_other_id' => 'other_id',
      ),
      'medical_case' => 
      array (
        'dyn_mc_id' => 'id',
        'dyn_mc_version_id' => 'version_id',
        'dyn_mc_patient_id' => 'patient_id',
        'dyn_mc_created_at' => 'created_at',
        'dyn_mc_updated_at' => 'updated_at',
        'dyn_mc_local_medical_case_id' => 'local_medical_case_id',
        'dyn_mc_consent' => 'consent',
        'dyn_mc_isEligible' => 'isEligible',
        'dyn_mc_group_id' => 'group_id',
        'dyn_mc_redcap' => 'redcap',
        'dyn_mc_consultation_date' => 'consultation_date',
        'dyn_mc_closedAt' => 'closedAt',
        'dyn_mc_force_close' => 'force_close',
        'dyn_mc_mc_redcap_flag' => 'mc_redcap_flag',
      ),
      'medical_case_answer' => 
      array (
        'dyn_mca_id' => 'id',
        'dyn_mca_medical_case_id' => 'medical_case_id',
        'dyn_mca_answer_id' => 'answer_id',
        'dyn_mca_node_id' => 'node_id',
        'dyn_mca_value' => 'value',
        'dyn_mca_created_at' => 'created_at',
        'dyn_mca_updated_at' => 'updated_at',
      ),
      'node' => 
      array (
        'dyn_nod_id' => 'id',
        'dyn_nod_medal_c_id' => 'medal_c_id',
        'dyn_nod_reference' => 'reference',
        'dyn_nod_label' => 'label',
        'dyn_nod_type' => 'type',
        'dyn_nod_category' => 'category',
        'dyn_nod_priority' => 'priority',
        'dyn_nod_stage' => 'stage',
        'dyn_nod_description' => 'description',
        'dyn_nod_formula' => 'formula',
        'dyn_nod_answer_type_id' => 'answer_type_id',
        'dyn_nod_algorithm_id' => 'algorithm_id',
        'dyn_nod_created_at' => 'created_at',
        'dyn_nod_updated_at' => 'updated_at',
        'dyn_nod_is_identifiable' => 'is_identifiable',
        'dyn_nod_display_format' => 'display_format',
      ),
      'version' => 
      array (
        'dyn_ver_id' => 'id',
        'dyn_ver_medal_c_id' => 'medal_c_id',
        'dyn_ver_name' => 'name',
        'dyn_ver_algorithm_id' => 'algorithm_id',
        'dyn_ver_created_at' => 'created_at',
        'dyn_ver_updated_at' => 'updated_at',
        'dyn_ver_consent_management' => 'consent_management',
        'dyn_ver_study' => 'study',
        'dyn_ver_is_arm_control' => 'is_arm_control',
      ),
      'algorithm' => 
      array (
        'dyn_alg_id' => 'id',
        'dyn_alg_medal_c_id' => 'medal_c_id',
        'dyn_alg_name' => 'name',
        'dyn_alg_created_at' => 'created_at',
        'dyn_alg_updated_at' => 'updated_at',
      ),
      'activity' => 
      array (
        'dyn_act_id' => 'id',
        'dyn_act_medical_case_id' => 'medical_case_id',
        'dyn_act_medal_c_id' => 'medal_c_id',
        'dyn_act_step' => 'step',
        'dyn_act_clinician' => 'clinician',
        'dyn_act_mac_address' => 'mac_address',
        'dyn_act_created_at' => 'created_at',
        'dyn_act_updated_at' => 'updated_at',
      ),
      'diagnosis' => 
      array (
        'dyn_dia_id' => 'id',
        'dyn_dia_medal_c_id' => 'medal_c_id',
        'dyn_dia_label' => 'label',
        'dyn_dia_diagnostic_id' => 'diagnostic_id',
        'dyn_dia_created_at' => 'created_at',
        'dyn_dia_updated_at' => 'updated_at',
        'dyn_dia_type' => 'type',
        'dyn_dia_version_id' => 'version_id',
      ),
      'custom_diagnosis' => 
      array (
        'dyn_cdi_id' => 'id',
        'dyn_cdi_label' => 'label',
        'dyn_cdi_drugs' => 'drugs',
        'dyn_cdi_created_at' => 'created_at',
        'dyn_cdi_updated_at' => 'updated_at',
        'dyn_cdi_medical_case_id' => 'medical_case_id',
      ),
      'diagnosis_reference' => 
      array (
        'dyn_dre_id' => 'id',
        'dyn_dre_agreed' => 'agreed',
        'dyn_dre_additional' => 'additional',
        'dyn_dre_diagnosis_id' => 'diagnosis_id',
        'dyn_dre_medical_case_id' => 'medical_case_id',
        'dyn_dre_created_at' => 'created_at',
        'dyn_dre_updated_at' => 'updated_at',
      ),
      'drug' => 
      array (
        'dyn_dru_id' => 'id',
        'dyn_dru_medal_c_id' => 'medal_c_id',
        'dyn_dru_type' => 'type',
        'dyn_dru_label' => 'label',
        'dyn_dru_description' => 'description',
        'dyn_dru_diagnosis_id' => 'diagnosis_id',
        'dyn_dru_created_at' => 'created_at',
        'dyn_dru_updated_at' => 'updated_at',
        'dyn_dru_is_anti_malarial' => 'is_anti_malarial',
        'dyn_dru_is_antibiotic' => 'is_antibiotic',
        'dyn_dru_duration' => 'duration',
      ),
      'additional_drug' => 
      array (
        'dyn_adr_id' => 'id',
        'dyn_adr_drug_id' => 'drug_id',
        'dyn_adr_medical_case_id' => 'medical_case_id',
        'dyn_adr_formulationSelected' => 'formulationSelected',
        'dyn_adr_agreed' => 'agreed',
        'dyn_adr_version_id' => 'version_id',
        'dyn_adr_created_at' => 'created_at',
        'dyn_adr_updated_at' => 'updated_at',
      ),
      'drug_reference' => 
      array (
        'dyn_dre_id' => 'id',
        'dyn_dre_drug_id' => 'drug_id',
        'dyn_dre_diagnosis_id' => 'diagnosis_id',
        'dyn_dre_agreed' => 'agreed',
        'dyn_dre_created_at' => 'created_at',
        'dyn_dre_updated_at' => 'updated_at',
        'dyn_dre_formulationSelected' => 'formulationSelected',
        'dyn_dre_formulation_id' => 'formulation_id',
        'dyn_dre_additional' => 'additional',
        'dyn_dre_duration' => 'duration',
      ),
      'management' => 
      array (
        'dyn_man_id' => 'id',
        'dyn_man_drug_id' => 'drug_id',
        'dyn_man_diagnosis_id' => 'diagnosis_id',
        'dyn_man_agreed' => 'agreed',
        'dyn_man_created_at' => 'created_at',
        'dyn_man_updated_at' => 'updated_at',
        'dyn_man_formulationSelected' => 'formulationSelected',
        'dyn_man_formulation_id' => 'formulation_id',
        'dyn_man_additional' => 'additional',
        'dyn_man_duration' => 'duration',
      ),
      'management_reference' => 
      array (
        'dyn_mre_id' => 'id',
        'dyn_mre_agreed' => 'agreed',
        'dyn_mre_diagnosis_id' => 'diagnosis_id',
        'dyn_mre_created_at' => 'created_at',
        'dyn_mre_updated_at' => 'updated_at',
        'dyn_mre_management_id' => 'management_id',
      ),
      'answer_type' => 
      array (
        'dyn_aty_id' => 'id',
        'dyn_aty_value' => 'value',
        'dyn_aty_created_at' => 'created_at',
        'dyn_aty_updated_at' => 'updated_at',
      ),
      'formulation' => 
      array (
        'dyn_for_id' => 'id',
        'dyn_for_medication_form' => 'medication_form',
        'dyn_for_administration_route_name' => 'administration_route_name',
        'dyn_for_liquid_concentration' => 'liquid_concentration',
        'dyn_for_dose_form' => 'dose_form',
        'dyn_for_unique_dose' => 'unique_dose',
        'dyn_for_by_age' => 'by_age',
        'dyn_for_minimal_dose_per_kg' => 'minimal_dose_per_kg',
        'dyn_for_maximal_dose' => 'maximal_dose',
        'dyn_for_description' => 'description',
        'dyn_for_doses_per_day' => 'doses_per_day',
        'dyn_for_created_at' => 'created_at',
        'dyn_for_updated_at' => 'updated_at',
        'dyn_for_drug_id' => 'drug_id',
        'dyn_for_administration_route_category' => 'administration_route_category',
        'dyn_for_medal_c_id' => 'medal_c_id',
      ),
      'answer' => 
      array (
        'dyn_ans_id' => 'id',
        'dyn_ans_label' => 'label',
        'dyn_ans_medal_c_id' => 'medal_c_id',
        'dyn_ans_node_id' => 'node_id',
        'dyn_ans_created_at' => 'created_at',
        'dyn_ans_updated_at' => 'updated_at',
      ),
    ),
    'flat' => 
    array (
      'folder' => '/answers/',
      'identifiers' => 
      array (
        'patient' => 
        array (
          'dyn_pat_study_id_patient' => 'patient_id',
          'dyn_pat_birth_date' => 'patient_birthdate',
          'dyn_pat_gender' => 'patient_gender',
          'dyn_pat_local_patient_id' => 'patient_local_patient_id',
          'dyn_pat_group_id' => 'patient_group_id',
          'dyn_pat_consent' => 'patient_consent',
          'dyn_pat_redcap' => 'patient_redcap',
          'dyn_pat_duplicate' => 'patient_duplicate',
          'dyn_pat_other_uid' => 'patient_other_uid',
          'dyn_pat_other_study_id' => 'patient_other_study_id',
          'dyn_pat_other_group_id' => 'patient_other_group_id',
          'dyn_pat_merged_with' => 'patient_merged_with',
          'dyn_pat_merged' => 'patient_merged',
          'dyn_pat_status' => 'patient_status',
          'dyn_pat_related_ids' => 'patient_related_ids',
          'dyn_pat_middle_name' => 'patient_middle_name',
          'dyn_pat_other_id' => 'patient_other_id',
        ),
        'medical_case' => 
        array (
          'dyn_mc_id' => 'medical_case_id',
          'dyn_mc_local_medical_case_id' => 'medical_case_local_id',
          'dyn_mc_consent' => 'medical_case_consent',
          'dyn_mc_isEligible' => 'medical_case_isEligible',
          'dyn_mc_redcap' => 'medical_case_redcap',
          'dyn_mc_consultation_month' => 'medical_case_consultation_month',
          'dyn_mc_consultation_day' => 'medical_case_consultation_day',
          'dyn_mc_force_close' => 'medical_case_close',
          'dyn_mc_mc_redcap_flag' => 'medical_case_mc_redcap_flag',
        ),
        'health_facility' => 
        array (
          'dyn_hfa_id' => 'health_facility_id',
          'dyn_hfa_long' => 'health_facility_longitude',
          'dyn_hfa_lat' => 'health_facility_latitude',
          'dyn_hfa_hf_mode' => 'health_facility_hf_mode',
          'dyn_hfa_name' => 'health_facility_name',
          'dyn_hfa_country' => 'health_facility_country',
          'dyn_hfa_area' => 'health_facility_area',
        ),
        'version' => 
        array (
          'dyn_ver_id' => 'version_id',
          'dyn_ver_medal_c_id' => 'version_medal_c_id',
          'dyn_ver_name' => 'version_name',
          'dyn_ver_consent_management' => 'version_consent_management',
          'dyn_ver_study' => 'version_study',
          'dyn_ver_is_arm_control' => 'version_is_arm_control',
        ),
        'algorithm' => 
        array (
          'dyn_alg_id' => 'algorithm_id',
          'dyn_alg_name' => 'algorithm_name',
        ),
      ),
    ),
  ),
  'database' => 
  array (
    'default' => 'postgres',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'medal-data',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '5432',
        'database' => 'medal-data',
        'username' => 'homestead',
        'password' => 'secret',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
      ),
      'postgres-local' => 
      array (
        'driver' => 'pgsql',
        'host' => '127.0.0.1',
        'port' => '5432',
        'database' => 'medal-data',
        'username' => 'homestead',
        'password' => 'secret',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'postgres' => 
      array (
        'driver' => 'pgsql',
        'url' => 'postgres://homestead:secret@127.0.0.1:5432/medal-data',
        'host' => '127.0.0.1',
        'port' => '5432',
        'database' => 'medal-data',
        'username' => 'homestead',
        'password' => 'secret',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => '127.0.0.1',
        'port' => '5432',
        'database' => 'medal-data',
        'username' => 'homestead',
        'password' => 'secret',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
      'cache' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 1,
      ),
    ),
  ),
  'debug-server' => 
  array (
    'host' => 'tcp://127.0.0.1:9912',
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/home/vagrant/code/liwi-main-data/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/home/vagrant/code/liwi-main-data/storage/app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
        'url' => NULL,
      ),
    ),
  ),
  'google2fa' => 
  array (
    'enabled' => true,
    'lifetime' => 0,
    'keep_alive' => true,
    'auth' => 'auth',
    'guard' => '',
    'session_var' => 'google2fa',
    'otp_input' => 'one_time_password',
    'window' => 1,
    'forbid_old_passwords' => false,
    'otp_secret_column' => 'google2fa_secret',
    'view' => 'google2fa.index',
    'error_messages' => 
    array (
      'wrong_otp' => 'The \'One Time Password\' typed was wrong.',
      'cannot_be_empty' => 'One Time Password cannot be empty.',
      'unknown' => 'An unknown error has occurred. Please try again.',
    ),
    'throw_exceptions' => true,
    'qrcode_image_backend' => 'svg',
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 1024,
      'threads' => 2,
      'time' => 2,
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'daily',
          1 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'errorlog',
        'path' => '/home/vagrant/code/liwi-main-data/storage/logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/home/vagrant/code/liwi-main-data/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => '127.0.0.1',
    'port' => '1025',
    'from' => 
    array (
      'address' => 'admin@dynamic.com',
      'name' => 'medal-data',
    ),
    'encryption' => NULL,
    'username' => NULL,
    'password' => NULL,
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/home/vagrant/code/liwi-main-data/resources/views/vendor/mail',
      ),
    ),
    'log_channel' => NULL,
  ),
  'medal' => 
  array (
    'uuid' => NULL,
    'authentication' => 
    array (
      'hub_callback_url' => 'http://127.0.0.1:5555',
      'reader_callback_url' => 'http://127.0.0.1:5555',
      'token_lifetime_days' => '1',
      'refresh_token_lifetime_days' => '30',
    ),
    'creator' => 
    array (
      'url' => 'https://liwi.wavelab.top',
      'algorithms_endpoint' => '/api/v1/algorithms',
      'health_facility_endpoint' => '/api/v1/health_facilities',
      'medal_data_config_endpoint' => '/api/v1/versions/medal_data_config?version_id=',
      'versions_endpoint' => '/api/v1/versions',
      'get_from_study' => '/api/v1/health_facilities/get_from_study?study_label=',
      'study_id' => 'Dynamic Tanzania',
      'language' => 'en_US:',
    ),
    'urls' => 
    array (
      'creator_algorithm_url' => 'https://medalc.unisante.ch/api/v1/versions/',
      'creator_health_facility_url' => 'https://medalc.unisante.ch/api/v1/health_facilities/',
      'creator_patient_url' => 'https://medalc.unisante.ch/api/v1/versions/medal_data_config',
    ),
    'global' => 
    array (
      'study_id' => 'Dynamic Tanzania',
      'language' => 'en',
      'local_health_facility_management' => false,
    ),
    'storage' => 
    array (
      'cases_zip_dir' => NULL,
      'json_extract_dir' => 'json_extract',
      'json_success_dir' => 'json_success',
      'json_failure_dir' => 'json_failure',
      'consent_img_dir' => NULL,
    ),
    'case_json_properties' => 
    array (
      'algorithm' => 
      array (
        'keys' => 
        array (
          'name' => 'algorithm_name',
          'medal_c_id' => 'algorithm_id',
        ),
      ),
      'activities' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
        ),
        'values' => 
        array (
          'step' => 'step',
          'clinician' => 'clinician',
          'mac_address' => 'mac_address',
        ),
      ),
      'version' => 
      array (
        'keys' => 
        array (
          'name' => 'version_name',
          'medal_c_id' => 'version_id',
        ),
      ),
      'node' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
        ),
        'values' => 
        array (
          'label' => 
          array (
            'key' => 'label',
            'modifiers' => 
            array (
              0 => 'language',
            ),
          ),
          'type' => 'type',
          'category' => 'category',
          'priority' => 'is_mandatory',
          'reference' => 
          array (
            'key' => 'reference',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'string',
          ),
          'display_format' => 
          array (
            'key' => 'display_format',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'string',
          ),
          'stage' => 
          array (
            'key' => 'stage',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'string',
          ),
          'description' => 
          array (
            'key' => 'description',
            'modifiers' => 
            array (
              0 => 'language',
              1 => 'optional',
            ),
            'type' => 'string',
          ),
          'formula' => 
          array (
            'key' => 'formula',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'string',
          ),
          'is_identifiable' => 'is_identifiable',
        ),
      ),
      'answer_type' => 
      array (
        'keys' => 
        array (
          'value' => 'value_format',
        ),
      ),
      'answer' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
        ),
        'values' => 
        array (
          'label' => 
          array (
            'key' => 'label',
            'modifiers' => 
            array (
              0 => 'language',
            ),
          ),
        ),
      ),
      'patient_config' => 
      array (
      ),
      'health_facility' => 
      array (
        'keys' => 
        array (
          'name' => 'name',
        ),
        'values' => 
        array (
          'group_id' => 'id',
          'long' => 'longitude',
          'lat' => 'latitude',
          'hf_mode' => 'architecture',
        ),
      ),
      'patient' => 
      array (
        'keys' => 
        array (
          'local_patient_id' => 'uid',
        ),
        'values' => 
        array (
          'first_name' => 'first_name',
          'last_name' => 'last_name',
          'birthdate' => 
          array (
            'key' => 'birth_date',
            'modifiers' => 
            array (
              0 => 'datetime-epoch',
            ),
          ),
          'group_id' => 'group_id',
          'other_group_id' => 'other_group_id',
          'other_study_id' => 'other_study_id',
          'other_uid' => 'other_uid',
          'created_at' => 
          array (
            'key' => 'createdAt',
            'modifiers' => 
            array (
              0 => 'datetime-epoch',
            ),
          ),
          'updated_at' => 
          array (
            'key' => 'updatedAt',
            'modifiers' => 
            array (
              0 => 'datetime-epoch',
            ),
          ),
        ),
      ),
      'diagnosis' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
          'diagnostic_id' => 'diagnosis_id',
        ),
        'values' => 
        array (
          'label' => 
          array (
            'key' => 'label',
            'modifiers' => 
            array (
              0 => 'language',
            ),
          ),
          'type' => 'type',
        ),
      ),
      'drug' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
        ),
        'values' => 
        array (
          'type' => 
          array (
            'key' => 'type',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'string',
          ),
          'label' => 
          array (
            'key' => 'label',
            'modifiers' => 
            array (
              0 => 'language',
            ),
          ),
          'description' => 
          array (
            'key' => 'description',
            'modifiers' => 
            array (
              0 => 'language',
              1 => 'optional',
            ),
            'type' => 'string',
          ),
          'is_antibiotic' => 
          array (
            'key' => 'is_antibiotic',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'object',
          ),
          'is_anti_malarial' => 
          array (
            'key' => 'is_anti_malarial',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'object',
          ),
        ),
      ),
      'management' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
        ),
        'values' => 
        array (
          'type' => 
          array (
            'key' => 'type',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
            'type' => 'string',
          ),
          'label' => 
          array (
            'key' => 'label',
            'modifiers' => 
            array (
              0 => 'language',
            ),
          ),
          'description' => 
          array (
            'key' => 'description',
            'modifiers' => 
            array (
              0 => 'language',
              1 => 'optional',
            ),
            'type' => 'string',
          ),
        ),
      ),
      'formulation' => 
      array (
        'keys' => 
        array (
          'medal_c_id' => 'id',
        ),
        'values' => 
        array (
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
          'description' => 
          array (
            'key' => 'description',
            'modifiers' => 
            array (
              0 => 'language',
              1 => 'optional',
            ),
            'type' => 'string',
          ),
        ),
      ),
      'medical_case' => 
      array (
        'keys' => 
        array (
          'local_medical_case_id' => 'id',
        ),
        'values' => 
        array (
          'consent' => 'consent',
        ),
      ),
      'medical_case_answer' => 
      array (
      ),
      'diagnosis_reference' => 
      array (
      ),
      'drug_reference' => 
      array (
        'values' => 
        array (
          'duration' => 
          array (
            'key' => 'duration',
            'modifiers' => 
            array (
              0 => 'optional',
            ),
          ),
        ),
      ),
      'management_reference' => 
      array (
      ),
      'custom_diagnosis' => 
      array (
        'keys' => 
        array (
          'label' => 'name',
        ),
      ),
      'custom_drug' => 
      array (
        'keys' => 
        array (
          'name' => 'name',
        ),
        'values' => 
        array (
          'duration' => 'duration',
        ),
      ),
    ),
  ),
  'medal-data' => 
  array (
    'urls' => 
    array (
      'creator_algorithm_url' => 'https://medalc.unisante.ch/api/v1/versions/',
      'creator_health_facility_url' => 'https://medalc.unisante.ch/api/v1/health_facilities/',
      'creator_patient_url' => 'https://medalc.unisante.ch/api/v1/versions/medal_data_config',
    ),
    'global' => 
    array (
      'study_id' => 'Dynamic Tanzania',
      'language' => 'en',
      'ip' => NULL,
    ),
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Spatie\\Permission\\Models\\Role',
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'model_morph_key' => 'model_id',
    ),
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      DateInterval::__set_state(array(
         'y' => 0,
         'm' => 0,
         'd' => 0,
         'h' => 24,
         'i' => 0,
         's' => 0,
         'f' => 0.0,
         'weekday' => 0,
         'weekday_behavior' => 0,
         'first_last_day_of' => 0,
         'invert' => 0,
         'days' => false,
         'special_type' => 0,
         'special_amount' => 0,
         'have_weekday_relative' => 0,
         'have_special_relative' => 0,
      )),
      'key' => 'spatie.permission.cache',
      'model_key' => 'name',
      'store' => 'default',
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
      ),
    ),
    'failed' => 
    array (
      'database' => 'postgres',
      'table' => 'failed_jobs',
    ),
  ),
  'redcap' => 
  array (
    'identifiers' => 
    array (
      'api_url_followup' => '',
      'api_token_followup' => '',
      'api_url_patient' => '',
      'api_token_patient' => '',
      'api_url_medical_case' => '',
      'api_token_medical_case' => '',
      'patient' => 
      array (
        'dyn_pat_study_id_patient' => 'dyn_pat_study_id_patient',
        'dyn_pat_first_name' => 'dyn_pat_first_name',
        'dyn_pat_middle_name' => 'dyn_pat_middle_name',
        'dyn_pat_last_name' => 'dyn_pat_last_name',
        'dyn_pat_dob' => 'dyn_pat_dob',
        'dyn_pat_village' => 'dyn_pat_village',
        'dyn_pat_sex' => 'dyn_pat_sex',
        'dyn_pat_first_name_caregiver' => 'dyn_pat_first_name_caregiver',
        'dyn_pat_last_name_caregiver' => 'dyn_pat_last_name_caregiver',
        'dyn_pat_relationship_child' => 'dyn_pat_relationship_child',
        'dyn_pat_relationship_child_other' => 'dyn_pat_relationship_child_other',
        'dyn_pat_phone_caregiver' => 'dyn_pat_phone_caregiver',
        'dyn_pat_phone_owner' => 'dyn_pat_phone_owner',
        'dyn_pat_phone_caregiver_2' => 'dyn_pat_phone_caregiver_2',
        'dyn_pat_phone_owner2' => 'dyn_pat_phone_owner2',
        'complete' => 'patient_information_complete',
      ),
      'followup' => 
      array (
        'redcap_event_name' => 'consultation_arm_1',
        'dyn_fup_study_id_consultation' => 'dyn_fup_study_id_consultation',
        'dyn_fup_study_id_patient' => 'dyn_fup_study_id_patient',
        'dyn_fup_firstname' => 'dyn_fup_firstname',
        'dyn_fup_middlename' => 'dyn_fup_middlename',
        'dyn_fup_lastname' => 'dyn_fup_lastname',
        'dyn_fup_sex' => 'dyn_fup_sex',
        'dyn_fup_first_name_caregiver' => 'dyn_fup_first_name_caregiver',
        'dyn_fup_last_name_caregiver' => 'dyn_fup_last_name_caregiver',
        'dyn_fup_birth_date' => 'dyn_fup_birth_date',
        'dyn_pat_village' => 'dyn_pat_village',
        'dyn_fup_relationship_child' => 'dyn_fup_relationship_child',
        'dyn_fup_phone_caregiver' => 'dyn_fup_phone_caregiver',
        'dyn_fup_phone_owner' => 'dyn_fup_phone_owner',
        'dyn_fup_phone_caregiver_2' => 'dyn_fup_phone_caregiver_2',
        'dyn_fup_phone_owner2' => 'dyn_fup_phone_owner2',
        'dyn_fup_id_health_facility' => 'dyn_fup_id_health_facility',
        'dyn_fup_date_time_consultation' => 'dyn_fup_date_time_consultation',
        'dyn_fup_group' => 'dyn_fup_group',
        'dyn_fup_sex_caregiver' => 'dyn_fup_sex_caregiver',
        'dyn_fup_consultation_id' => 'dyn_fup_consultation_id',
        'identification_complete' => 'identification_information_complete',
        'dyn_fup_followup_status' => 'dyn_fup_followup_status',
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
      ),
      'medical_case' => 
      array (
        'patient_id' => 'dyn_mc_patient_id',
        'datetime_consultation' => 'dyn_mc_datetime_consultation',
        'datetime_closedAt' => 'dyn_mc_datetime_closed_at',
        'arm' => 'dyn_mc_algorithm_arm',
        'complete' => 'medical_case_complete',
        'hf_id' => 'dyn_mc_id_health_facility',
        'dyn_mc_medalc_question_id' => 'dyn_mc_medalc_question_id',
        'dyn_mc_medalc_question_label' => 'dyn_mc_medalc_question_label',
        'dyn_mc_medalc_answer_id' => 'dyn_mc_medalc_answer_id',
        'dyn_mc_medalc_answer_value' => 'dyn_mc_medalc_answer_value',
        'variables_complete' => 'variables_complete',
        'dyn_mc_medalc_diag_id' => 'dyn_mc_medalc_diag_id',
        'dyn_mc_medal_data_diag_id' => 'dyn_mc_medal_data_diag_id',
        'dyn_mc_medal_data_diag_additional' => 'dyn_mc_medal_data_diag_additional',
        'dyn_mc_medalc_diag_label' => 'dyn_mc_medalc_diag_label',
      ),
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
      'webhook' => 
      array (
        'secret' => NULL,
        'tolerance' => 300,
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/home/vagrant/code/liwi-main-data/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'medal_data_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
    'same_site' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/home/vagrant/code/liwi-main-data/resources/views',
    ),
    'compiled' => '/home/vagrant/code/liwi-main-data/storage/framework/views',
  ),
  'debugbar' => 
  array (
    'enabled' => NULL,
    'except' => 
    array (
      0 => 'telescope*',
      1 => 'horizon*',
    ),
    'storage' => 
    array (
      'enabled' => true,
      'driver' => 'file',
      'path' => '/home/vagrant/code/liwi-main-data/storage/debugbar',
      'connection' => NULL,
      'provider' => '',
      'hostname' => '127.0.0.1',
      'port' => 2304,
    ),
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => 
    array (
      'phpinfo' => true,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => true,
      'auth' => false,
      'gate' => true,
      'session' => true,
      'symfony_request' => true,
      'mail' => true,
      'laravel' => false,
      'events' => false,
      'default_request' => false,
      'logs' => false,
      'files' => false,
      'config' => false,
      'cache' => false,
      'models' => true,
      'livewire' => true,
    ),
    'options' => 
    array (
      'auth' => 
      array (
        'show_name' => true,
      ),
      'db' => 
      array (
        'with_params' => true,
        'backtrace' => true,
        'backtrace_exclude_paths' => 
        array (
        ),
        'timeline' => false,
        'duration_background' => true,
        'explain' => 
        array (
          'enabled' => false,
          'types' => 
          array (
            0 => 'SELECT',
          ),
        ),
        'hints' => false,
        'show_copy' => false,
      ),
      'mail' => 
      array (
        'full_log' => false,
      ),
      'views' => 
      array (
        'timeline' => false,
        'data' => false,
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
      'cache' => 
      array (
        'values' => true,
      ),
    ),
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_domain' => NULL,
    'theme' => 'auto',
    'debug_backtrace_limit' => 50,
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => 
    array (
      'font_dir' => '/home/vagrant/code/liwi-main-data/storage/fonts/',
      'font_cache' => '/home/vagrant/code/liwi-main-data/storage/fonts/',
      'temp_dir' => '/tmp',
      'chroot' => '/home/vagrant/code/liwi-main-data',
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_font' => 'serif',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => true,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => false,
    ),
  ),
  'passport' => 
  array (
    'private_key' => NULL,
    'public_key' => NULL,
    'client_uuids' => false,
    'personal_access_client' => 
    array (
      'id' => NULL,
      'secret' => NULL,
    ),
    'storage' => 
    array (
      'database' => 
      array (
        'connection' => 'postgres',
      ),
    ),
  ),
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'pre_calculate_formulas' => false,
      'strict_null_comparison' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'ignore_empty' => false,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'escape_character' => '\\',
        'contiguous' => false,
        'input_encoding' => 'UTF-8',
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
    'value_binder' => 
    array (
      'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
    ),
    'cache' => 
    array (
      'driver' => 'memory',
      'batch' => 
      array (
        'memory_limit' => 60000,
      ),
      'illuminate' => 
      array (
        'store' => NULL,
      ),
    ),
    'transactions' => 
    array (
      'handler' => 'db',
    ),
    'temporary_files' => 
    array (
      'local_path' => '/home/vagrant/code/liwi-main-data/storage/framework/laravel-excel',
      'remote_disk' => NULL,
      'remote_prefix' => NULL,
      'force_resync_remote' => NULL,
    ),
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'multi_term' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
      'starts_with' => false,
    ),
    'index_column' => 'DT_RowIndex',
    'engines' => 
    array (
      'eloquent' => 'Yajra\\DataTables\\EloquentDataTable',
      'query' => 'Yajra\\DataTables\\QueryDataTable',
      'collection' => 'Yajra\\DataTables\\CollectionDataTable',
      'resource' => 'Yajra\\DataTables\\ApiResourceDataTable',
    ),
    'builders' => 
    array (
    ),
    'nulls_last_sql' => ':column :direction NULLS LAST',
    'error' => NULL,
    'columns' => 
    array (
      'excess' => 
      array (
        0 => 'rn',
        1 => 'row_num',
      ),
      'escape' => '*',
      'raw' => 
      array (
        0 => 'action',
      ),
      'blacklist' => 
      array (
        0 => 'password',
        1 => 'remember_token',
      ),
      'whitelist' => '*',
    ),
    'json' => 
    array (
      'header' => 
      array (
      ),
      'options' => 0,
    ),
  ),
  'trustedproxy' => 
  array (
    'proxies' => NULL,
    'headers' => 30,
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
