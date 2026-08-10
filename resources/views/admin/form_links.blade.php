<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Form Links</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f7f9fb; color: #111; }
        .page { max-width: 960px; margin: 0 auto; padding: 20px; }
        .header { background: #fff; padding: 18px 20px; border-bottom: 1px solid #ddd; }
        h1, h2 { margin: 0 0 12px; }
        label { display: block; margin: 12px 0 6px; font-weight: 600; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 10px; padding: 10px 16px; background: #0d4a6d; color: white; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f1f5f9; }
        a { color: #0d4a6d; text-decoration: none; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <h1>Admin: Form Links</h1>
            @if(!empty($errorMessage))
                <p style="color:#a33;">{{ $errorMessage }}</p>
            @endif
        </div>

        @if(session('status'))
            <div style="background:#e6ffed;border:1px solid #b7f0c6;padding:8px;margin:12px 0">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ url('/admin/form-links') }}">
            @csrf
            <label>Name (optional)</label>
            <input name="name" />
            <button type="submit">Create Link</button>
        </form>

        <p><a href="{{ url('/admin/registrations') }}">View all registrations</a></p>

        <h2>Existing Links</h2>
        <table>
        <thead><tr><th>ID</th><th>Name</th><th>Link</th><th>Export</th><th>Registrations</th></tr></thead>
        <tbody>
            @forelse($links as $link)
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
            @empty
                <tr><td colspan="5">No form links found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
