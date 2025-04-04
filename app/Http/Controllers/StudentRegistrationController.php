<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewStudentRegistration;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\Prerequisite;

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
    ]);

    $program = \DB::table('programs')->where('program_id', $validated['program_id'])->first();

    if (!$program) {
        return response()->json(['message' => 'Invalid program selected.'], 422);
    }

    // Attach program_name securely from DB
    $validated['program_name'] = $program->program_name;

    // Handle file upload
    if ($request->hasFile('tertiary_qualification')) {
        $file = $request->file('tertiary_qualification');
        $path = $file->store('uploads/qualifications', 'public');
        if ($request->hasFile('tertiary_qualification')) {
            $file = $request->file('tertiary_qualification');
            $path = $file->store('uploads/qualifications', 'public');
        
            // Optional: just log or keep for other use
            \Log::info('File uploaded to: ' . $path);
        }
        
        
    }

    // Set registration timestamp
    $validated['registration_date'] = now();

    // Save to DB
    $validated['application_id'] = uniqid(); // or use Str::uuid() if UUIDs are fine

    $application = NewStudentRegistration::create($validated);

    return response()->json([
        'message' => 'Registration successful',
        'application_id' => $application->application_id,
    ], 201);
}

public function getCoursesByProgram(Request $request)
{
    $request->validate([
        'program_id' => 'required'
    ]);

    $courses = Course::where('program_id', $request->program_id)->get();

    return response()->json($courses);
}

public function getPrerequisites(Request $request)
{
    $request->validate([
        'course_code' => 'required|string'
    ]);

    $prerequisites = Prerequisite::where('course_code', $request->course_code)->get();

    return response()->json($prerequisites);
}
}

