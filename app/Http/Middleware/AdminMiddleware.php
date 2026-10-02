<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $allowedEmails = ['admin@jmap.com', 'your@email.com']; // Ganti 'your@email.com' dengan email admin Anda

        if (in_array($user->email, $allowedEmails)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
