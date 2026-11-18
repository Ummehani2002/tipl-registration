<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tanseeq Badminton Championship - Season 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        :root {
            --navy: #0a1628;
            --lime: #b8e000;
            --blue: #1e90ff;
        }
        body {
            font-size: 16px;
            -webkit-text-size-adjust: 100%;
            background: linear-gradient(180deg, #0a1628 0%, #122440 40%, #f0f4f8 40%);
            min-height: 100vh;
            padding: 10px 0 30px;
        }
        .form-control, .form-select {
            font-size: 16px;
            padding: 12px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--lime);
            box-shadow: 0 0 0 0.2rem rgba(184, 224, 0, 0.25);
            outline: none;
        }
        .form-label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a2a3a;
        }
        .text-danger { color: #dc3545 !important; font-weight: bold; }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }
        .poster-wrap {
            background: var(--navy);
            line-height: 0;
        }
        .poster-wrap img {
            width: 100%;
            height: auto;
            display: block;
        }
        .card-body { background: white; padding: 24px; }
        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 4px;
            text-align: center;
        }
        .card-subtitle {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 22px;
        }
        .form-section {
            background: #f7f9fc;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 18px;
            border-left: 4px solid var(--lime);
        }
        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dde3ea;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .btn-primary {
            background-color: var(--navy);
            border: 2px solid var(--lime);
            color: var(--lime);
            font-size: 16px;
            padding: 12px;
            font-weight: 700;
            min-height: 48px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-primary:hover {
            background-color: #132038;
            border-color: var(--lime);
            color: #fff;
        }
        .success-container { text-align: center; padding: 40px 20px; }
        .success-icon { font-size: 64px; color: #28a745; margin-bottom: 20px; }
        .registration-id-box {
            background-color: #e8f5c8;
            border: 2px solid var(--lime);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            display: inline-block;
        }
        .registration-id-label { font-size: 14px; color: #666; margin-bottom: 10px; }
        .registration-id-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--navy);
            letter-spacing: 2px;
        }
        .pickup-field { display: none; }
        .pickup-field.show { display: block; }
        @media (max-width: 576px) {
            body { padding: 0 0 20px; }
            .card-body { padding: 16px 14px; }
            .card-title { font-size: 18px; }
            .form-section { padding: 14px; }
        }
    </style>
</head>
<body>
<div class="container-fluid px-2 px-md-3 mt-2 mt-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-7">
            <div class="card shadow">
                <div class="poster-wrap">
                    <img src="{{ asset('images/tanseeq-badminton-poster.jpg') }}" alt="Tanseeq Badminton Championship Season 4">
                </div>
                <div class="card-body">
                    <h3 class="card-title">Registration Form</h3>
                    <p class="card-subtitle">Tanseeq Badminton Championship — Season 4</p>

                    @if(session('success'))
                        <div class="success-container">
                            <div class="success-icon">✓</div>
                            <h4 class="text-success mb-4">Registration Successful!</h4>
                            <p class="mb-4">{{ session('success') }}</p>
                            @if(session('registration_id'))
                                <div class="registration-id-box">
                                    <div class="registration-id-label">Your Registration ID:</div>
                                    <div class="registration-id-value">{{ session('registration_id') }}</div>
                                </div>
                            @endif
                            <p class="text-muted mt-4" style="font-size: 12px;">
                                Please save your Registration ID for future reference.
                            </p>
                        </div>
                    @else

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/tanseeq-badminton') }}" id="registrationForm">
                        @csrf

                        <div class="form-section">
                            <div class="section-title">Player Information</div>

                            <div class="mb-3">
                                <label for="company" class="form-label">Company <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="company" name="company" required>
                                    <option value="">Select Company</option>
                                    <option value="Proscape LLC" {{ old('company') == 'Proscape LLC' ? 'selected' : '' }}>Proscape LLC</option>
                                    <option value="Stones & Slates LLC" {{ old('company') == 'Stones & Slates LLC' ? 'selected' : '' }}>Stones & Slates LLC</option>
                                    <option value="Tanseeq" {{ old('company') == 'Tanseeq' ? 'selected' : '' }}>Tanseeq</option>
                                    <option value="Metaline" {{ old('company') == 'Metaline' ? 'selected' : '' }}>Metaline</option>
                                    <option value="Timbertech" {{ old('company') == 'Timbertech' ? 'selected' : '' }}>Timbertech</option>
                                    <option value="Proscape Golf" {{ old('company') == 'Proscape Golf' ? 'selected' : '' }}>Proscape Golf</option>
                                    <option value="Proscape FM" {{ old('company') == 'Proscape FM' ? 'selected' : '' }}>Proscape FM</option>
                                    <option value="Acacia" {{ old('company') == 'Acacia' ? 'selected' : '' }}>Acacia</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Employee Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name') }}" placeholder="Enter employee name" required autocomplete="name">
                            </div>

                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id"
                                       value="{{ old('employee_id') }}" placeholder="Enter employee ID" required autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth (DOB) <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dob" name="dob"
                                       value="{{ old('dob') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_local" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">971</span>
                                    <input type="tel" class="form-control" id="contact_local" inputmode="numeric"
                                           maxlength="9" pattern="[0-9]{9}"
                                           value="{{ old('contact_number') && str_starts_with(old('contact_number'), '971') ? substr(old('contact_number'), 3) : '' }}"
                                           placeholder="501234567" required>
                                </div>
                                <input type="hidden" id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                                <small class="text-muted">Enter 9 digits after 971 (example: 971501234567)</small>
                            </div>

                            <div class="mb-3">
                                <label for="planned_vacation" class="form-label">Planned Vacation <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="planned_vacation" name="planned_vacation"
                                       value="{{ old('planned_vacation') }}"
                                       min="{{ now()->timezone(config('app.timezone', 'Asia/Dubai'))->toDateString() }}"
                                       max="2027-01-31"
                                       required>
                                <small class="text-muted">Select a date from today through 31 January 2027</small>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Trial Details (27/11/2026)</div>

                            <div class="mb-3">
                                <label for="trial_availability" class="form-label">Trial Availability (27/11/2026) <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="trial_availability" name="trial_availability" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('trial_availability') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('trial_availability') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="trial_transportation" class="form-label">Trial Transportation <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="trial_transportation" name="trial_transportation" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('trial_transportation') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('trial_transportation') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3 pickup-field {{ old('trial_transportation') == 'Yes' ? 'show' : '' }}" id="trial_pickup_wrap">
                                <label for="trial_pickup_location" class="form-label">Trial Pickup Location <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="trial_pickup_location" name="trial_pickup_location">
                                    <option value="">Select Location</option>
                                    <option value="ADCB Metro Station" {{ old('trial_pickup_location') == 'ADCB Metro Station' ? 'selected' : '' }}>ADCB Metro Station</option>
                                    <option value="International City" {{ old('trial_pickup_location') == 'International City' ? 'selected' : '' }}>International City</option>
                                    <option value="Al Quoz" {{ old('trial_pickup_location') == 'Al Quoz' ? 'selected' : '' }}>Al Quoz</option>
                                    <option value="Rashidiya Metro Station" {{ old('trial_pickup_location') == 'Rashidiya Metro Station' ? 'selected' : '' }}>Rashidiya Metro Station</option>
                                    <option value="Etisalat Metro Station" {{ old('trial_pickup_location') == 'Etisalat Metro Station' ? 'selected' : '' }}>Etisalat Metro Station</option>
                                    <option value="DIP/Jebel Ali" {{ old('trial_pickup_location') == 'DIP/Jebel Ali' ? 'selected' : '' }}>DIP/Jebel Ali</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="section-title">Tournament Details</div>

                            <div class="mb-3">
                                <label for="tournament_availability" class="form-label">Tournament Availability <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="tournament_availability" name="tournament_availability" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('tournament_availability') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('tournament_availability') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tournament_transportation" class="form-label">Tournament Transportation <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="tournament_transportation" name="tournament_transportation" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('tournament_transportation') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('tournament_transportation') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="mb-3 pickup-field {{ old('tournament_transportation') == 'Yes' ? 'show' : '' }}" id="tournament_pickup_wrap">
                                <label for="tournament_pickup_location" class="form-label">Tournament Pickup Location <span class="text-danger">*</span></label>
                                <select class="form-control form-select" id="tournament_pickup_location" name="tournament_pickup_location">
                                    <option value="">Select Location</option>
                                    <option value="ADCB Metro Station" {{ old('tournament_pickup_location') == 'ADCB Metro Station' ? 'selected' : '' }}>ADCB Metro Station</option>
                                    <option value="International City" {{ old('tournament_pickup_location') == 'International City' ? 'selected' : '' }}>International City</option>
                                    <option value="Al Quoz" {{ old('tournament_pickup_location') == 'Al Quoz' ? 'selected' : '' }}>Al Quoz</option>
                                    <option value="Rashidiya Metro Station" {{ old('tournament_pickup_location') == 'Rashidiya Metro Station' ? 'selected' : '' }}>Rashidiya Metro Station</option>
                                    <option value="Etisalat Metro Station" {{ old('tournament_pickup_location') == 'Etisalat Metro Station' ? 'selected' : '' }}>Etisalat Metro Station</option>
                                    <option value="DIP/Jebel Ali" {{ old('tournament_pickup_location') == 'DIP/Jebel Ali' ? 'selected' : '' }}>DIP/Jebel Ali</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

    const contactLocal = document.getElementById('contact_local');
    const contactHidden = document.getElementById('contact_number');
    const registrationForm = document.getElementById('registrationForm');

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

    if (registrationForm) {
        registrationForm.addEventListener('submit', function (e) {
            syncContactNumber();
            if (!/^971[0-9]{9}$/.test(contactHidden.value)) {
                e.preventDefault();
                alert('Contact number must be 971 followed by exactly 9 digits.');
                contactLocal.focus();
            }
        });
    }
</script>
</body>
</html>
