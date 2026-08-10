<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegistrationsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $linkId;

    public function __construct($linkId)
    {
        $this->linkId = $linkId;
    }

    public function collection()
    {
        return Registration::where('form_link_id', $this->linkId)
            ->orderBy('created_at')
            ->get([
                'id', 'full_name', 'date_of_birth', 'company', 'employee_id', 'designation', 'mobile_number', 'email', 'cric_contact_no', 'cric_id_name', 'playing_role', 'previous_team', 'availability_from', 'availability_to', 'availability_none', 'current_location', 'company_transport_required', 'created_at'
            ]);
    }

    public function headings(): array
    {
        return [
            'ID','Full Name','Date of Birth','Company','Employee ID','Designation','Mobile Number','Email','Cric Contact No','Cric ID Name','Playing Role','Previous Team','Availability From','Availability To','Availability None','Current Location','Company Transport Required','Submitted At'
        ];
    }
}
