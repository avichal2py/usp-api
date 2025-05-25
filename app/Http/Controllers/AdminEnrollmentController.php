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

public function manageRestrictions()
{
    $students = DB::table('active_students')->get();
    return view('admin.restrictions', compact('students'));
}

public function search(Request $request)
{
    $search = $request->input('search');
    
    $query = DB::table('active_students')
        ->when($search, function($query) use ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        })
        ->orderBy('last_name');

    // Check if we're doing a search or showing all results
    if ($search) {
        $students = $query->paginate(15);
    } else {
        $students = $query->get();
    }

    return view('admin.restrictions', compact('students'));
}

public function toggleRestriction($id)
{
    $student = DB::table('active_students')->where('student_id', $id)->first();
    if (!$student) {
        return redirect()->back()->with('error', 'Student not found.');
    }

    DB::table('active_students')
        ->where('student_id', $id)
        ->update(['is_restricted' => !$student->is_restricted]);

    return redirect()->back()->with('success', 'Restriction status updated.');
}






public function viewStudentForms()
{
    $forms = DB::table('student_requests')
        ->join(DB::raw('active_students'), function ($join) {
            $join->on(
                DB::raw('CONVERT(student_requests.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci'),
                '=',
                DB::raw('CONVERT(active_students.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci')
            );
        })
        ->select(
            'student_requests.*',
            'active_students.first_name',
            'active_students.last_name',
            'active_students.program_name'
        )
        ->where('student_requests.status', 'Pending')
        ->get();

    return view('admin.reviewStudentForms', compact('forms'));
}


public function approveStudentForm($id)
{
    DB::table('student_requests')->where('id', $id)->update([
        'status' => 'Approved',
        'student_notified' => false,
        'updated_at' => now()
    ]);

    return back()->with('success', 'Student form approved and student will be notified.');
}

public function rejectStudentForm($id)
{
    DB::table('student_requests')->where('id', $id)->update([
        'status' => 'Rejected',
        'student_notified' => false,
        'updated_at' => now()
    ]);

    return back()->with('success', 'Student form rejected and student will be notified.');
}


public function downloadStudentDoc($filename)
{
    $path = storage_path("app/public/student_requests/{$filename}"); // ✅ FIXED folder name

    if (!file_exists($path)) {
        abort(404, 'File not found.');
    }

    return response()->download($path);
}




}
