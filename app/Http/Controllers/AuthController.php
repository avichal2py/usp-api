<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\LoginLoggerService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'emp_id' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('emp_id', $request->emp_id)->first();

        if (!$user || $user->password !== md5($request->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user
        ]);
    }

    public function studentLogin(Request $request)
{
    $request->validate([
        'student_id' => 'required|string',
        'password' => 'required|string',
    ]);

    $student = DB::table('student_login')
        ->where('student_id', $request->student_id)
        ->first();

    if (!$student || md5($request->password) !== $student->password) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Optional: Fetch student details too
    $details = DB::table('active_students')
        ->where('student_id', $request->student_id)
        ->first();

    return response()->json([
        'message' => 'Login successful',
        'user' => $details,
    ]);
}





public function showLoginForm()
{
    return view('login'); 
}

public function handleWebLogin(Request $request, LoginLoggerService $logger)
{
    $request->validate([
        'login_as' => 'required|in:employee,student',
        'identifier' => 'required',
        'password' => 'required',
    ]);

    $ip = $request->ip();

    if ($request->login_as === 'employee') {
        $user = User::where('emp_id', $request->identifier)->first();

        if (!$user || $user->password !== md5($request->password)) {
            $logger->log('EMPLOYEE', $request->identifier, 'FAIL', $ip, 'Login attempt failed');
            return back()->withErrors(['Invalid credentials']);
        }
        $logger->log('EMPLOYEE', $user->emp_id, 'SUCCESS', $ip, 'Login successful');
        session([
            'user' => $user, 
            'role' => 'employee',
        ]);

        if ($user->role == 1) {
            return redirect()->route('admin.home');
        } elseif ($user->role == 2) {
            return redirect()->route('lecturer.home');
        } else {
            return redirect('/');
        }

    } else {
        $student = DB::table('student_login')
            ->where('student_id', $request->identifier)
            ->first();

        if (!$student || md5($request->password) !== $student->password) {
             $logger->log('STUDENT', $request->identifier, 'FAIL', $ip, 'Login attempt failed');
            return back()->withErrors(['Invalid credentials']);
        }

        $details = DB::table('active_students')
            ->where('student_id', $student->student_id)
            ->first();

        session([
            'user' => $details, // ✅ unified key
            'role' => 'student',
        ]);

        $logger->log('STUDENT', $student->student_id, 'SUCCESS', $ip, 'Login successful');

        return redirect()->route('student.home');
    }
}


public function logout()
{
    session()->flush();
    return redirect('/');
}

}
