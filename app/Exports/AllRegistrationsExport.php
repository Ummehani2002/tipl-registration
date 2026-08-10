<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllRegistrationsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Registration::orderBy('created_at')
            ->get([
                'id', 'form_link_id', 'full_name', 'date_of_birth', 'company', 'employee_id', 'designation', 'mobile_number', 'email', 'cric_contact_no', 'cric_id_name', 'playing_role', 'previous_team', 'availability_from', 'availability_to', 'availability_none', 'current_location', 'transport_type', 'company_transport_required', 'created_at', 'updated_at'
            ]);
    }

    public function headings(): array
    {
        return [
            'id', 'form_link_id', 'full_name', 'date_of_birth', 'company', 'employee_id', 'designation', 'mobile_number', 'email', 'cric_contact_no', 'cric_id_name', 'playing_role', 'previous_team', 'availability_from', 'availability_to', 'availability_none', 'current_location', 'transport_type', 'company_transport_required', 'created_at', 'updated_at'
        ];
    }
}
