<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Registration deadline
    |--------------------------------------------------------------------------
    |
    | Public registration closes at this date/time in the timezone below.
    | Set REGISTRATION_CLOSES_AT to null or an empty string to keep open.
    |
    */

    'closes_at' => env('REGISTRATION_CLOSES_AT', '2026-08-13 16:00:00'),

    'timezone' => env('REGISTRATION_TIMEZONE', 'Asia/Dubai'),

];
