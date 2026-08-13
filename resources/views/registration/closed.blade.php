@extends('layouts.app')

@section('content')
<div class="card">
    <h3>Registration closed</h3>
    <p style="margin:12px 0 0;color:#61707c;line-height:1.5">
        Player registration for TIPL Season 6 closed
        @if($closesAt)
            on {{ $closesAt->format('l, j F Y \a\t g:i A') }} (Dubai time).
        @else
            .
        @endif
        This link is no longer accepting new registrations.
    </p>
</div>
@endsection
