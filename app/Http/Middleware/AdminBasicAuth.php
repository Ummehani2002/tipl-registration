<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminBasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        $envUser = env('ADMIN_USER');
        $envPass = env('ADMIN_PASS');

        if (!$envUser || !$envPass) {
            return response('Admin auth not configured.', 503);
        }

        $user = $request->getUser();
        $pass = $request->getPassword();

        if (!$user || !$pass || !hash_equals($envUser, $user) || !hash_equals($envPass, $pass)) {
            $headers = ['WWW-Authenticate' => 'Basic realm="Admin Area"'];
            return response('Unauthorized', 401, $headers);
        }

        return $next($request);
    }
}
