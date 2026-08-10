@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrations</h2>

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
                <th>Email</th>
                <th>Mobile</th>
                <th>Company</th>
                <th>Role</th>
                <th>Transport</th>
                <th>Link</th>
                <th>Submitted At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->full_name }}</td>
                    <td>{{ $r->email }}</td>
                    <td>{{ $r->mobile_number }}</td>
                    <td>{{ $r->company }}</td>
                    <td>{{ $r->playing_role }}</td>
                    <td>{{ $r->transport_type ?? ($r->company_transport_required ? 'Company' : 'Self') }}</td>
                    <td>@if($r->formLink)<a href="{{ url('/register/'.$r->formLink->token) }}" target="_blank">open</a>@endif</td>
                    <td>{{ $r->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
