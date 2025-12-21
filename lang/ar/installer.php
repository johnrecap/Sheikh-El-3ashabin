<?php


return [
    'title'       => 'مثبت StoreKing',
    'next'        => 'الخطوة التالية',
    'welcome'     => [
        'templateTitle' => 'مرحبا',
        'title'         => 'مثبت StoreKing',
        'message'       => 'معالج التثبيت والإعداد السهل.',
        'next'          => 'التحقق من المتطلبات',
    ],
    'requirement' => [
        'templateTitle' => 'الخطوة 1 | متطلبات الخادم',
        'title'         => 'متطلبات الخادم',
        'next'          => 'فحص الأذونات',
        'version'       => 'الإصدار',
        'required'      => 'مطلوب'
    ],
    'permission'  => [
        'templateTitle'       => 'الخطوة 2 | الأذونات',
        'title'               => 'الأذونات',
        'next'                => 'إعداد الموقع',
        'permission_checking' => 'فحص الأذونات'
    ],
    'site'        => [
        'templateTitle' => 'الخطوة 3 | إعداد الموقع',
        'title'         => 'إعداد الموقع',
        'next'          => 'إعداد قاعدة البيانات',
        'label'         => [
            'app_name' => 'اسم التطبيق',
            'app_url'  => 'عنوان URL للتطبيق',
        ]
    ],
    'database'    => [
        'templateTitle'            => 'الخطوة 4 | إعداد قاعدة البيانات',
        'title'                    => 'إعداد قاعدة البيانات',
        'next'                     => 'إعداد نهائي',
        'fail_message'             => 'تعذر الاتصال بقاعدة البيانات.',
        'fail_mysql_version'       => 'استخدم إصدار MySQL 8.0 أو أحدث.',
        'fail_mariadb_version'     => 'استخدم إصدار MySQL 10.2 أو أحدث.',
        'fail_postgresql_version'  => 'استخدم إصدار MySQL 9.4 أو أحدث.',
        'fail_sqlserver_version'   => 'استخدم إصدار MySQL 2008 أو أحدث.',
        'fail_singlestore_version' => 'استخدم إصدار MySQL 8.1 أو أحدث.',
        'label'                    => [
            'database_connection' => 'اتصال قاعدة البيانات',
            'database_host'       => 'مضيف قاعدة البيانات',
            'database_port'       => 'منفذ قاعدة البيانات',
            'database_name'       => 'اسم قاعدة البيانات',
            'database_username'   => 'اسم مستخدم قاعدة البيانات',
            'database_password'   => 'كلمة مرور قاعدة البيانات',
        ]
    ],
    'final'       => [
        'templateTitle'   => 'الخطوة 6 | الإعداد النهائي',
        'title'           => 'الإعداد النهائي',
        'success_message' => 'تم تثبيت التطبيق بنجاح.',
        'login_info'      => 'معلومات تسجيل الدخول',
        'email'           => 'البريد الإلكتروني',
        'password'        => 'كلمة المرور',
        'email_info'      => 'admin@example.com',
        'password_info'   => '123456',
        'next'            => 'إنهاء',
    ],
    'installed'   => [
        'success_log_message' => 'تم تثبيت Store King بنجاح',
        'update_log_message'  => 'تم تحديث مثبت Store King بنجاح',
    ]
];