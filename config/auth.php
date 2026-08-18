<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    | "web" guard = Job Seeker (public facing default guard)
    */
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards — one per user type
    |--------------------------------------------------------------------------
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users', // Job Seekers
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'company' => [
            'driver' => 'session',
            'provider' => 'companies',
        ],

        'employee' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],

        // API guards (Sanctum) — one token guard per actor, reuses same providers
        'admin-api' => [
            'driver' => 'sanctum',
            'provider' => 'admins',
        ],
        'company-api' => [
            'driver' => 'sanctum',
            'provider' => 'companies',
        ],
        'seeker-api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
        'employee-api' => [
            'driver' => 'sanctum',
            'provider' => 'employees',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers — one per table/model
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Job Seeker
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'companies' => [
            'driver' => 'eloquent',
            'model' => App\Models\Company::class,
        ],
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords — one broker per guard type
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'admin_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'companies' => [
            'provider' => 'companies',
            'table' => 'company_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'employees' => [
            'provider' => 'employees',
            'table' => 'employee_password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
