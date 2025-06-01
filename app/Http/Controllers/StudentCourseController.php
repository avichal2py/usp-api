<?php

namespace App\Http\Controllers;

use App\Models\GradeRecheck;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\StudentTrackCourse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;
use App\Services\LoginLoggerService;



class StudentCourseController extends Controller
{
    public function register(Request $request, LoginLoggerService $logger)
    {
        $request->validate([
            'student_id' => 'required|string',
            'course_code' => 'required|string',
        ]);
        $ip = $request->ip();
    
        $studentId = $request->student_id;
        $courseCode = $request->course_code;
    
        $currentSemester = DB::table('alpha_control')->value('semester');
        $course = DB::table('courses')->where('course_code', $courseCode)->first();
    
        if (!$course) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Course not found.'], 404)
                : back()->withErrors(['Course not found.']);
        }
    
        if ($course->semester != $currentSemester) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Course not available this semester.'], 403)
                : back()->withErrors(['This course is not available this semester.']);
        }
    
        $prereqs = DB::table('prerequisites')->where('course_code', $courseCode)->pluck('prereq_code');
        if ($prereqs->isNotEmpty()) {
            $completed = DB::table('student_track_course')
                ->where('student_id', $studentId)
                ->where('status', 'Completed')
                ->pluck('course_code');
    
            $missing = $prereqs->diff($completed);
            if ($missing->isNotEmpty()) {
                $msg = 'Missing prerequisites: ' . $missing->implode(', ');
                return $request->expectsJson()
                    ? response()->json(['message' => 'Missing prerequisites.', 'missing' => $missing->values()])
                    : back()->withErrors([$msg]);
            }
        }
    
        $existing = DB::table('student_track_course')
            ->where('student_id', $studentId)
            ->where('course_code', $courseCode)
            ->where('status', 'Enrolled')
            ->first();
    
        if ($existing) {
            DB::table('student_track_course')
                ->where('id', $existing->id)
                ->delete();
                $logger->log('STUDENT', $request->student_id, 'SUCCESS', $ip, 'Course unregistered');
    
            return $request->expectsJson()
                ? response()->json(['message' => 'Course unregistered.'])
                : back()->with('success', 'Course unregistered.');
        } else {
            DB::table('student_track_course')->insert([
                'student_id' => $studentId,
                'course_code' => $courseCode,
                'semester' => $currentSemester,
                'status' => 'Enrolled',
                'registered_at' => now(),
            ]);
            $logger->log('STUDENT', $request->student_id, 'SUCCESS', $ip, 'Course registered');
    
            return $request->expectsJson()
                ? response()->json(['message' => 'Course registered successfully.'])
                : back()->with('success', 'Course registered successfully.');
        }
    }
    

    public function visualCourses(Request $request)
    {
        $student = null;
        $studentId = null;
    
        if ($request->has('student_id')) {
            // API usage: from query param
            $studentId = $request->query('student_id');
            $student = DB::table('active_students')->where('student_id', $studentId)->first();
        } elseif (session('role') === 'student' && session('user')) {
            // Web usage: from session
            $student = session('user');
            $studentId = $student->student_id;
        }
    
        // 🔴 Fallback: if no valid student info
        if (!$studentId || !$student) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Student not found.'], 404);
            } else {
                return redirect()->route('login')->with('error', 'Unauthorized access.');
            }
        }
    
        // 🧠 Fetch courses by program level
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
                    $status = 'Not Started';
    
                    if ($trackEntry) {
                        if ($trackEntry->status === 'Completed') {
                            $label .= ' ✅';
                            $status = 'Completed';
                        } elseif ($trackEntry->status === 'Enrolled') {
                            $label .= ' (R)';
                            $status = 'Enrolled';
                        }
    
                        $grade = $trackEntry->grade ?? null;
                        $semester = $trackEntry->semester ?? null;
                    }
    
                    return [
                        'course_code' => $course->course_code,
                        'label' => $label,
                        'description' => $course->description,
                        'grade' => $grade,
                        'semester' => $course->semester,
                        'status' => $status,
                    ];
                })->values(),
            ];
        }
    
        // ✅ Return based on request type
        if ($request->wantsJson()) {
            return response()->json($result);
        } else {
            return view('student.visualizeCourse', [
                'student' => $student,
                'levels' => $result,
            ]);
        }
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


// public function downloadCompletedCourses()
// {
//     $role = session('role');
//     $student = session('user');

//     if ($role !== 'student' || !$student) {
//         return redirect()->route('login')->with('error', 'Unauthorized access.');
//     }

//     $studentId = $student->student_id;

//     $courses = DB::table('courses')
//         ->where('program_id', $student->program_id)
//         ->get();

//     $track = DB::table('student_track_course')
//         ->where('student_id', $studentId)
//         ->where('status', 'Completed')
//         ->get()
//         ->keyBy('course_code');

//     $completedCourses = $courses->filter(function ($course) use ($track) {
//         return $track->has($course->course_code);
//     })->map(function ($course) use ($track) {
//         $trackEntry = $track[$course->course_code];
//         return [
//             'course_code' => $course->course_code,
//             'course_name' => $course->course_name,
//             'grade' => $trackEntry->grade ?? 'N/A',
//             'semester' => $trackEntry->semester ?? 'N/A',
//         ];
//     })->values();

//     $pdf = Pdf::loadView('student.pdfReport', [
//         'student' => $student,
//         'courses' => $completedCourses,
//     ]);

//     return $pdf->download('usp_completed_courses_report.pdf');
// }


