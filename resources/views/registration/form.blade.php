@extends('layouts.app')

@section('content')
<div class="card">
    <form method="POST" action="{{ route('register.submit', ['token' => $link->token]) }}">
        @csrf
        <div class="row">
            <div class="full">
                <label for="full_name">Full Name *</label>
                <input id="full_name" name="full_name" value="{{ old('full_name') }}" required placeholder="Enter full name" />
            </div>

            <div>
                <label for="date_of_birth">Date of Birth *</label>
                <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required />
            </div>

            <div>
                <label for="company">Company *</label>
                <select id="company" name="company" required>
                    @foreach($companies as $c)
                        <option value="{{ $c }}" {{ old('company') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="employee_id">Employee ID *</label>
                <input id="employee_id" name="employee_id" value="{{ old('employee_id') }}" required pattern="[0-9]{5}" minlength="5" maxlength="5" placeholder="5 digits" />
            </div>

            <div>
                <label for="designation">Designation</label>
                <input id="designation" name="designation" value="{{ old('designation') }}" />
            </div>

            <div>
                <label for="mobile_number">Mobile Number *</label>
                <input id="mobile_number" name="mobile_number" type="tel" value="{{ old('mobile_number', '+971') }}" required pattern="^\+971[0-9]{9}$" placeholder="+971501234567" maxlength="13" />
            </div>

            <div>
                <label for="email">Email *</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" />
            </div>

            <div>
                <label for="cric_contact_no">Cric Heroes Contact No. *</label>
                <input id="cric_contact_no" name="cric_contact_no" type="tel" value="{{ old('cric_contact_no', '+971') }}" required pattern="^\+971[0-9]{9}$" placeholder="+971501234567" maxlength="13" />
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
                <select id="previous_team" name="previous_team">
                    <option value="">Select previous TIPL team</option>
                    <option value="SNS United" {{ old('previous_team') == 'SNS United' ? 'selected' : '' }}>SNS United</option>
                    <option value="Proscape Panthers" {{ old('previous_team') == 'Proscape Panthers' ? 'selected' : '' }}>Proscape Panthers</option>
                    <option value="Tanseeq Falcons" {{ old('previous_team') == 'Tanseeq Falcons' ? 'selected' : '' }}>Tanseeq Falcons</option>
                    <option value="FM Titans" {{ old('previous_team') == 'FM Titans' ? 'selected' : '' }}>FM Titans</option>
                    <option value="WIM Warriors" {{ old('previous_team') == 'WIM Warriors' ? 'selected' : '' }}>WIM Warriors</option>
                    <option value="Transmech Tuskers" {{ old('previous_team') == 'Transmech Tuskers' ? 'selected' : '' }}>Transmech Tuskers</option>
                    <option value="Metaline Steel Hawks" {{ old('previous_team') == 'Metaline Steel Hawks' ? 'selected' : '' }}>Metaline Steel Hawks</option>
                    <option value="Tigers of Proscape" {{ old('previous_team') == 'Tigers of Proscape' ? 'selected' : '' }}>Tigers of Proscape</option>
                </select>
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

            <div class="full">
                <label for="transport_type">Company Transport</label>
                <select id="transport_type" name="transport_type">
                    <option value="">Select option</option>
                    <option value="Self" {{ old('transport_type') == 'Self' ? 'selected' : '' }}>Self</option>
                    <option value="Company" {{ old('transport_type') == 'Company' ? 'selected' : '' }}>Company</option>
                </select>
            </div>

            <div class="full" style="text-align:right">
                <button class="primary" type="submit">Submit Registration</button>
            </div>
        </div>
    </form>
</div>
@endsection
