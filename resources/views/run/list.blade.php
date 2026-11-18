<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tanseeq Badminton - Registrations List (Admin)</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #f5f5f5;
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
        }
        .container {
            max-width: 100%;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 { color: #0a1628; margin-bottom: 15px; font-size: 20px; }
        .action-buttons {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .btn {
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-size: 14px;
            min-height: 44px;
            text-align: center;
            flex: 1 1 auto;
            min-width: 120px;
        }
        .btn-primary { background-color: #0a1628; color: #b8e000; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-edit { background-color: #ffc107; color: #000; padding: 5px 10px; font-size: 12px; }
        .table-wrapper {
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 10px;
            max-height: calc(100vh - 250px);
            position: relative;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1100px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            white-space: nowrap;
        }
        th {
            background-color: #0a1628;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .count { margin-bottom: 15px; font-size: 16px; font-weight: bold; }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        @media (max-width: 576px) {
            body { padding: 5px; font-size: 14px; }
            .action-buttons { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tanseeq Badminton Championship - All Registrations (Admin)</h2>
        
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif
        
        @if(isset($isSuperAdmin) && !$isSuperAdmin)
            <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #ffeaa7;">
                <strong>ℹ️ Info:</strong> You are logged in as a regular admin. Only super admins can create new admin accounts.
            </div>
        @endif
        
        <div class="count">Total Registrations: {{ $registrations->count() }}</div>
        
        <div class="action-buttons">
            <a href="/tanseeq-run" class="btn btn-primary">Create New Registration</a>
            <a href="/tanseeq-run/export" class="btn btn-success">Download All CSV</a>
            @if(isset($isSuperAdmin) && $isSuperAdmin)
                <a href="{{ route('admin.register') }}" class="btn" style="background-color: #6c757d; color: white;">Create Admin Account</a>
            @endif
            <a href="{{ route('admin.logout') }}" class="btn" style="background-color: #dc3545; color: white;">Logout</a>
        </div>
       
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Registration ID</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>DOB</th>
                    <th>Contact</th>
                    <th>Trial Avail.</th>
                    <th>Trial Transport</th>
                    <th>Trial Pickup</th>
                    <th>Tournament Avail.</th>
                    <th>Tournament Transport</th>
                    <th>Tournament Pickup</th>
                    <th>Attendance</th>
                    <th>Registered At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r->registration_id ?? 'N/A' }}</td>
                    <td>{{ $r->employee_id }}</td>
                    <td>{{ $r->name }}</td>
                    <td>{{ $r->company }}</td>
                    <td>{{ $r->dob ? \Illuminate\Support\Carbon::parse($r->dob)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $r->contact_number }}</td>
                    <td>{{ $r->trial_availability ?? '-' }}</td>
                    <td>{{ $r->trial_transportation ?? '-' }}</td>
                    <td>{{ $r->trial_pickup_location ?? '-' }}</td>
                    <td>{{ $r->tournament_availability ?? '-' }}</td>
                    <td>{{ $r->tournament_transportation ?? '-' }}</td>
                    <td>{{ $r->tournament_pickup_location ?? '-' }}</td>
                    <td>
                        @if($r->attendance_status === 'present')
                            <span style="color: #28a745; font-weight: bold;">✓ Present</span>
                        @else
                            <span style="color: #6c757d;">Pending</span>
                        @endif
                    </td>
                    <td>{{ $r->created_at ? $r->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('registrations.edit', $r->id) }}" class="btn btn-edit">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="16" style="text-align: center; padding: 20px; color: #999;">
                        No registrations found yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
