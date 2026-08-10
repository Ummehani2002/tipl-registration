<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
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
    return app()->call([RegistrationController::class, 'indexLinks']);
});
Route::post('/admin/form-links', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app()->call([RegistrationController::class, 'storeLink'], ['request' => $request]);
});
Route::get('/admin/form-links/{id}/export', function (Request $request, $id) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app()->call([RegistrationController::class, 'exportCsv'], ['id' => $id]);
});
Route::get('/admin/form-links/{id}/export-xlsx', function (Request $request, $id) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app()->call([RegistrationController::class, 'exportXlsx'], ['id' => $id]);
});
Route::get('/admin/registrations', function (Request $request) use ($checkAdmin) {
    if ($resp = $checkAdmin($request)) return $resp;
    return app()->call([RegistrationController::class, 'indexRegistrations'], ['request' => $request]);
});

// Public registration form by token
Route::get('/register/thanks', [RegistrationController::class, 'thankYou'])->name('register.thanks');
Route::get('/register/{token}', [RegistrationController::class, 'showForm'])->name('register.show');
Route::post('/register/{token}', [RegistrationController::class, 'submitForm'])->name('register.submit');
