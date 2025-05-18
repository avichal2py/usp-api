<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessControl extends Controller
{

 public function accessCourseContent(Request $request)
{
    $studentId = $request->student_id;
    $courseCode = $request->course_code;

    $track = DB::table('student_track_course')
        ->where('student_id', $studentId)
        ->where('course_code', $courseCode)
        ->first();

    if (!$track || !$track->is_cleared) {
        return response()->json(['message' => 'Access denied. Please clear fees first.'], 403);
    }

    // Allow access
    return view('student.course-materials', [
        'course_code' => $courseCode
    ]);
}

}
