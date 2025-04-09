<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentFinanceController extends Controller
{
    public function getFinance(Request $request)
{
    $request->validate([
        'student_id' => 'required|string',
    ]);

    $studentId = $request->student_id;

    $enrolledCourses = DB::table('student_track_course')
        ->where('student_id', $studentId)
        ->where('status', 'Enrolled')
        ->pluck('course_code');

    $coursesWithPrices = DB::table('course_price')
        ->whereIn('course_code', $enrolledCourses)
        ->get();

    $subtotal = $coursesWithPrices->sum('course_price');
    $serviceFee = 50;
    $total = $subtotal + $serviceFee;

    return response()->json([
        'courses' => $coursesWithPrices,
        'subtotal' => $subtotal,
        'service_fee' => $serviceFee,
        'total' => $total,
    ]);
}

}
