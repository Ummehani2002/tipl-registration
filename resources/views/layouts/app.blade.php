<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIPL Registration</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root{--accent:#0d4a6d;--muted:#f3f5f7}
        body { font-family: Arial, Helvetica, sans-serif; margin:0; background:#f7f9fb; color:#222 }
        .site-header{background:white;border-bottom:1px solid #e6edf2;padding:12px 16px;display:flex;align-items:center;gap:12px}
        .site-header img{height:48px;width:48px;object-fit:contain}
        .site-title{font-weight:700;color:var(--accent);font-size:18px}
        .site-sub{font-size:12px;color:#666}
        .container{max-width:900px;margin:20px auto;padding:0 12px}
        .card{background:white;padding:18px;border-radius:6px;box-shadow:0 1px 3px rgba(16,24,40,0.04)}
        form .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        label{display:block;font-size:13px;color:#133; margin-bottom:6px}
        input, select, textarea{padding:10px;border:1px solid #d6e2ea;border-radius:4px;font-size:14px;width:100%;box-sizing:border-box}
        .full{grid-column:1/-1}
        button.primary{background:var(--accent);color:white;border:none;padding:10px 16px;border-radius:4px;font-weight:600}
        @media(max-width:600px){
            form .row{grid-template-columns:1fr}
            .site-header img{height:40px;width:40px}
        }
        table{border-collapse:collapse}
    </style>
</head>
<body>
    <header class="site-header">
        <img src="{{ asset(env('LOGO_PATH', 'logo.png')) }}" alt="Logo" onerror="this.style.display='none'" />
        <div>
            <div class="site-title">TIPL SEASON 6</div>
            <div class="site-sub">Player Registration</div>
        </div>
    </header>

    <main class="container">
        @if(session('status'))
            <div style="background:#e6ffed;border:1px solid #b7f0c6;padding:8px;margin-bottom:12px">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
