<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
