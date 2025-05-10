<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


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

public function downloadFinancePDF()
{
    $student = session('user');
    if (!$student || session('role') !== 'student') {
        return redirect()->route('login');
    }

    $studentId = $student->student_id;

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

    $pdf = Pdf::loadView('student.finance_pdf', [
        'student' => $student,
        'courses' => $coursesWithPrices,
        'subtotal' => $subtotal,
        'service_fee' => $serviceFee,
        'total' => $total,
    ]);

    return $pdf->download('usp_finance_summary.pdf');
}

public function showFinancePage()
{
    $student = session('user');
    if (!$student || session('role') !== 'student') {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    $studentId = $student->student_id;

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

    return view('student.finance', [
        'student' => $student,
        'finance' => [ // ✅ Now the Blade will have $finance
            'courses' => $coursesWithPrices,
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'total' => $total,
        ],
    ]);
}


}
