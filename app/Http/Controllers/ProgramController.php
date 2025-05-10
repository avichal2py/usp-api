<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        return Program::all();
    }

    public function toggleRegistration($id)
    {
        $program = Program::findOrFail($id);
        $program->registration_status = !$program->registration_status;
        $program->save();
    
        $message = $program->registration_status ? 'Registration opened' : 'Registration closed';
    
        if (request()->expectsJson()) {
            return response()->json([
                'message' => $message,
                'program' => $program
            ]);
        }
    
        return redirect()->back()->with('success', $message . ' for ' . $program->program_name);
    }
    

    public function page()
{
    // If web request, return Blade view
    if (!request()->expectsJson()) {
        $programs = Program::all();
        return view('admin.program', compact('programs'));
    }

    // Else return JSON (for API)
    return Program::all();
}

}
