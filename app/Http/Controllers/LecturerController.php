<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    public function getEnrolledStudents(Request $request)
{
    $courseCode = $request->query('course_code');

    $students = DB::table('student_track_course')
        ->join('active_students', 'student_track_course.student_id', '=', 'active_students.student_id')
        ->where('student_track_course.course_code', $courseCode)
        ->select('active_students.student_id', 'first_name', 'last_name', 'status', 'grade')
        ->get();

    return response()->json($students);
}

}
