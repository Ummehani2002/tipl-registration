<?php

use App\Support\RegistrationDeadline;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (RegistrationDeadline::isClosed()) {
        return response()->view('registration.closed', [
            'closesAt' => RegistrationDeadline::closesAt(),
        ], 403);
    }

    $token = env('DEFAULT_FORM_TOKEN');
    if ($token) {
        return redirect('/register/'.$token);
    }

    $link = \App\Models\FormLink::orderBy('created_at', 'desc')->first();
    if ($link) {
        return redirect('/register/'.$link->token);
    }

    return view('welcome');
});

use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;

// Admin: protect with simple HTTP Basic (credentials in .env: ADMIN_USER, ADMIN_PASS)
$checkAdmin = function (Request $request) {
    $envUser = env('ADMIN_USER');
    $envPass = env('ADMIN_PASS');
    if (!$envUser || !$envPass) {
        return response('Admin auth not configured.', 503);
    }
    $user = $request->getUser();
    $pass = $request->getPassword();
    if (!$user || !$pass || !hash_equals($envUser, $user) || !hash_equals($envPass, $pass)) {
        return response('Unauthorized', 401, ['WWW-Authenticate' => 'Basic realm="Admin Area"']);
    }
    return null;
};

Route::get('/admin/form-links', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->indexLinks();
});
Route::post('/admin/form-links', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->storeLink($request);
});
Route::get('/admin/form-links/{id}/export', function (Request $request, $id) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->exportCsv($id);
});
Route::get('/admin/form-links/{id}/export-xlsx', function (Request $request, $id) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->exportXlsx($id);
});
Route::get('/admin/registrations', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->indexRegistrations($request);
});
Route::get('/admin/registrations/link/{token}', function (Request $request, $token) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->showRegistrationsByToken($token);
});
Route::get('/admin/registrations/export', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->exportAllCsv($request);
});
Route::get('/admin/registrations/export-xlsx', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app(RegistrationController::class)->exportAllXlsx($request);
});

// Public registration form by token
Route::get('/register/thanks', [RegistrationController::class, 'thankYou'])->name('register.thanks');
Route::get('/register/{token}', [RegistrationController::class, 'showForm'])->name('register.show');
Route::post('/register/{token}', [RegistrationController::class, 'submitForm'])->name('register.submit');
