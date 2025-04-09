<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturerController extends Controller
{
    // Fetch distinct courses from student_track_course
    public function getCoursesFromTracking()
    {
        $courses = DB::table('student_track_course as stc')
            ->join('courses as c', 'stc.course_code', '=', 'c.course_code')
            ->select('c.course_code', 'c.course_name')
            ->distinct()
            ->get();

        return response()->json($courses);
    }

    // Get students for a given course
    public function getStudentsInCourse(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string'
        ]);

        $students = DB::table('student_track_course as stc')
            ->join('active_students as s', 's.student_id', '=', 'stc.student_id')
            ->where('stc.course_code', $request->course_code)
            ->select(
                'stc.id',
                's.student_id',
                's.first_name',
                's.last_name',
                'stc.status',
                'stc.grade'
            )
            ->get();

        return response()->json($students);
    }

    // Grade submission
    public function submitGrade(Request $request)
    {
        $request->validate([
            'track_id' => 'required|integer',
            'grade' => 'required|string'
        ]);

        DB::table('student_track_course')
            ->where('id', $request->track_id)
            ->update([
                'grade' => $request->grade,
                'status' => 'Completed'
            ]);

        return response()->json(['message' => 'Grade submitted successfully']);
    }
}
