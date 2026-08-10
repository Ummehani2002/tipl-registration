<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Registrations</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f7f9fb; color: #111; }
        .page { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #ffffff; padding: 16px 20px; border-bottom: 1px solid #ddd; }
        h1 { margin: 0; font-size: 24px; }
        p { margin: 8px 0 20px; color: #555; }
        form { margin-bottom: 16px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; }
        select { padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; min-width: 260px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background: #f1f5f9; font-weight: 700; }
        tbody tr:nth-child(odd) { background: #fafafa; }
        .note { margin: 0 0 20px; color: #666; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>Admin: Registrations</h1>
            <p class="note">Shows all registrations submitted through the public form. Use the filter to view a specific link.</p>
            @if(!empty($errorMessage))
                <p style="color:#a33; margin: 16px 0;">{{ $errorMessage }}</p>
            @endif
        </div>

        <form method="GET" action="{{ url('/admin/registrations') }}">
            <label for="link_id">Filter by link</label>
            <select id="link_id" name="link_id" onchange="this.form.submit()">
                <option value="">All</option>
                @foreach($links as $l)
                    <option value="{{ $l->id }}" {{ request('link_id') == $l->id ? 'selected' : '' }}>{{ $l->id }} - {{ $l->name ?? $l->token }}</option>
                @endforeach
            </select>
        </form>

        <table>
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
                @forelse($registrations as $r)
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
                @empty
                    <tr>
                        <td colspan="17">No registrations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
