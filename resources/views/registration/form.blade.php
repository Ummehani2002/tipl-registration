@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin-top:0">Player Registration</h2>
    <form method="POST" action="{{ url('/register/'.$link->token) }}">
        @csrf
        <div class="row">
            <div class="full">
                <label for="full_name">Full Name *</label>
                <input id="full_name" name="full_name" value="{{ old('full_name') }}" required placeholder="Enter full name" />
            </div>

            <div>
                <label for="date_of_birth">Date of Birth</label>
                <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" />
            </div>

            <div>
                <label for="company">Company</label>
                <select id="company" name="company">
                    @foreach($companies as $c)
                        <option value="{{ $c }}" {{ old('company') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="employee_id">Employee ID</label>
                <input id="employee_id" name="employee_id" value="{{ old('employee_id') }}" />
            </div>

            <div>
                <label for="designation">Designation</label>
                <input id="designation" name="designation" value="{{ old('designation') }}" />
            </div>

            <div>
                <label for="mobile_number">Mobile Number</label>
                <input id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="e.g. +971501234567" />
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" />
            </div>

            <div>
                <label for="cric_contact_no">Cric Heroes Contact No.</label>
                <input id="cric_contact_no" name="cric_contact_no" value="{{ old('cric_contact_no') }}" />
            </div>

            <div>
                <label for="cric_id_name">Cric Heroes ID Name</label>
                <input id="cric_id_name" name="cric_id_name" value="{{ old('cric_id_name') }}" />
            </div>

            <div>
                <label for="playing_role">Playing Role</label>
                <select id="playing_role" name="playing_role">
                    @foreach($roles as $r)
                        <option value="{{ $r }}" {{ old('playing_role') == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="previous_team">Previous TIPL Team</label>
                <input id="previous_team" name="previous_team" value="{{ old('previous_team') }}" />
            </div>

            <div class="full">
                <label>Planned Vacation</label>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    <input type="date" name="availability_from" value="{{ old('availability_from') }}" style="max-width:220px" />
                    <input type="date" name="availability_to" value="{{ old('availability_to') }}" style="max-width:220px" />
                    <label style="display:flex;align-items:center"><input type="checkbox" name="availability_none" {{ old('availability_none') ? 'checked' : '' }} style="margin-right:6px" /> None</label>
                </div>
            </div>

            <div>
                <label for="current_location">Current Location</label>
                <input id="current_location" name="current_location" value="{{ old('current_location') }}" />
            </div>

            <div>
                <label>Company Transport Required for Trials</label>
                <label style="display:inline-block;margin-right:12px"><input type="radio" name="company_transport_required" value="1" {{ old('company_transport_required') == '1' ? 'checked' : '' }} /> Yes</label>
                <label style="display:inline-block"><input type="radio" name="company_transport_required" value="0" {{ old('company_transport_required') === '0' ? 'checked' : '' }} /> No</label>
            </div>

            <div class="full" style="text-align:right">
                <button class="primary" type="submit">Submit Registration</button>
            </div>
        </div>
    </form>
</div>
@endsection
