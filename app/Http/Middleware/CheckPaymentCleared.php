<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckPaymentCleared
{
 public function handle(Request $request, Closure $next)
{
    $student = session('user');
    if (!$student || session('role') !== 'student') {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    $uncleared = DB::table('student_track_course')
        ->where('student_id', $student->student_id)
        ->where('status', 'Enrolled')
        ->where('is_cleared', false)
        ->exists();

    if ($uncleared) {
        return redirect()->route('payment.required')->with([
            'message' => 'You must clear all payments to access this section.',
        ]);
    }

    return $next($request);
}

}