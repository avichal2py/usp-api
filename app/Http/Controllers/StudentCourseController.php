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

        // Check prerequisites
        $prereqs = DB::table('prerequisites')->where('course_code', $courseCode)->pluck('prereq_code');
        if ($prereqs->isNotEmpty()) {
            $completed = DB::table('student_track_course')
                ->where('student_id', $studentId)
                ->where('status', 'Completed')
                ->pluck('course_code');

            $missing = $prereqs->diff($completed);
            if ($missing->isNotEmpty()) {
                return response()->json([
                    'message' => 'Missing prerequisites.',
                    'missing' => $missing->values(),
                ], 403);
            }
        }

        // Toggle registration
        $existing = DB::table('student_track_course')
            ->where('student_id', $studentId)
            ->where('course_code', $courseCode)
            ->where('status', 'Enrolled')
            ->first();

        if ($existing) {
            // Unregister
            DB::table('student_track_course')
                ->where('id', $existing->id)
                ->delete();

            return response()->json(['message' => 'Course unregistered.']);
        } else {
            // Register
            DB::table('student_track_course')->insert([
                'student_id' => $studentId,
                'course_code' => $courseCode,
                'semester' => $currentSemester,
                'status' => 'Enrolled',
                'registered_at' => now(),
            ]);

            return response()->json(['message' => 'Course registered successfully.']);
        }
    }

    public function visualCourses(Request $request)
{
    $studentId = $request->query('student_id');

    $student = DB::table('active_students')->where('student_id', $studentId)->first();
    if (!$student) {
        return response()->json(['message' => 'Student not found.'], 404);
    }

    $courses = DB::table('courses')
        ->where('program_id', $student->program_id)
        ->get()
        ->groupBy('level');

    $track = DB::table('student_track_course')
        ->where('student_id', $studentId)
        ->get()
        ->groupBy('course_code');

    $result = [];

    foreach ($courses as $level => $groupedCourses) {
        $result[] = [
            'level' => $level,
            'courses' => $groupedCourses->map(function ($course) use ($track) {
                $trackEntry = $track[$course->course_code][0] ?? null;

                $label = $course->course_name;
                $grade = null;
                $semester = null;

                if ($trackEntry) {
                    if ($trackEntry->status === 'Completed') {
                        $label .= ' ✅';
                    } elseif ($trackEntry->status === 'Enrolled') {
                        $label .= ' (R)';
                    }

                    $grade = $trackEntry->grade ?? null;
                    $semester = $trackEntry->semester ?? null;
                }

                return [
                    'course_code' => $course->course_code,
                    'label' => $label,
                    'description' => $course->description,
                    'grade' => $grade,
                    'semester' => $semester,
                ];
            })->values(),
        ];
    }

    return response()->json($result);
}


    public function getCoursesWithPrerequisites(Request $request)
{
    $request->validate([
        'program_id' => 'required|string',
    ]);

    $courses = DB::table('courses')
        ->where('program_id', $request->program_id)
        ->get();

    $result = $courses->map(function ($course) {
        $prereqs = DB::table('prerequisites')
            ->where('course_code', $course->course_code)
            ->pluck('prereq_code');

        return [
            'course_code' => $course->course_code,
            'course_name' => $course->course_name,
            'prerequisites' => $prereqs,
        ];
    });

    return response()->json($result);
}

}