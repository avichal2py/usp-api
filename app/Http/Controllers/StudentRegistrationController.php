<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Course;
use App\Models\Prerequisite;
use Illuminate\Http\Request;
use App\Models\NewStudentRegistration;
use Illuminate\Support\Facades\Storage;

class StudentRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'email_address' => 'required|email',
            'phone' => 'required',
            'program_id' => 'required|string',
    
            // Optional fields
            'citizenship' => 'nullable|string',
            'residential_address' => 'nullable|string',
            'postal_address' => 'nullable|string',
            'city' => 'nullable|string',
            'nation' => 'nullable|string',
    
            // Emergency Contact
            'ec_firstname' => 'nullable|string',
            'ec_lastname' => 'nullable|string',
            'ec_othername' => 'nullable|string',
            'ec_relationship' => 'nullable|string',
            'ec_residential_address' => 'nullable|string',
            'ec_city' => 'nullable|string',
            'ec_nation' => 'nullable|string',
            'ec_phone' => 'nullable|string',
        ]);
    
        // ✅ Validate program
        $program = DB::table('programs')->where('program_id', $validated['program_id'])->first();
        if (!$program) {
            return response()->json(['message' => 'Invalid program selected.'], 422);
        }
    
        $validated['program_name'] = $program->program_name;
    
        // ✅ Handle file storage without saving path in DB
        if ($request->hasFile('tertiary_qualification')) {
            $file = $request->file('tertiary_qualification');
            $path = $file->store('uploads/qualifications', 'public');
            \Log::info('Tertiary qualification uploaded to: ' . $path);
            // You can also email or notify admin with file path if needed
        }
    
        // ✅ Meta
        $validated['application_id'] = uniqid();
        $validated['registration_date'] = now();
    
        // ✅ Save application (without the file path)
        $application = NewStudentRegistration::create($validated);
    
        return response()->json([
            'message' => 'Registration successful',
            'application_id' => $application->application_id,
        ], 201);
    }
    

    

public function getCoursesByProgram(Request $request)
{
    // 👇 Automatically fill from session if missing
    if (!$request->has('student_id') || !$request->has('program_id')) {
        $user = session('user');
        if (!$user || !isset($user->student_id, $user->program_id)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->withErrors(['Session expired. Please log in.']);
        }

        $request->merge([
            'student_id' => $user->student_id,
            'program_id' => $user->program_id,
        ]);
    }

    // ✅ Validate inputs now that session values are merged
    $request->validate([
        'program_id' => 'required',
        'student_id' => 'required'
    ]);

    $courses = Course::where('program_id', $request->program_id)->get();

    $track = \DB::table('student_track_course')
        ->where('student_id', $request->student_id)
        ->get()
        ->groupBy('course_code');

    $enriched = $courses->map(function ($course) use ($track) {
        $trackEntry = $track[$course->course_code][0] ?? null;

        return [
            'course_code' => $course->course_code,
            'course_name' => $course->course_name ?? 'Untitled',
            'description' => $course->description ?? '',
            'semester' => $course->semester ?? '',
            'status' => $trackEntry->status ?? null,
        ];
    })->toArray(); // 👈 Ensure array for Blade compatibility

    // ✅ API response
    if ($request->expectsJson()) {
        return response()->json($enriched);
    }
    $prerequisites = [];
    foreach ($courses as $course) {
        $prereqList = \DB::table('prerequisites')
            ->where('course_code', $course->course_code)
            ->pluck('prereq_code')
            ->toArray();

        $prerequisites[$course->course_code] = $prereqList;
    }


    return view('student.courses', [
        'courses' => $enriched,
        'program_id' => $request->program_id,
        'student_id' => $request->student_id,
        'prerequisites' => $prerequisites,
        'current_semester' => \DB::table('alpha_control')->value('semester')
    ]);
    
}




public function getPrerequisites(Request $request)
{
    $request->validate([
        'course_code' => 'required|string'
    ]);

    $prerequisites = Prerequisite::where('course_code', $request->course_code)->get();

    return response()->json($prerequisites);
}

public function show()
{
    $programs = \DB::table('programs')->get();
    return view('student.register', compact('programs'));
}

// Controller method
public function showCourses()
{
    $student = session('user');

    if (!$student) {
        return redirect()->route('login')->withErrors(['Session expired. Please log in.']);
    }

    return view('student.courses', [
        'student_id' => $student->student_id,
        'program_id' => $student->program_id,
    ]);
}

}

