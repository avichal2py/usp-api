<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\NewStudentRegistration;
use App\Models\ActiveStudent;

class AdminEnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $applications = NewStudentRegistration::all();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($applications);
        }

        return view('admin.enrollments')->withApplications($applications);
    }


    /**
     * Approve a student application and create their account.
     */
    public function approve($id)
    {
        $application = NewStudentRegistration::findOrFail($id);

        $studentId = 'S' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        $plainPassword = Str::random(8);
        $hashedPassword = md5($plainPassword);

        DB::transaction(function () use ($application, $studentId, $hashedPassword) {
            // Insert into active_students
            DB::table('active_students')->insert([
                'student_id' => $studentId,
                'first_name' => $application->first_name,
                'middle_name' => $application->middle_name,
                'last_name' => $application->last_name,
                'dob' => $application->dob,
                'gender' => $application->gender,
                'citizenship' => $application->citizenship,
                'residential_address' => $application->residential_address,
                'postal_address' => $application->postal_address,
                'city' => $application->city,
                'nation' => $application->nation,
                'email_address' => $application->email_address,
                'phone' => $application->phone,
                'ec_firstname' => $application->ec_firstname,
                'ec_lastname' => $application->ec_lastname,
                'ec_othername' => $application->ec_othername,
                'ec_relationship' => $application->ec_relationship,
                'ec_residential_address' => $application->ec_residential_address,
                'ec_city' => $application->ec_city,
                'ec_nation' => $application->ec_nation,
                'ec_phone' => $application->ec_phone,
                'registration_date' => $application->registration_date,
                'program_id' => $application->program_id,
                'program_name' => $application->program_name,
            ]);

            // Insert into student_login
            DB::table('student_login')->insert([
                'student_id' => $studentId,
                'password' => $hashedPassword,
                'created_at' => now(),
            ]);
        });

        // Send email with credentials
        try {
    Mail::raw(
        "Your USP student account has been approved.\n\n" .
        "Student ID: $studentId\n" .
        "Password: $plainPassword\n\n" .
        "Login here: http://your-login-link.com",
        function ($message) use ($application) {
            $message->to($application->email_address)
                    ->subject('USP Student Login Credentials');
        }
    );
        } catch (\Exception $e) {
            Log::error('Failed to send student credentials email: ' . $e->getMessage());
        }

        // Delete original application
        $application->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Student approved and notified.',
                'student_id' => $studentId,
                'password' => $plainPassword
            ]);
        }
    
        return redirect()->back()->with('success', "Student approved! ID: $studentId Password: $plainPassword");
    }
    /**
     * Reject a student application.
     */
    public function reject($id)
    {
        $application = NewStudentRegistration::findOrFail($id);
        $application->delete();
    
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Application rejected.']);
        }
    
        return redirect()->back()->with('success', 'Application rejected.');
    }


    public function changeSemester(Request $request)
    {
        $request->validate([
            'semester' => 'required|in:1,2'
        ]);
    
        DB::table('alpha_control')->update([
            'semester' => $request->semester
        ]);
    
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Semester changed to ' . $request->semester
            ]);
        }
    
        return redirect()->back()->with('success', 'Semester changed to Semester ' . $request->semester . ' successfully.');
    }
    

public function showSemesterForm()
{
    $currentSemester = DB::table('alpha_control')->value('semester');
    return view('admin.semester', compact('currentSemester'));
}


public function getCurrentSemester()
{
    $control = DB::table('alpha_control')->first();

    if (!$control) {
        return response()->json(['message' => 'Semester not set'], 404);
    }

    return response()->json([
        'semester' => $control->semester
    ]);
}


}
