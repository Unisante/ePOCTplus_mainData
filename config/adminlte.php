<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
     */

    'title' => 'ePOCT+  Main data',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
     */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
     */

    'logo' => '<b>ePOCT+</b>Main data',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    // 'logo_img' => 'public/MedALDataLogo.png',
    'logo_img_class' => 'brand-image-xl',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'ePOCT+ Main data',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-layout
    |
     */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Extra Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-classes
    |
     */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_header' => 'container-fluid',
    'classes_content' => 'container-fluid',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand-md',
    'classes_topnav_container' => 'container-fluid',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#66-sidebar
    |
     */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    'layout_fixed_sidebar' => true,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-control-sidebar-right-sidebar
    |
     */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-urls
    |
     */

    'use_route_url' => false,

    'dashboard_url' => 'home',

    'logout_url' => 'logout',
    // 'logout_url' => false,
    'login_url' => 'login',

    'register_url' => 'register',
    // 'password_reset_url' => false,

    'password_reset_url' => 'password/reset',

    // 'password_email_url' => 'password/email',

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#69-laravel-mix
    |
     */

    'enabled_laravel_mix' => false,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#610-menu
    |
     */

    'menu' => [
        [
            'text' => 'Follow-Up',
            'url' => '/followUp',
            'icon' => 'fas fa-fw fa-check-circle',
            'can' => 'Access_Follow_Up_Panel',
        ],
        [
            'text' => 'Duplicates',
            'icon' => 'fas fa-fw fa-file',
            'can' => 'Access_Duplicates_Panel',
            'submenu' => [
                [
                    'text' => 'Patients duplicates',
                    'url' => '/patients/duplicates',
                    'icon' => 'fas fa-fw fa-user',
                    'can' => 'Access_Duplicates_Panel',
                ],
                [
                    'text' => 'Cases duplicates',
                    'icon' => 'fas fa-fw fa-file',
                    'url' => '/medical-cases/duplicates',
                    'can' => 'Access_Duplicates_Panel',
                ],
            ],
        ],
        [
            'text' => 'Facilities',
            'url' => '/facilities',
            'icon' => 'fas fa-fw fa-cart-plus',
            'can' => 'Access_Facilities_Panel',
        ],
        [
            'text' => 'Health Facilities',
            'url' => '/health-facilities',
            'icon' => 'fas fa-fw fa-hospital',
            'can' => 'Access_Health_Facilities_Panel',
        ],
        [
            'text' => 'Devices',
            'url' => '/devices',
            'icon' => 'fas fa-fw fa-tablet',
            'can' => 'Access_Devices_Panel',
        ],
        [
            'text' => 'Medical Staff',
            'url' => '/medical-staff',
            'icon' => 'fas fa-fw fa-users',
            'can' => 'Access_Medical_Staff_Panel',
        ],
        [
            'text' => 'Patients',
            'url' => '/patients',
            'icon' => 'fas fa-fw fa-list',
            'can' => 'Access_Patients_Panel',
        ],

        [
            'text' => 'Medical Cases',
            'url' => '/medical-cases',
            'icon' => 'fas fa-fw fa-file',
            'can' => 'Access_Medical_Cases_Panel',
        ],

        [
            'text' => 'Diagnoses',
            'url' => '/exports/diagnosis_list',
            'can' => 'Access_Diagnoses_Panel',
        ],
        [
            'text' => 'Drugs',
            'url' => '/exports/drug_list',
            'can' => 'Access_Drugs_Panel',
        ],
        [
            'text' => 'Exports',
            'icon' => 'fas fa-fw fa-clone',
            'can' => 'Access_Export_Panel',
            'url' => '/exports/exportZip',
            // 'submenu' => [
            //   [
            //       'text' => 'Patients',
            //       'url'  => '/export/patients',
            //   ],
            //   [
            //       'text' => 'Medical Cases',
            //       'url'  => '/export/medicalcases',
            //   ],
            //   [
            //     'text'=>'Case Answers',
            //     'url'=>'/export/cases_answers'
            //   ],
            //   [
            //     'text' => 'Answers',
            //     'url'  => '/export/answers',
            //   ],
            //   [
            //       'text' => 'Diagnosis References',
            //       'url'  => '/export/diagnosis_references',
            //   ],
            //   [
            //     'text' => 'Custom Diagnoses',
            //     'url'  => '/export/custom_diagnoses',
            //   ],
            //   [
            //       'text' => 'Drug references',
            //       'url'  => '/export/drug_references',
            //   ],
            //   [
            //     'text' => 'Additional Drugs',
            //     'url'  => '/export/additional_drugs',
            //   ],
            //   [
            //       'text' => 'Management References',
            //       'url'  => '/export/management_references',
            //   ],

            //   [
            //     'text' => 'Diagnoses',
            //     'url'  => '/export/diagnoses',
            //   ],
            //   [
            //       'text' => 'Drugs',
            //       'url'  => '/export/drugs',
            //   ],
            //   [
            //     'text' => 'Formulations',
            //     'url'  => '/export/formulations',
            //   ],
            //   [
            //       'text' => 'Managements',
            //       'url'  => '/export/managements',
            //   ],

            //   [
            //     'text' => 'Nodes',
            //     'url'  => '/export/nodes',
            //   ],
            //   [
            //       'text' => 'Answer Types',
            //       'url'  => '/export/answer_types',
            //   ],
            //   [
            //     'text' => 'Algorithms',
            //     'url'  => '/export/algorithms',
            //   ],
            //   [
            //       'text' => 'Algorithm Versions',
            //       'url'  => '/export/algorithm_versions',
            //   ],

            // //   [
            // //     'text' => 'Drug Results',
            // //     'url'  => '/export/drug_analysis',
            // // ],
            // ],
        ],
        [
            'text' => 'Logs',
            'url' => '/logs',
            'icon' => 'fas fa-fw fa-file',
            'can' => 'See_Logs',
        ],
        [
            'text' => 'Audits',
            'url' => '/audits',
            'icon' => 'fas fa-fw fa-search',
            'can' => 'See_Logs',
        ],
        [
            'text' => 'profile',
            'url' => '/user/profile',
            'icon' => 'fas fa-fw fa-user',
            'can' => 'Access_Profile_Panel',
        ],
        [
            'text' => 'change_password',
            'url' => '/user/password',
            'icon' => 'fas fa-fw fa-lock',
            'can' => 'Access_Reset_Own_Password_Panel',
        ],
        [
            'text' => 'Admin Corner',
            'icon' => 'fas fa-fw fa-cog',
            'can' => 'Access_Admin_Corner_Panel',
            'submenu' => [
                [
                    'text' => 'Users Management',
                    'url' => '/users',
                    'icon' => 'fas fa-fw fa-users',
                ],

                [
                    'text' => 'Manage Roles',
                    'url' => '/roles',
                    'icon' => 'fas fa-fw fa-pencil-alt',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-menu-filters
    |
     */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        // JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-plugins
    |
     */

    'plugins' => [
        [
            'name' => 'Datatables',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        [
            'name' => 'Select2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        [
            'name' => 'Chartjs',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        [
            'name' => 'Sweetalert2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],
];
