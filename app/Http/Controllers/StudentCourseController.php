<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StudentTrackCourse;


class StudentCourseController extends Controller
{
    public function register(Request $request)
{
    $request->validate([
        'student_id' => 'required|string',
        'course_code' => 'required|string',
    ]);

    $studentId = $request->student_id;
    $courseCode = $request->course_code;

    $currentSemester = DB::table('alpha_control')->where('id', 1)->value('semester');
    $course = DB::table('courses')->where('course_code', $courseCode)->first();

    if (!$course) {
        return response()->json(['message' => 'Course not found.'], 404);
    }

    if ($course->semester != $currentSemester) {
        return response()->json(['message' => 'Course not available this semester.'], 403);
    }

    $prereqs = DB::table('prerequisites')->where('course_code', $courseCode)->pluck('prereq_code');

    if ($prereqs->isNotEmpty()) {
        $completed = DB::table('student_track_course')
            ->where('student_id', $studentId)
            ->where('status', 'Completed')
            ->pluck('course_code');

        $missing = $prereqs->diff($completed);

        if ($missing->isNotEmpty()) {
            return response()->json([
                'message' => 'Prerequisites not Completed.',
                'missing' => $missing->values(),
            ], 403);
        }
    }

    // Register student
    StudentTrackCourse::create([
        'student_id' => $studentId,
        'course_code' => $courseCode,
        'semester' => $currentSemester,
        'status' => 'Enrolled',
    ]);

    return response()->json(['message' => 'Course registered successfully.']);
}
}
