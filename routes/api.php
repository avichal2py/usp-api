<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\StudentFinanceController;
use App\Http\Controllers\AdminEnrollmentController;
use App\Http\Controllers\StudentRegistrationController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/student/login', [AuthController::class, 'studentLogin']);

Route::get('/programs', [ProgramController::class, 'index']);
Route::post('/programs/{id}/toggle', [ProgramController::class, 'toggleRegistration']);

Route::post('/student/register', [StudentRegistrationController::class, 'register']);
Route::get('/student/courses', [StudentRegistrationController::class, 'getCoursesByProgram']);
Route::get('/student/prerequisites', [StudentRegistrationController::class, 'getPrerequisites']);
Route::post('/student/register-course', [StudentCourseController::class, 'register']);
Route::get('/student/visual-courses', [StudentCourseController::class, 'visualCourses']);
Route::get('/student/courses-prereqs', [StudentCourseController::class, 'getCoursesWithPrerequisites']);




Route::post('/admin/change-semester', [AdminEnrollmentController::class, 'changeSemester']);
Route::get('/admin/current-semester', [AdminEnrollmentController::class, 'getCurrentSemester']);



Route::get('/lecturer/courses', [LecturerController::class, 'getCoursesFromTracking']);
Route::get('/lecturer/students', [LecturerController::class, 'getStudentsInCourse']);
Route::post('/lecturer/grade', [LecturerController::class, 'submitGrade']);

Route::get('/student/finance', [StudentFinanceController::class, 'getFinance']);



Route::prefix('admin')->group(function () {
    Route::get('/enrollments', [AdminEnrollmentController::class, 'index']);
    Route::post('/enrollments/{id}/approve', [AdminEnrollmentController::class, 'approve']);
    Route::post('/enrollments/{id}/reject', [AdminEnrollmentController::class, 'reject']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
