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
        'has_planned_vacation',
        'planned_vacation_from',
        'planned_vacation_to',
        'planned_vacation',
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
        'planned_vacation' => 'date',
        'planned_vacation_from' => 'date',
        'planned_vacation_to' => 'date',
    ];
}
