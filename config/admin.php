<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Super Admin Emails
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of emails that can create other admin accounts.
    | IMPORTANT: use config('admin.super_admin_emails') — not env() — so this
    | still works when Laravel Cloud runs config:cache.
    |
    */
    'super_admin_emails' => env('SUPER_ADMIN_EMAILS', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Admin Password (bootstrap only)
    |--------------------------------------------------------------------------
    */
    'password' => env('ADMIN_PASSWORD', 'admin123'),
];
