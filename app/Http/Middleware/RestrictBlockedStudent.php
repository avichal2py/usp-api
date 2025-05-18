<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictBlockedStudent
{
    public function handle(Request $request, Closure $next)
    {
        $student = session('user');
        if ($student && $student->is_restricted) {
            return redirect()->route('payment.required');
        }

        return $next($request);
    }
}