public function downloadCompletedCourses()
{
    $role = session('role');
    $student = session('user');

    if ($role !== 'student' || !$student) {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    $studentId = $student->student_id;

    $courses = DB::table('courses')
        ->where('program_id', $student->program_id)
        ->get();

    $track = DB::table('student_track_course')
        ->where('student_id', $studentId)
        ->where('status', 'Completed')
        ->get()
        ->keyBy('course_code');

    $completedCourses = $courses->filter(function ($course) use ($track) {
        return $track->has($course->course_code);
    })->map(function ($course) use ($track) {
        $trackEntry = $track[$course->course_code];
        return [
            'course_code' => $course->course_code,
            'course_name' => $course->course_name,
            'grade' => $trackEntry->grade ?? 'N/A',
            'semester' => $trackEntry->semester ?? 'N/A',
        ];
    })->values()->toArray();

    // ❗ Alert if no completed courses
    if (empty($completedCourses)) {
        return redirect()->route('student.visualizeCourse')->with('error', 'No completed courses found. Please complete a course first.');
    }

    try {
        $response = Http::withHeaders([
            'Accept' => 'application/pdf',
        ])->timeout(5)->post('http://localhost:5001/generate-pdf', [
            'student' => [
                'first_name' => $student->first_name ?? '',
                'last_name' => $student->last_name ?? '',
                'student_id' => $student->student_id,
                'program_id' => $student->program_id,
            ],
            'courses' => $completedCourses
        ]);

        if ($response->successful()) {
            return response($response->body(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename=usp_completed_courses_report.pdf');
        }

    } catch (ConnectionException | RequestException $e) {
        \Log::error('PDF Service unavailable: ' . $e->getMessage());
    }

    return response()->view('errors.feature-unavailable', [], 503);
}




public function batchPrerequisites(Request $request)
{
    $courseCodes = $request->input('course_codes', []);

    $prereqs = DB::table('prerequisites')
        ->whereIn('course_code', $courseCodes)
        ->get()
        ->groupBy('course_code');

    return response()->json($prereqs);
}


public function showRecheckCourses()
{
    $student = session('user');

    $completedCourses = DB::table('student_track_course')
        ->where('student_id', $student->student_id)
        ->where('status', 'Completed')
        ->join('courses', 'student_track_course.course_code', '=', 'courses.course_code')
        ->select('student_track_course.course_code', 'courses.course_name', 'student_track_course.grade')
        ->get();

    return view('student.recheckCourses', compact('student', 'completedCourses'));
}

public function applyGradeRecheck(Request $request)
{
    $request->validate([
        'course_code' => 'required|string',
        'reason' => 'nullable|string',
    ]);

    $studentId = session('user')->student_id;

    // 🔍 Get the current grade from student_track_course
    $track = DB::table('student_track_course')
        ->where('student_id', $studentId)
        ->where('course_code', $request->course_code)
        ->first();

    if (!$track || $track->status !== 'Completed') {
        return redirect()->back()->with('error', 'You can only request a recheck for completed courses.');
    }

    // ✅ Create recheck with old grade
    GradeRecheck::create([
        'student_id' => $studentId,
        'course_code' => $request->course_code,
        'reason' => $request->reason,
        'old_grade' => $track->grade, // ✅ Set old grade
    ]);

    return redirect()->back()->with('success', 'Grade recheck request submitted.');
}



public function dismissAllNotifications(Request $request)
{
    if (session()->has('user')) {
        $studentId = session('user')->student_id;

        // Handle grade recheck notifications
        $gradeIds = explode(',', $request->grade_notification_ids);
        $gradeIds = array_filter($gradeIds);
        
        if (!empty($gradeIds)) {
            DB::table('grade_rechecks')
                ->where('student_id', $studentId)
                ->whereIn('id', $gradeIds)
                ->whereIn('status', ['Reviewed', 'Rejected'])
                ->where('student_notified', false)
                ->update(['student_notified' => true]);
        }

        // Handle form notifications
        $formIds = explode(',', $request->form_notification_ids);
        $formIds = array_filter($formIds);
        
        if (!empty($formIds)) {
            DB::table('student_requests')
                ->where('student_id', $studentId)
                ->whereIn('id', $formIds)
                ->whereIn('status', ['Approved', 'Rejected'])
                ->where('student_notified', false)
                ->update(['student_notified' => true]);
        }
    }

    return redirect()->back()->with('success', 'Notifications dismissed.');
}





public function showRequestForm()
{
    $student = session('user');
    if (!$student || session('role') !== 'student') {
        return redirect()->route('login')->with('error', 'Unauthorized');
    }

    return view('student.requestForm', compact('student'));
}

public function submitRequestForm(Request $request)
{
    $request->validate([
        'request_type' => 'required|in:Graduation,Compassionate Pass,Aegrotat Pass,Re-sit',
        'document' => 'required|file|mimes:pdf,jpg,png,docx|max:5120',
    ]);

    $studentId = session('user')->student_id;

    $extension = $request->file('document')->getClientOriginalExtension();
    $filename = $studentId . '_' . time() . '.' . $extension;

    $path = $request->file('document')->storeAs('student_requests', $filename, 'public');

    DB::table('student_requests')->insert([
        'student_id' => $studentId,
        'request_type' => $request->request_type,
        'document_path' => 'student_requests/' . $filename,
        'status' => 'Pending',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Request submitted.');
}



public function getDoc(){
    return view('student.documents');
}

public function downloadDoc($filename) 
{
    $path = public_path("documents/{$filename}");

    if (!file_exists($path)) {
        abort(404, "File not found.");
    }

    return response()->download($path);
}

}