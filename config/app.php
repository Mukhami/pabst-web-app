<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Imanage Details
    |--------------------------------------------------------------------------
    |
    | This section contains details to be used when posting to imanage
    |
    */
    'imanage_auth' => [
        'email' => env('MKENGA_API_USER', null),
        'password' => env('MKENGA_API_PASSWORD', null),
    ],

    'default_library' => env('MKENGA_API_DEFAULT_LIBRARY', 63), //{"id": 63, "tenant_id": 28,"imanage_library_id": "PPGDMS01","is_classic_client_compatible": 0,"type": "worksite","created_at": "2023-10-21 08:41:08","updated_at": "2024-07-08 12:13:44"}
    'default_practice_area' => env('MKENGA_API_DEFAULT_PRACTICE_AREA', 251),
    'default_template' => env('MKENGA_API_DEFAULT_TEMPLATE', 472),

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],


    /*
    |--------------------------------------------------------------------------
    | System Permissions
    |--------------------------------------------------------------------------
    |
    | Here is a list of all the permissions used within the system
    |
    |
    */
    'permissions' => [
        //users module
        'users.users.browse',
        'users.users.read',
        'users.users.edit',
        'users.users.add',
        'users.users.delete',

        'users.roles.browse',
        'users.roles.read',
        'users.roles.edit',
        'users.roles.add',
        'users.roles.delete',

        //matter types module
        'matter-types.matter-types.browse',
        'matter-types.matter-types.read',
        'matter-types.matter-types.edit',
        'matter-types.matter-types.add',
        'matter-types.matter-types.delete',

        'matter-types.matter-subtypes.browse',
        'matter-types.matter-subtypes.read',
        'matter-types.matter-subtypes.edit',
        'matter-types.matter-subtypes.add',
        'matter-types.matter-subtypes.delete',

        //matter requests module
        'matters.requests.browse',
        'matters.requests.read',
        'matters.requests.edit',
        'matters.requests.add',
        'matters.requests.delete',

        'matters.requests.approve'
    ]

];
