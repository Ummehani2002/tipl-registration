<?php

namespace App\Support;

use Carbon\Carbon;

class RegistrationDeadline
{
    public static function isClosed(): bool
    {
        $closesAt = config('registration.closes_at');

        if (! $closesAt) {
            return false;
        }

        $timezone = config('registration.timezone', 'Asia/Dubai');

        return Carbon::now($timezone)->gte(Carbon::parse($closesAt, $timezone));
    }

    public static function closesAt(): ?Carbon
    {
        $closesAt = config('registration.closes_at');

        if (! $closesAt) {
            return null;
        }

        $timezone = config('registration.timezone', 'Asia/Dubai');

        return Carbon::parse($closesAt, $timezone);
    }
}
