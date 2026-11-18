<?php
// routes/web.php
use App\Http\Controllers\RunRegistrationController;

// Root route - redirect to registration
Route::get('/', function () {
    return redirect('/tanseeq-badminton');
});

// Backward-compatible redirects from old run URLs
Route::redirect('/tanseeq-run', '/tanseeq-badminton', 301);
Route::redirect('/tanseeq-run/list', '/tanseeq-badminton/list', 301);
Route::redirect('/tanseeq-run/export', '/tanseeq-badminton/export', 301);
Route::redirect('/tanseeq-run/attendance', '/tanseeq-badminton/attendance', 301);

// Registration routes (public)
Route::get('/tanseeq-badminton', [RunRegistrationController::class, 'create']);
Route::post('/tanseeq-badminton', [RunRegistrationController::class, 'store']);
Route::get('/api/employee', [RunRegistrationController::class, 'getEmployee']);
Route::get('/api/check-database', [RunRegistrationController::class, 'checkDatabase']); // Diagnostic endpoint
Route::get('/api/check-deadline', [RunRegistrationController::class, 'checkDeadlineStatus']); // Check current deadline status
Route::get('/api/test-deadline', [RunRegistrationController::class, 'testDeadline']); // Test deadline at different times
Route::get('/api/check-schema', [RunRegistrationController::class, 'checkSchema']); // DB schema diagnostic
Route::get('/api/check-admin-status', [RunRegistrationController::class, 'checkAdminStatus'])->middleware('admin'); // Admin status diagnostic

// Admin login and registration routes
Route::get('/admin/login', [RunRegistrationController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [RunRegistrationController::class, 'login'])->name('admin.login.post');
Route::get('/admin/setup-super-admin', [RunRegistrationController::class, 'setupSuperAdmin']);
Route::get('/api/setup-super-admin', [RunRegistrationController::class, 'setupSuperAdmin']);

// Admin registration (only accessible by super admins)
Route::middleware('admin')->group(function () {
    Route::get('/admin/register', [RunRegistrationController::class, 'showRegister'])->name('admin.register');
    Route::post('/admin/register', [RunRegistrationController::class, 'register'])->name('admin.register.post');
});

// Admin routes (protected)
Route::middleware('admin')->group(function () {
    Route::get('/tanseeq-badminton/list', [RunRegistrationController::class, 'index'])->name('registrations.list');
    Route::get('/tanseeq-badminton/export', [RunRegistrationController::class, 'export']);
    Route::get('/tanseeq-badminton/export-presentees', [RunRegistrationController::class, 'exportPresentees'])->name('registrations.export.presentees');
    Route::get('/tanseeq-badminton/edit/{id}', [RunRegistrationController::class, 'edit'])->name('registrations.edit');
    Route::put('/tanseeq-badminton/update/{id}', [RunRegistrationController::class, 'update'])->name('registrations.update');
    Route::get('/admin/logout', [RunRegistrationController::class, 'logout'])->name('admin.logout');

    // Attendance routes
    Route::get('/tanseeq-badminton/attendance', [RunRegistrationController::class, 'showAttendance'])->name('attendance.show');
    Route::get('/tanseeq-badminton/attendance/export', [RunRegistrationController::class, 'exportAttendance'])->name('attendance.export');
    Route::post('/api/search-employee', [RunRegistrationController::class, 'searchEmployee'])->name('attendance.search');
    Route::post('/api/mark-attendance', [RunRegistrationController::class, 'markAttendance'])->name('attendance.mark');
});
