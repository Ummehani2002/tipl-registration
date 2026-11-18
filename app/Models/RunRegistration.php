<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunRegistration extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'designation',
        'company',
        'entity',
        'dob',
        'run_category',
        'contact_number',
        'tshirt_size',
        'trial_availability',
        'trial_transportation',
        'trial_pickup_location',
        'tournament_availability',
        'tournament_transportation',
        'tournament_pickup_location',
        'registration_id',
        'bib_number',
        'attendance_status',
    ];

    protected $casts = [
        'dob' => 'date',
    ];
}
