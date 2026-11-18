<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Edit Registration - Tanseeq Badminton Championship</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-size: 16px; -webkit-text-size-adjust: 100%; background: #f5f5f5; }
        .form-control, .form-select { font-size: 16px; padding: 12px; height: auto; }
        .form-label { font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .btn { font-size: 16px; padding: 12px; font-weight: 500; min-height: 44px; }
        .pickup-field { display: none; }
        .pickup-field.show { display: block; }
        .form-section {
            background: #f7f9fc;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            border-left: 4px solid #b8e000;
        }
        .section-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="bg-light">
<div class="container-fluid px-2 px-md-3 mt-2 mt-md-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title mb-2">Edit Registration</h3>
                    <p class="text-muted">Registration ID: <strong>{{ $registration->registration_id }}</strong></p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('registrations.update', $registration->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-section">
                            <div class="section-title">Player Information</div>

                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" class="form-control" id="employee_id"
                                       value="{{ $registration->employee_id }}" disabled>
                                <small class="text-muted">Employee ID cannot be changed</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $registration->email) }}" required autocomplete="email">
                            </div>

                            <div class="mb-3">
                                <label for="company" class="form-label">Company <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="company" name="company" required>
                                    @foreach(['Proscape LLC','Stones & Slates LLC','Tanseeq','Metaline','Timbertech','Proscape Golf','Proscape FM','Acacia'] as $company)
                                        <option value="{{ $company }}" {{ $registration->company == $company ? 'selected' : '' }}>{{ $company }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Employee Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $registration->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth (DOB) <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob"
                                       value="{{ old('dob', $registration->dob ? \Illuminate\Support\Carbon::parse($registration->dob)->format('Y-m-d') : '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_local" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">971</span>
                                    <input type="tel" class="form-control" id="contact_local" inputmode="numeric"
                                           maxlength="9" pattern="[0-9]{9}"
                                           value="{{ old('contact_number', $registration->contact_number) && str_starts_with(old('contact_number', $registration->contact_number), '971') ? substr(old('contact_number', $registration->contact_number), 3) : '' }}"
                                           placeholder="501234567" required>
                                </div>
                                <input type="hidden" id="contact_number" name="contact_number"
                                       value="{{ old('contact_number', $registration->contact_number) }}">
                                <small class="text-muted">Enter 9 digits after 971 (example: 971501234567)</small>
                            </div>

                            @php
                                $hasVacation = old('has_planned_vacation', $registration->has_planned_vacation
                                    ?? ($registration->planned_vacation || $registration->planned_vacation_from ? 'Yes' : ''));
                                $vacationFrom = old('planned_vacation_from', $registration->planned_vacation_from
                                    ? \Illuminate\Support\Carbon::parse($registration->planned_vacation_from)->format('Y-m-d')
                                    : ($registration->planned_vacation ? \Illuminate\Support\Carbon::parse($registration->planned_vacation)->format('Y-m-d') : ''));
                                $vacationTo = old('planned_vacation_to', $registration->planned_vacation_to
                                    ? \Illuminate\Support\Carbon::parse($registration->planned_vacation_to)->format('Y-m-d')
                                    : ($registration->planned_vacation ? \Illuminate\Support\Carbon::parse($registration->planned_vacation)->format('Y-m-d') : ''));
                            @endphp
                            <div class="mb-3">
                                <label for="has_planned_vacation" class="form-label">Planned Vacation <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="has_planned_vacation" name="has_planned_vacation" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ $hasVacation == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ $hasVacation == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3 pickup-field {{ $hasVacation == 'Yes' ? 'show' : '' }}" id="vacation_dates_wrap">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="planned_vacation_from" class="form-label">Vacation From <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="planned_vacation_from" name="planned_vacation_from"
                                               value="{{ $vacationFrom }}"
                                               min="{{ now()->timezone(config('app.timezone', 'Asia/Dubai'))->toDateString() }}"
                                               max="2027-01-31">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="planned_vacation_to" class="form-label">Vacation To <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="planned_vacation_to" name="planned_vacation_to"
                                               value="{{ $vacationTo }}"
                                               min="{{ now()->timezone(config('app.timezone', 'Asia/Dubai'))->toDateString() }}"
                                               max="2027-01-31">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Trial Details</div>

                            <div class="mb-3">
                                <label for="trial_availability" class="form-label">Trial Availability <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="trial_availability" name="trial_availability" required>
                                    <option value="Yes" {{ old('trial_availability', $registration->trial_availability) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('trial_availability', $registration->trial_availability) == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="trial_transportation" class="form-label">Trial Transportation <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="trial_transportation" name="trial_transportation" required>
                                    <option value="Yes" {{ old('trial_transportation', $registration->trial_transportation) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('trial_transportation', $registration->trial_transportation) == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3 pickup-field {{ old('trial_transportation', $registration->trial_transportation) == 'Yes' ? 'show' : '' }}" id="trial_pickup_wrap">
                                <label for="trial_pickup_location" class="form-label">Trial Pickup Location</label>
                                <select class="form-control form-select" id="trial_pickup_location" name="trial_pickup_location">
                                    <option value="">Select Location</option>
                                    @foreach(['ADCB Metro Station','International City','Al Quoz','Rashidiya Metro Station','Etisalat Metro Station','DIP/Jebel Ali'] as $loc)
                                        <option value="{{ $loc }}" {{ old('trial_pickup_location', $registration->trial_pickup_location) == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Tournament Details</div>

                            <div class="mb-3">
                                <label for="tournament_availability" class="form-label">Tournament Availability <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="tournament_availability" name="tournament_availability" required>
                                    <option value="Yes" {{ old('tournament_availability', $registration->tournament_availability) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('tournament_availability', $registration->tournament_availability) == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tournament_transportation" class="form-label">Tournament Transportation <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="tournament_transportation" name="tournament_transportation" required>
                                    <option value="Yes" {{ old('tournament_transportation', $registration->tournament_transportation) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('tournament_transportation', $registration->tournament_transportation) == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3 pickup-field {{ old('tournament_transportation', $registration->tournament_transportation) == 'Yes' ? 'show' : '' }}" id="tournament_pickup_wrap">
                                <label for="tournament_pickup_location" class="form-label">Tournament Pickup Location</label>
                                <select class="form-control form-select" id="tournament_pickup_location" name="tournament_pickup_location">
                                    <option value="">Select Location</option>
                                    @foreach(['ADCB Metro Station','International City','Al Quoz','Rashidiya Metro Station','Etisalat Metro Station','DIP/Jebel Ali'] as $loc)
                                        <option value="{{ $loc }}" {{ old('tournament_pickup_location', $registration->tournament_pickup_location) == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between gap-2">
                            <a href="{{ route('registrations.list') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Registration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePickup(selectId, wrapId, locationId) {
        const select = document.getElementById(selectId);
        const wrap = document.getElementById(wrapId);
        const location = document.getElementById(locationId);
        if (!select || !wrap || !location) return;
        const update = () => {
            if (select.value === 'Yes') {
                wrap.classList.add('show');
                location.required = true;
            } else {
                wrap.classList.remove('show');
                location.required = false;
                location.value = '';
            }
        };
        select.addEventListener('change', update);
        update();
    }
    togglePickup('trial_transportation', 'trial_pickup_wrap', 'trial_pickup_location');
    togglePickup('tournament_transportation', 'tournament_pickup_wrap', 'tournament_pickup_location');

    (function toggleVacationDates() {
        const select = document.getElementById('has_planned_vacation');
        const wrap = document.getElementById('vacation_dates_wrap');
        const from = document.getElementById('planned_vacation_from');
        const to = document.getElementById('planned_vacation_to');
        if (!select || !wrap || !from || !to) return;

        const update = () => {
            if (select.value === 'Yes') {
                wrap.classList.add('show');
                from.required = true;
                to.required = true;
            } else {
                wrap.classList.remove('show');
                from.required = false;
                to.required = false;
                from.value = '';
                to.value = '';
            }
        };

        select.addEventListener('change', update);
        from.addEventListener('change', () => {
            if (from.value) {
                to.min = from.value;
                if (to.value && to.value < from.value) {
                    to.value = from.value;
                }
            }
        });
        update();
    })();

    const contactLocal = document.getElementById('contact_local');
    const contactHidden = document.getElementById('contact_number');
    function syncContactNumber() {
        if (!contactLocal || !contactHidden) return;
        const digits = contactLocal.value.replace(/\D/g, '').slice(0, 9);
        contactLocal.value = digits;
        contactHidden.value = digits.length ? ('971' + digits) : '';
    }
    if (contactLocal) {
        contactLocal.addEventListener('input', syncContactNumber);
        syncContactNumber();
    }
</script>
</body>
</html>
