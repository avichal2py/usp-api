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

    // // Grade submission
    // public function submitGrade(Request $request)
    // {
    //     $request->validate([
    //         'track_id' => 'required|integer',
    //         'grade' => 'required|string'
    //     ]);

    //     DB::table('student_track_course')
    //         ->where('id', $request->track_id)
    //         ->update([
    //             'grade' => $request->grade,
    //             'status' => 'Completed'
    //         ]);

    //     return response()->json(['message' => 'Grade submitted successfully']);
    // }



    public function viewGradePage(Request $request)
{
    $courses = DB::table('student_track_course as stc')
        ->join('courses as c', 'stc.course_code', '=', 'c.course_code')
        ->select('c.course_code', 'c.course_name')
        ->distinct()
        ->get();

    $selectedCourse = $request->query('course_code');
    $students = [];

    if ($selectedCourse) {
        $query = DB::table('student_track_course as stc')
            ->join('active_students as s', 's.student_id', '=', 'stc.student_id')
            ->where('stc.course_code', $selectedCourse)
            ->select('stc.id', 's.student_id', 's.first_name', 's.last_name', 'stc.status', 'stc.grade');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('s.first_name', 'like', "%$search%")
                    ->orWhere('s.last_name', 'like', "%$search%")
                    ->orWhere('s.student_id', 'like', "%$search%");
            });
        }

        $students = $query->get();
    }

    return view('lecturer.grade', compact('courses', 'selectedCourse', 'students'));
}

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

    // ✅ Respond based on the request type
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Grade submitted successfully']);
    }

    return back()->with('success', 'Grade submitted successfully.');
}



public function viewGradeRecheckRequests()
{
    $rechecks = DB::table('grade_rechecks')
        ->select('grade_rechecks.*', 'active_students.first_name', 'active_students.last_name', 'courses.course_name')
        ->join(DB::raw('active_students'), function ($join) {
            $join->on(
                DB::raw('CONVERT(grade_rechecks.student_id USING utf8mb4) COLLATE utf8mb4_general_ci'),
                '=',
                DB::raw('CONVERT(active_students.student_id USING utf8mb4) COLLATE utf8mb4_general_ci')
            );
        })
        ->join(DB::raw('courses'), function ($join) {
            $join->on(
                DB::raw('CONVERT(grade_rechecks.course_code USING utf8mb4) COLLATE utf8mb4_general_ci'),
                '=',
                DB::raw('CONVERT(courses.course_code USING utf8mb4) COLLATE utf8mb4_general_ci')
            );
        })
        ->where('grade_rechecks.status', 'Pending')
        ->get();

    return view('lecturer.gradeRecheckRequests', compact('rechecks'));
}

public function updateGrade(Request $request, $id)
{
    $request->validate([
        'new_grade' => 'required|string',
    ]);

    $recheck = DB::table('grade_rechecks')->where('id', $id)->first();
    if (!$recheck) return back()->with('error', 'Request not found.');

    DB::table('student_track_course')
        ->where('student_id', $recheck->student_id)
        ->where('course_code', $recheck->course_code)
        ->update(['grade' => $request->new_grade]);

    DB::table('grade_rechecks')
        ->where('id', $id)
        ->update([
            'new_grade' => $request->new_grade,
            'status' => 'Reviewed',
            'lecturer_message' => 'Grade updated to ' . $request->new_grade,
            'student_notified' => false,
        ]);

    return back()->with('success', 'Grade updated successfully.');
}

public function rejectGradeRecheck($id)
{
    $recheck = DB::table('grade_rechecks')->where('id', $id)->first();

    if (!$recheck) {
        return back()->with('error', 'Recheck request not found.');
    }

    DB::table('grade_rechecks')
        ->where('id', $id)
        ->update([
            'status' => 'Rejected',
            'lecturer_message' => 'Grade recheck request rejected',
            'student_notified' => false,
        ]);

    return back()->with('reject', 'Grade recheck request rejected.');
}



}
