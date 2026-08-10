@extends('layouts.app')

@section('content')
@php
    $selectedId = $selectedLinkId ?? request('link_id');
    $exportQuery = $selectedId ? '?link_id='.$selectedId : '';
@endphp

<div class="registrations-page">
    <div class="dashboard-heading">
        <div>
            <p class="eyebrow">ADMIN DASHBOARD</p>
            <h1>Player registrations</h1>
            <p class="muted">All submitted registration data is shown below.</p>
        </div>
        <div class="heading-actions">
            <a class="button button-secondary" href="{{ url('/admin/form-links') }}">Manage form links</a>
            <a class="button button-primary" href="{{ url('/admin/registrations/export-xlsx'.$exportQuery) }}">Download Excel</a>
        </div>
    </div>

    <div class="stats">
        <div class="stat-card"><span>Registrations shown</span><strong>{{ $registrations->count() }}</strong></div>
        <div class="stat-card"><span>Form links</span><strong>{{ $links->count() }}</strong></div>
        <div class="stat-card"><span>Company transport</span><strong>{{ $registrations->where('transport_type', 'Company')->count() }}</strong></div>
    </div>

    <div class="data-card">
        <div class="toolbar">
            <form method="GET" action="{{ url('/admin/registrations') }}" class="filter-form">
                <label for="link_id">Registration link</label>
                <select name="link_id" id="link_id" onchange="this.form.submit()">
                    <option value="">All form links</option>
                    @foreach($links as $link)
                        <option value="{{ $link->id }}" {{ (string) $selectedId === (string) $link->id ? 'selected' : '' }}>
                            #{{ $link->id }} — {{ $link->name ?: $link->token }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="toolbar-actions">
                <input id="registration-search" type="search" placeholder="Search registrations…" aria-label="Search registrations">
                <a class="text-link" href="{{ url('/admin/registrations/export'.$exportQuery) }}">CSV</a>
                <a class="text-link" href="{{ url('/admin/registrations/export-xlsx'.$exportQuery) }}">Excel</a>
            </div>
        </div>

        @if(!empty($selectedLink))
            <div class="filter-note">Showing registrations for <strong>{{ $selectedLink->name ?: $selectedLink->token }}</strong>.</div>
        @endif

        <div class="table-wrap">
            <table class="registrations-table">
                <thead>
                    <tr>
                        <th>#</th><th>form_link_id</th><th>full_name</th><th>date_of_birth</th><th>company</th><th>employee_id</th><th>designation</th><th>mobile_number</th><th>email</th><th>cric_contact_no</th><th>cric_id_name</th><th>playing_role</th><th>previous_team</th><th>availability_from</th><th>availability_to</th><th>availability_none</th><th>current_location</th><th>transport_type</th><th>company_transport_required</th><th>created_at</th><th>updated_at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->id }}</td><td>{{ $registration->form_link_id }}</td><td>{{ $registration->full_name }}</td><td>{{ optional($registration->date_of_birth)->format('Y-m-d') }}</td><td>{{ $registration->company }}</td><td>{{ $registration->employee_id }}</td><td>{{ $registration->designation }}</td><td>{{ $registration->mobile_number }}</td><td>{{ $registration->email }}</td><td>{{ $registration->cric_contact_no }}</td><td>{{ $registration->cric_id_name }}</td><td>{{ $registration->playing_role }}</td><td>{{ $registration->previous_team }}</td><td>{{ optional($registration->availability_from)->format('Y-m-d') }}</td><td>{{ optional($registration->availability_to)->format('Y-m-d') }}</td><td>{{ $registration->availability_none ? 1 : 0 }}</td><td>{{ $registration->current_location }}</td><td>{{ $registration->transport_type }}</td><td>{{ $registration->company_transport_required ? 1 : 0 }}</td><td>{{ optional($registration->created_at)->format('Y-m-d H:i:s') }}</td><td>{{ optional($registration->updated_at)->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="21">No registrations have been submitted for this selection yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="table-status" id="table-status">{{ $registrations->count() }} record{{ $registrations->count() === 1 ? '' : 's' }}</p>
    </div>
</div>

<style>
    .registrations-page{max-width:none}.dashboard-heading{display:flex;justify-content:space-between;gap:24px;align-items:flex-end;margin:10px 0 22px}.eyebrow{color:#0d4a6d;font-size:11px;font-weight:800;letter-spacing:.12em;margin:0 0 5px}.dashboard-heading h1{font-size:28px;margin:0;color:#172b3a}.muted{color:#61707c;margin:6px 0 0}.heading-actions,.toolbar-actions{display:flex;gap:9px;align-items:center}.button{display:inline-block;text-decoration:none;border-radius:5px;padding:10px 14px;font-size:14px;font-weight:700;white-space:nowrap}.button-primary{background:#0d4a6d;color:#fff}.button-secondary{background:#fff;color:#0d4a6d;border:1px solid #c8d6df}.stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:18px}.stat-card{background:#fff;border:1px solid #e0e8ed;border-radius:7px;padding:15px 18px}.stat-card span{display:block;color:#667780;font-size:13px}.stat-card strong{color:#0d4a6d;font-size:25px;display:block;margin-top:4px}.data-card{background:#fff;border:1px solid #dfe8ed;border-radius:7px;overflow:hidden}.toolbar{padding:14px 16px;border-bottom:1px solid #e4ebef;display:flex;justify-content:space-between;gap:16px;align-items:end}.filter-form label{font-size:12px;font-weight:700;margin-bottom:4px}.filter-form select{min-width:240px;padding:8px 10px}.toolbar input{width:250px;padding:8px 10px}.text-link{color:#0d4a6d;font-size:14px;font-weight:700;text-decoration:none}.filter-note{padding:10px 16px;background:#f1f8fb;color:#355463;font-size:13px}.table-wrap{overflow:auto;max-height:65vh}.registrations-table{width:100%;min-width:2700px;border-collapse:separate;border-spacing:0;font-size:13px}.registrations-table th{position:sticky;top:0;background:#ecf3f6;color:#274556;text-align:left;font-size:12px;white-space:nowrap;z-index:1}.registrations-table th,.registrations-table td{padding:11px 12px;border-bottom:1px solid #e7edf0;border-right:1px solid #edf1f3;white-space:nowrap}.registrations-table tbody tr:hover{background:#f7fbfc}.registrations-table td:nth-child(3),.registrations-table td:nth-child(5),.registrations-table td:nth-child(8),.registrations-table td:nth-child(9){color:#08731b}.empty-row td{text-align:center;color:#6b7c85;padding:32px}.table-status{margin:0;padding:11px 16px;color:#64747d;font-size:13px;border-top:1px solid #e7edf0}@media(max-width:720px){.dashboard-heading,.toolbar{align-items:stretch;flex-direction:column}.heading-actions{flex-wrap:wrap}.stats{grid-template-columns:1fr}.toolbar-actions{flex-wrap:wrap}.toolbar input,.filter-form select{width:100%;min-width:0}}
</style>
<script>
    document.getElementById('registration-search').addEventListener('input', function () {
        const term = this.value.toLowerCase().trim();
        const rows = Array.from(document.querySelectorAll('.registrations-table tbody tr:not(.empty-row)'));
        let visible = 0;
        rows.forEach(function (row) {
            const matches = row.textContent.toLowerCase().includes(term);
            row.hidden = !matches;
            if (matches) visible++;
        });
        document.getElementById('table-status').textContent = visible + ' record' + (visible === 1 ? '' : 's') + (term ? ' match your search' : '');
    });
</script>
@endsection
