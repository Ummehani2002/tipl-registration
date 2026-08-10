<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_link_id',
        'full_name',
        'date_of_birth',
        'company',
        'employee_id',
        'designation',
        'mobile_number',
        'email',
        'cric_contact_no',
        'cric_id_name',
        'playing_role',
        'previous_team',
        'availability_from',
        'availability_to',
        'availability_none',
        'current_location',
        'company_transport_required',
        'transport_type',
    ];

    protected $casts = [
        'availability_none' => 'boolean',
        'company_transport_required' => 'boolean',
        'date_of_birth' => 'date',
        'availability_from' => 'date',
        'availability_to' => 'date',
    ];

    public function formLink()
    {
        return $this->belongsTo(FormLink::class);
    }

    // Ensure company is stored in uppercase
    public function setCompanyAttribute($value)
    {
        $this->attributes['company'] = $value ? mb_strtoupper($value) : $value;
    }
}
