@extends('layouts.app')

@section('content')
<div class="container">
        <h2>Registrations (Admin only)</h2>
        <p>Only authenticated admins can access this page. It shows full details of each submitted registration.</p>

        <form method="GET" action="{{ url('/admin/registrations') }}">
            <label>Filter by link</label>
            <select name="link_id" onchange="this.form.submit()">
                <option value="">All</option>
                @foreach($links as $l)
                    <option value="{{ $l->id }}" {{ request('link_id') == $l->id ? 'selected' : '' }}>{{ $l->id }} - {{ $l->name ?? $l->token }}</option>
                @endforeach
            </select>
        </form>

        <table border="1" cellpadding="6" style="width:100%; margin-top:12px">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>DOB</th>
                    <th>Employee ID</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Company</th>
                    <th>Role</th>
                    <th>Previous Team</th>
                    <th>Vacation</th>
                    <th>Location</th>
                    <th>Transport</th>
                    <th>Cric Contact</th>
                    <th>Cric ID</th>
                    <th>Link</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td>{{ $r->full_name }}</td>
                        <td>{{ $r->date_of_birth }}</td>
                        <td>{{ $r->employee_id }}</td>
                        <td>{{ $r->designation }}</td>
                        <td>{{ $r->email }}</td>
                        <td>{{ $r->mobile_number }}</td>
                        <td>{{ $r->company }}</td>
                        <td>{{ $r->playing_role }}</td>
                        <td>{{ $r->previous_team }}</td>
                        <td>
                            @if($r->availability_none)
                                None
                            @else
                                {{ $r->availability_from }} - {{ $r->availability_to }}
                            @endif
                        </td>
                        <td>{{ $r->current_location }}</td>
                        <td>{{ $r->transport_type ?? ($r->company_transport_required ? 'Company' : 'Self') }}</td>
                        <td>{{ $r->cric_contact_no }}</td>
                        <td>{{ $r->cric_id_name }}</td>
                        <td>{{ optional($r->formLink)->name ?? optional($r->formLink)->token ?? $r->form_link_id }}</td>
                        <td>{{ $r->created_at }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
@endsection
