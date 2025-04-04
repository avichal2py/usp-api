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

        return response()->json([
            'message' => $program->registration_status ? 'Registration opened' : 'Registration closed',
            'program' => $program
        ]);
    }
}
