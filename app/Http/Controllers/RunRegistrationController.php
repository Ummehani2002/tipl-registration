<?php

namespace App\Http\Controllers;

use App\Models\RunRegistration;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class RunRegistrationController extends Controller
{
    /**
     * Check if registration is closed
     * Closes on 23 November 2026 at night 12:00 AM Dubai time
     * (open all day on 23 Nov; closed from 24 Nov 00:00 Asia/Dubai)
     *
     * @param Carbon|null $testTime Optional test time to simulate different times
     */
    private function isRegistrationClosed($testTime = null)
    {
        // Get deadline from environment or use default (night of 23 Nov 2026 = 24 Nov 00:00 Dubai)
        $deadline = env('REGISTRATION_DEADLINE', null);
        
        // Get local timezone from env or default to Asia/Dubai (Dubai/UAE timezone)
        $localTimezone = env('APP_TIMEZONE', 'Asia/Dubai');
        
        if ($deadline) {
            // Parse deadline assuming it's in local timezone (not UTC)
            $deadlineTime = Carbon::parse($deadline, $localTimezone);
        } else {
            // Default: night of 23 Nov 2026 at 12:00 AM Dubai = 24 Nov 2026 00:00:00 Asia/Dubai
            $deadlineTime = Carbon::create(2026, 11, 24, 0, 0, 0, $localTimezone);
        }
        
        $currentTime = $testTime ?? now();
        // Convert current time to local timezone for comparison
        $currentTime = $currentTime->setTimezone($localTimezone);
        
        return $currentTime->gte($deadlineTime);
    }

    /**
     * Quick diagnostic endpoint to check deadline status
     */
    public function checkDeadlineStatus()
    {
        $deadline = env('REGISTRATION_DEADLINE', null);
        $localTimezone = env('APP_TIMEZONE', 'Asia/Dubai');
        $currentTime = now();
        
        if ($deadline) {
            // Parse deadline assuming it's in local timezone
            $deadlineTime = Carbon::parse($deadline, $localTimezone);
        } else {
            $deadlineTime = Carbon::create(2026, 11, 24, 0, 0, 0, $localTimezone);
        }
        
        $currentTime = $currentTime->setTimezone($localTimezone);
        $isClosed = $currentTime->gte($deadlineTime);
        
        return response()->json([
            'current_time' => $currentTime->format('Y-m-d H:i:s'),
            'current_timezone' => $localTimezone,
            'deadline_from_env' => $deadline ?: 'NOT SET (using default)',
            'deadline_time' => $deadlineTime->format('Y-m-d H:i:s'),
            'deadline_timezone' => $deadlineTime->timezone->getName(),
            'is_closed' => $isClosed,
            'status' => $isClosed ? 'CLOSED' : 'OPEN',
            'time_until_deadline' => $currentTime->lt($deadlineTime) 
                ? $currentTime->diffForHumans($deadlineTime, true) 
                : 'Deadline has passed',
            'comparison' => [
                'current_timestamp' => $currentTime->timestamp,
                'deadline_timestamp' => $deadlineTime->timestamp,
                'difference_seconds' => $currentTime->timestamp - $deadlineTime->timestamp,
            ],
        ], 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Test endpoint to check registration status at different times
     * Usage: /api/test-deadline?time=2026-01-07 11:50:00
     */
    public function testDeadline(Request $request)
    {
        $testTimeStr = $request->input('time');
        
        // Get deadline
        $deadline = env('REGISTRATION_DEADLINE', null);
        if ($deadline) {
            $deadlineTime = Carbon::parse($deadline);
        } else {
            $deadlineTime = Carbon::create(2026, 11, 24, 0, 0, 0);
        }
        
        // Test times to check
        $testTimes = [];
        
        if ($testTimeStr) {
            // Test with provided time
            $testTimes[] = [
                'label' => 'Provided Time',
                'time' => Carbon::parse($testTimeStr),
            ];
        } else {
            // Default test times
            $testTimes = [
                ['label' => 'Nov 23 11:50 AM', 'time' => Carbon::create(2026, 11, 23, 11, 50, 0)],
                ['label' => 'Nov 23 11:59 PM', 'time' => Carbon::create(2026, 11, 23, 23, 59, 59)],
                ['label' => 'Nov 24 12:00 AM', 'time' => Carbon::create(2026, 11, 24, 0, 0, 0)],
                ['label' => 'Nov 24 12:01 AM', 'time' => Carbon::create(2026, 11, 24, 0, 1, 0)],
            ];
        }
        
        // Add current time
        array_unshift($testTimes, [
            'label' => 'Current Time',
            'time' => now(),
        ]);
        
        $results = [];
        foreach ($testTimes as $test) {
            $isClosed = $this->isRegistrationClosed($test['time']);
            $results[] = [
                'label' => $test['label'],
                'time' => $test['time']->format('Y-m-d H:i:s'),
                'is_closed' => $isClosed,
                'status' => $isClosed ? '❌ CLOSED' : '✅ OPEN',
                'time_until_deadline' => $test['time']->lt($deadlineTime) 
                    ? $test['time']->diffForHumans($deadlineTime, true) . ' until deadline'
                    : 'Deadline passed',
            ];
        }
        
        return response()->json([
            'deadline' => $deadlineTime->format('Y-m-d H:i:s'),
            'deadline_readable' => $deadlineTime->format('F j, Y g:i A'),
            'current_time' => now()->format('Y-m-d H:i:s'),
            'test_results' => $results,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    public function create()
    {
        // Check if registration is closed
        if ($this->isRegistrationClosed()) {
            return view('run.closed');
        }
        
        return view('run.register');
    }

    public function checkDatabase()
    {
        try {
            $employeeCount = Employee::count();
            $sampleEmployees = Employee::take(5)->get(['employee_id', 'name']);
            
            return response()->json([
                'status' => 'ok',
                'total_employees' => $employeeCount,
                'sample_employee_ids' => $sampleEmployees->pluck('employee_id')->toArray(),
                'message' => $employeeCount > 0 
                    ? "Database has {$employeeCount} employees. Employee lookup should work." 
                    : "⚠️ WARNING: No employees found in database! You need to import the Excel file."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkAdminStatus()
    {
        $isSuperAdmin = $this->isSuperAdmin();
        $isAdmin = $this->isAdmin();
        $user = Auth::user();
        
        $superAdminEmails = env('SUPER_ADMIN_EMAILS', '');
        $superAdminList = array_map('trim', explode(',', $superAdminEmails));
        $superAdminList = array_filter($superAdminList);
        
        return response()->json([
            'is_authenticated' => Auth::check(),
            'is_admin' => $isAdmin,
            'is_super_admin' => $isSuperAdmin,
            'current_user_email' => $user ? $user->email : null,
            'super_admin_emails_configured' => $superAdminList,
            'can_create_admins' => $isSuperAdmin,
            'message' => $isSuperAdmin 
                ? 'You are a super admin and can create admin accounts' 
                : ($isAdmin 
                    ? 'You are a regular admin and cannot create admin accounts' 
                    : 'You are not logged in')
        ]);
    }

    public function getEmployee(Request $request)
    {
        $employeeId = trim($request->input('employee_id'));
        
        if (empty($employeeId)) {
            return response()->json(['error' => 'Employee ID is required'], 400);
        }
        
        try {
            // Try multiple search methods to handle different formats
            $employee = null;
            
            // Method 1: Exact match (case sensitive)
            $employee = Employee::where('employee_id', $employeeId)->first();
            
            // Method 2: Case insensitive with trimmed values
            if (!$employee) {
                $employee = Employee::whereRaw('LOWER(TRIM(employee_id)) = ?', [strtolower(trim($employeeId))])->first();
            }
            
            // Method 3: Try with just trimmed (in case of extra spaces)
            if (!$employee) {
                $employee = Employee::whereRaw('TRIM(employee_id) = ?', [trim($employeeId)])->first();
            }
            
            // Method 4: Try case insensitive without trim (fallback)
            if (!$employee) {
                $employee = Employee::whereRaw('LOWER(employee_id) = ?', [strtolower($employeeId)])->first();
            }
            
            if ($employee) {
                // Check if already registered - use the actual employee_id from database
                $actualEmployeeId = $employee->employee_id;
                $existing = RunRegistration::where('employee_id', $actualEmployeeId)->first();
                if ($existing) {
                    return response()->json([
                        'error' => 'This employee ID has already been registered.',
                        'registration_id' => $existing->registration_id
                    ], 400);
                }
                
                // Get department/projects - try different possible field names
                $departmentProjects = $employee->department_projects 
                    ?? $employee->department 
                    ?? $employee->projects 
                    ?? '';
                
            return response()->json([
                'name' => $employee->name ?? '',
                'designation' => $employee->designation ?? '',
                'department_projects' => $departmentProjects,
                'entity' => $employee->entity ?? '', // Map entity to company field in form
            ]);
            }
            
            // Check if database has any employees at all
            $totalEmployees = Employee::count();
            
            // Log for debugging
            \Log::info('Employee not found', [
                'employee_id' => $employeeId,
                'total_employees' => $totalEmployees,
                'searched_id' => $employeeId
            ]);
            
            // Provide helpful error message
            $errorMessage = 'Employee not found. Please check your Employee ID and try again.';
            if ($totalEmployees === 0) {
                $errorMessage .= ' (Note: No employees found in database. Please contact administrator to import employee data.)';
            } else {
                $errorMessage .= ' You can also manually enter your details below.';
            }
            
            return response()->json([
                'error' => $errorMessage
            ], 404);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching employee', [
                'employee_id' => $employeeId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Error fetching employee data. Please try again or enter your details manually.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // Check if registration is closed
        if ($this->isRegistrationClosed()) {
            return back()->withErrors(['registration_closed' => 'Registration is now closed. The deadline has passed.'])->withInput();
        }

        $companies = [
            'Proscape LLC',
            'Stones & Slates LLC',
            'Tanseeq',
            'Metaline',
            'Timbertech',
            'Proscape Golf',
            'Proscape FM',
            'Acacia',
        ];

        $pickupLocations = [
            'ADCB Metro Station',
            'International City',
            'Al Quoz',
            'Rashidiya Metro Station',
            'Etisalat Metro Station',
            'DIP/Jebel Ali',
        ];

        $request->validate([
            'company' => ['required', Rule::in($companies)],
            'name' => 'required|string|max:255',
            'employee_id' => 'required|unique:run_registrations',
            'dob' => 'required|date',
            'contact_number' => ['required', 'regex:/^971[0-9]{9}$/'],
            'planned_vacation' => 'required|date|after_or_equal:today|before_or_equal:2027-01-31',
            'trial_availability' => 'required|in:Yes,No',
            'trial_transportation' => 'required|in:Yes,No',
            'trial_pickup_location' => ['nullable', 'required_if:trial_transportation,Yes', Rule::in($pickupLocations)],
            'tournament_availability' => 'required|in:Yes,No',
            'tournament_transportation' => 'required|in:Yes,No',
            'tournament_pickup_location' => ['nullable', 'required_if:tournament_transportation,Yes', Rule::in($pickupLocations)],
        ], [
            'contact_number.regex' => 'Contact number must start with 971 followed by exactly 9 digits (example: 971501234567).',
        ]);

        // Generate unique registration ID: BC4-XXXX
        $existingRegistrations = RunRegistration::where('registration_id', 'like', 'BC4-%')->get();
        $maxNumber = 1000;

        foreach ($existingRegistrations as $reg) {
            if (preg_match('/BC4-(\d+)/', $reg->registration_id, $matches)) {
                $regNumber = (int) $matches[1];
                if ($regNumber > $maxNumber) {
                    $maxNumber = $regNumber;
                }
            }
        }

        $nextNumber = $maxNumber + 1;
        $registrationId = 'BC4-' . $nextNumber;

        while (RunRegistration::where('registration_id', $registrationId)->exists()) {
            $nextNumber++;
            $registrationId = 'BC4-' . $nextNumber;
        }

        try {
            $this->ensureBadmintonColumns();

            RunRegistration::create([
                'employee_id' => $request->employee_id,
                'name' => $request->name,
                'designation' => 'Player',
                'company' => $request->company,
                'dob' => $request->dob,
                'run_category' => 'Badminton',
                'contact_number' => $request->contact_number,
                'planned_vacation' => $request->planned_vacation,
                'tshirt_size' => 'N/A',
                'trial_availability' => $request->trial_availability,
                'trial_transportation' => $request->trial_transportation,
                'trial_pickup_location' => $request->trial_transportation === 'Yes' ? $request->trial_pickup_location : null,
                'tournament_availability' => $request->tournament_availability,
                'tournament_transportation' => $request->tournament_transportation,
                'tournament_pickup_location' => $request->tournament_transportation === 'Yes' ? $request->tournament_pickup_location : null,
                'registration_id' => $registrationId,
            ]);
        } catch (\Throwable $e) {
            Log::error('Badminton registration failed', [
                'message' => $e->getMessage(),
                'employee_id' => $request->employee_id,
            ]);

            return back()
                ->withErrors(['registration' => 'Registration failed: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()->back()->with([
            'success' => 'Registration successful! Your Registration ID is: ' . $registrationId,
            'registration_id' => $registrationId
        ]);
    }

    /**
     * Ensure badminton columns exist (helps when Cloud migrate was not run yet).
     */
    private function ensureBadmintonColumns(): void
    {
        if (!Schema::hasTable('run_registrations')) {
            throw new \RuntimeException('Registrations table is missing. Run: php artisan migrate --force');
        }

        $columns = [
            'planned_vacation' => fn (Blueprint $table) => $table->date('planned_vacation')->nullable(),
            'trial_availability' => fn (Blueprint $table) => $table->string('trial_availability')->nullable(),
            'trial_transportation' => fn (Blueprint $table) => $table->string('trial_transportation')->nullable(),
            'trial_pickup_location' => fn (Blueprint $table) => $table->string('trial_pickup_location')->nullable(),
            'tournament_availability' => fn (Blueprint $table) => $table->string('tournament_availability')->nullable(),
            'tournament_transportation' => fn (Blueprint $table) => $table->string('tournament_transportation')->nullable(),
            'tournament_pickup_location' => fn (Blueprint $table) => $table->string('tournament_pickup_location')->nullable(),
            'registration_id' => fn (Blueprint $table) => $table->string('registration_id')->nullable(),
        ];

        foreach ($columns as $column => $definition) {
            if (!Schema::hasColumn('run_registrations', $column)) {
                Schema::table('run_registrations', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        }
    }

    public function index()
    {
        $registrations = RunRegistration::latest()->get();
        $isSuperAdmin = $this->isSuperAdmin();
        return view('run.list', compact('registrations', 'isSuperAdmin'));
    }

    public function showAttendance()
    {
        $presentCount = RunRegistration::where('attendance_status', 'present')->count();
        
        return view('run.attendance', compact('presentCount'));
    }

    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Check if current user is a super admin
     * Only the 3 initial super admins (defined in .env) can create other admin accounts
     * Admins created by super admins can access admin area but cannot create more admins
     */
    private function isSuperAdmin()
    {
        // Get super admin emails from .env (comma-separated)
        // These are the 3 initial admins who can create other admins
        $superAdminEmails = env('SUPER_ADMIN_EMAILS', '');
        $superAdminList = array_map('trim', explode(',', $superAdminEmails));
        $superAdminList = array_filter($superAdminList); // Remove empty values
        
        // Check if user is logged in and is one of the 3 super admins
        if (Auth::check()) {
            $userEmail = Auth::user()->email;
            return in_array($userEmail, $superAdminList);
        }
        
        return false;
    }

    /**
     * Check if current user is an admin (any admin - super admin or regular admin)
     * All users in the users table are admins and can access admin area
     */
    private function isAdmin()
    {
        // Any authenticated user is an admin
        // Super admins are a subset of admins who can create more admins
        return Auth::check() || session('admin_logged_in');
    }

    public function showRegister()
    {
        // Only super admins can access registration page
        if (!$this->isSuperAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Access denied. Only super admins can create admin accounts.');
        }
        
        return view('admin.register');
    }

    public function register(Request $request)
    {
        // Only super admins can create admin accounts
        if (!$this->isSuperAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Access denied. Only super admins can create admin accounts.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create new admin account
        // This user will be able to access admin area but cannot create more admins
        // Only the 3 super admins (defined in .env) can create admin accounts
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('registrations.list')->with('success', 'Admin account created successfully! The new admin can now login and access the admin area.');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // Email is now required for proper authentication
        if (!$email) {
            return redirect()->back()->with('error', 'Email address is required. Please enter your email to login.');
        }

        // Validate email and password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try to authenticate with email and password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            session(['admin_logged_in' => true]);
            session(['admin_user_id' => Auth::id()]);
            return redirect()->route('registrations.list')->with('success', 'Login successful!');
        }

        // If authentication fails, provide helpful error message
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Invalid email or password. Please check your credentials and try again.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->forget('admin_logged_in');
        session()->forget('admin_user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully');
    }

    public function edit($id)
    {
        $registration = RunRegistration::findOrFail($id);
        return view('run.edit', compact('registration'));
    }

    public function update(Request $request, $id)
    {
        $registration = RunRegistration::findOrFail($id);

        $companies = [
            'Proscape LLC',
            'Stones & Slates LLC',
            'Tanseeq',
            'Metaline',
            'Timbertech',
            'Proscape Golf',
            'Proscape FM',
            'Acacia',
        ];

        $pickupLocations = [
            'ADCB Metro Station',
            'International City',
            'Al Quoz',
            'Rashidiya Metro Station',
            'Etisalat Metro Station',
            'DIP/Jebel Ali',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'company' => ['required', Rule::in($companies)],
            'dob' => 'required|date',
            'contact_number' => ['required', 'regex:/^971[0-9]{9}$/'],
            'planned_vacation' => 'required|date|after_or_equal:today|before_or_equal:2027-01-31',
            'trial_availability' => 'required|in:Yes,No',
            'trial_transportation' => 'required|in:Yes,No',
            'trial_pickup_location' => ['nullable', 'required_if:trial_transportation,Yes', Rule::in($pickupLocations)],
            'tournament_availability' => 'required|in:Yes,No',
            'tournament_transportation' => 'required|in:Yes,No',
            'tournament_pickup_location' => ['nullable', 'required_if:tournament_transportation,Yes', Rule::in($pickupLocations)],
        ], [
            'contact_number.regex' => 'Contact number must start with 971 followed by exactly 9 digits (example: 971501234567).',
        ]);

        $registration->update([
            'name' => $request->name,
            'company' => $request->company,
            'dob' => $request->dob,
            'contact_number' => $request->contact_number,
            'planned_vacation' => $request->planned_vacation,
            'trial_availability' => $request->trial_availability,
            'trial_transportation' => $request->trial_transportation,
            'trial_pickup_location' => $request->trial_transportation === 'Yes' ? $request->trial_pickup_location : null,
            'tournament_availability' => $request->tournament_availability,
            'tournament_transportation' => $request->tournament_transportation,
            'tournament_pickup_location' => $request->tournament_transportation === 'Yes' ? $request->tournament_pickup_location : null,
        ]);

        return redirect()->route('registrations.list')->with('success', 'Registration updated successfully!');
    }

    public function export()
    {
        $registrations = RunRegistration::latest()->get();
        
        $filename = 'tanseeq_badminton_registrations_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'Registration ID',
                'Employee ID',
                'Name',
                'Company',
                'DOB',
                'Contact Number',
                'Planned Vacation',
                'Trial Availability',
                'Trial Transportation',
                'Trial Pickup Location',
                'Tournament Availability',
                'Tournament Transportation',
                'Tournament Pickup Location',
                'Attendance Status',
                'Registered At'
            ]);
            
            // Data rows
            foreach ($registrations as $r) {
                fputcsv($file, [
                    $r->registration_id ?? 'N/A',
                    $r->employee_id,
                    $r->name,
                    $r->company,
                    $r->dob ? Carbon::parse($r->dob)->format('d/m/Y') : '-',
                    $r->contact_number,
                    $r->planned_vacation ? Carbon::parse($r->planned_vacation)->format('d/m/Y') : '-',
                    $r->trial_availability ?? '-',
                    $r->trial_transportation ?? '-',
                    $r->trial_pickup_location ?? '-',
                    $r->tournament_availability ?? '-',
                    $r->tournament_transportation ?? '-',
                    $r->tournament_pickup_location ?? '-',
                    $r->attendance_status ?? 'pending',
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function searchEmployee(Request $request)
    {
        $employeeId = trim($request->input('employee_id'));
        
        if (empty($employeeId)) {
            return response()->json(['error' => 'Employee ID is required'], 400);
        }
        
        $registration = RunRegistration::where('employee_id', $employeeId)
            ->orWhere('employee_id', 'LIKE', '%' . $employeeId . '%')
            ->first();
        
        if (!$registration) {
            return response()->json(['error' => 'Employee not found in registrations'], 404);
        }
        
        return response()->json([
            'id' => $registration->id,
            'employee_id' => $registration->employee_id,
            'name' => $registration->name,
            'designation' => $registration->designation,
            'company' => $registration->company,
            'contact_number' => $registration->contact_number,
            'run_category' => $registration->run_category,
            'bib_number' => $registration->bib_number,
            'registration_id' => $registration->registration_id,
            'tshirt_size' => $registration->tshirt_size,
            'attendance_status' => $registration->attendance_status ?? 'pending',
        ]);
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:run_registrations,id',
            'status' => 'required|in:present,pending',
        ]);
        
        $registration = RunRegistration::findOrFail($request->registration_id);
        $registration->attendance_status = $request->status;
        $registration->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'attendance_status' => $registration->attendance_status,
        ]);
    }

    public function exportPresentees()
    {
        $registrations = RunRegistration::where('attendance_status', 'present')
            ->latest()
            ->get();
        
        $filename = 'tanseeq_badminton_presentees_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'Registration ID',
                'Employee ID',
                'Name',
                'Company',
                'DOB',
                'Contact Number',
                'Planned Vacation',
                'Trial Availability',
                'Trial Transportation',
                'Trial Pickup Location',
                'Tournament Availability',
                'Tournament Transportation',
                'Tournament Pickup Location',
                'Registered At'
            ]);
            
            // Data rows
            foreach ($registrations as $r) {
                fputcsv($file, [
                    $r->registration_id ?? 'N/A',
                    $r->employee_id,
                    $r->name,
                    $r->company,
                    $r->dob ? Carbon::parse($r->dob)->format('d/m/Y') : '-',
                    $r->contact_number,
                    $r->planned_vacation ? Carbon::parse($r->planned_vacation)->format('d/m/Y') : '-',
                    $r->trial_availability ?? '-',
                    $r->trial_transportation ?? '-',
                    $r->trial_pickup_location ?? '-',
                    $r->tournament_availability ?? '-',
                    $r->tournament_transportation ?? '-',
                    $r->tournament_pickup_location ?? '-',
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportAttendance()
    {
        // Export ONLY present registrations
        $registrations = RunRegistration::where('attendance_status', 'present')
            ->latest()
            ->get();
        
        $filename = 'tanseeq_badminton_presentees_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row - Attendance Sheet with attendance status
            fputcsv($file, [
                'Registration ID',
                'Employee ID',
                'Name',
                'Company',
                'DOB',
                'Contact Number',
                'Trial Availability',
                'Tournament Availability',
                'Attendance Status',
                'Registered At'
            ]);
            
            // Data rows
            foreach ($registrations as $r) {
                $attendanceStatus = $r->attendance_status ?? 'Pending';
                if ($attendanceStatus === 'present') {
                    $attendanceStatus = 'Present';
                } else {
                    $attendanceStatus = 'Pending';
                }
                
                fputcsv($file, [
                    $r->registration_id ?? 'N/A',
                    $r->employee_id,
                    $r->name,
                    $r->company,
                    $r->dob ? Carbon::parse($r->dob)->format('d/m/Y') : '-',
                    $r->contact_number,
                    $r->trial_availability ?? '-',
                    $r->tournament_availability ?? '-',
                    $attendanceStatus,
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

