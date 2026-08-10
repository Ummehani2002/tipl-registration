@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Form Links</h2>

    @if(session('status'))
        <div style="background:#dfd;padding:8px">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ url('/admin/form-links') }}">
        @csrf
        <label>Name (optional)</label>
        <input name="name" />
        <button type="submit">Create Link</button>
    </form>

    <p><a href="{{ url('/admin/registrations') }}">View all registrations</a></p>

    <h3>Existing Links</h3>
    <table border="1" cellpadding="6">
        <thead><tr><th>ID</th><th>Name</th><th>Link</th><th>Export</th></tr></thead>
        <tbody>
            @foreach($links as $link)
                <tr>
                    <td>{{ $link->id }}</td>
                    <td>{{ $link->name }}</td>
                    <td><a href="{{ url('/register/'.$link->token) }}" target="_blank">{{ url('/register/'.$link->token) }}</a></td>
                    <td>
                        <a href="{{ url('/admin/form-links/'.$link->id.'/export') }}">Export CSV</a>
                        &nbsp;|&nbsp;
                        <a href="{{ url('/admin/form-links/'.$link->id.'/export-xlsx') }}">Export XLSX</a>
                    </td>
                    <td><a href="{{ url('/admin/registrations?link_id='.$link->id) }}">View Registrations</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
