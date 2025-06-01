<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\StudentFinanceController;
use App\Http\Controllers\AdminEnrollmentController;
use App\Http\Controllers\StudentRegistrationController;

// Show login form
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');

// Handle login POST
Route::post('/login', [AuthController::class, 'handleWebLogin']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/student/home', function () {
    return view('student.home');
})->name('student.home');


Route::middleware('auth.session')->group(function () {
    Route::get('/admin', fn() => view('admin.home'))->name('admin.home');
});


Route::get('/lecturer/home', function () {
    return view('lecturer.home');
})->name('lecturer.home');

Route::middleware('web')->group(function () {
    Route::get('/lecturer/home', function () {
        return view('lecturer.home');
    })->name('lecturer.home');

    Route::get('/lecturer/grade', function () {
        return view('lecturer.grade');
    })->name('lecturer.grade');
});

Route::get('/lecturer/grade', [LecturerController::class, 'viewGradePage'])->name('lecturer.grade');
Route::post('/lecturer/submit-grade', [LecturerController::class, 'submitGrade'])->name('lecturer.submitGrade');
Route::get('/register', [StudentRegistrationController::class, 'show'])->name('student.register');
Route::post('/register', [StudentRegistrationController::class, 'register'])->name('student.register.submit');

Route::get('/admin/semester', [AdminEnrollmentController::class, 'showSemesterForm'])->name('admin.semester');
Route::post('/admin/semester', [AdminEnrollmentController::class, 'changeSemester'])->name('admin.semester.update');

Route::get('/admin/programs', [ProgramController::class, 'page'])->name('admin.program');
Route::post('/admin/programs/{id}/toggle', [ProgramController::class, 'toggleRegistration'])->name('admin.programs.toggle');
Route::get('/admin/enrollments', [AdminEnrollmentController::class, 'index'])->name('admin.enrollments');
Route::post('/admin/enrollments/{id}/approve', [AdminEnrollmentController::class, 'approve'])->name('admin.enrollments.approve');
Route::post('/admin/enrollments/{id}/reject', [AdminEnrollmentController::class, 'reject'])->name('admin.enrollments.reject');
Route::post('/student/register-course', [StudentCourseController::class, 'register'])->name('student.register-course');
Route::get('/student/courses', [StudentRegistrationController::class, 'getCoursesByProgram'])
    ->name('student.courses')->middleware('restrict.student');
Route::get('/student/visual-courses', [StudentCourseController::class, 'visualCourses'])->name('student.visualizeCourse');
Route::get('/student/download-completed-courses', [StudentCourseController::class, 'downloadCompletedCourses'])
    ->name('student.downloadCompletedCourses');

Route::get('/student/finance/download', [StudentFinanceController::class, 'downloadFinancePDF'])->name('student.downloadFinancePDF');
Route::get('/student/finance', [StudentFinanceController::class, 'showFinancePage'])->name('student.finance');
Route::get('/student/recheck-courses', [StudentCourseController::class, 'showRecheckCourses'])->name('student.recheckCourses');
Route::post('/student/apply-recheck', [StudentCourseController::class, 'applyGradeRecheck'])->name('student.applyRecheck');

Route::get('/lecturer/grade-rechecks', [LecturerController::class, 'viewGradeRecheckRequests'])->name('lecturer.gradeRechecks');
Route::post('/lecturer/grade-rechecks/{id}/update', [LecturerController::class, 'updateGrade'])->name('lecturer.updateGrade');
Route::post('/lecturer/grade-rechecks/{id}/reject', [LecturerController::class, 'rejectGradeRecheck'])->name('lecturer.rejectGrade');


Route::post('/student/dismiss-all-notifications', [StudentCourseController::class, 'dismissAllNotifications'])->name('student.dismissAllNotifications');
Route::get('/student/request-form', [StudentCourseController::class, 'showRequestForm'])->name('student.requestForm');
Route::post('/student/request-form', [StudentCourseController::class, 'submitRequestForm'])->name('student.submitRequestForm');


Route::get('/payment-required', [StudentFinanceController::class, 'showPaymentRequired'])
    ->name('payment.required');


Route::get('/admin/restrict-students', [AdminEnrollmentController::class, 'manageRestrictions'])->name('admin.restrict.view');
Route::post('/admin/toggle-student-restriction/{id}', [AdminEnrollmentController::class, 'toggleRestriction'])->name('admin.restrict.toggle');
Route::get('/admin/restrict-students/search', [AdminEnrollmentController::class, 'search'])->name('admin.restrict.search');

Route::get('/admin/forms', [AdminEnrollmentController::class, 'viewStudentForms'])->name('admin.viewForms');
Route::post('/admin/forms/{id}/approve', [AdminEnrollmentController::class, 'approveStudentForm'])->name('admin.approveForm');
Route::post('/admin/forms/{id}/reject', [AdminEnrollmentController::class, 'rejectStudentForm'])->name('admin.rejectForm');

Route::get('/download-request/{filename}', [StudentCourseController::class, 'downloadDoc'])
    ->name('student.downloadDoc');
Route::get('/docs', [StudentCourseController::class, 'getDoc'])->name('student.docs');

Route::get('/admin/download-student-doc/{filename}', [AdminEnrollmentController::class, 'downloadStudentDoc'])
    ->name('admin.downloadStudentDoc');


Route::get('/admin/login-logs', [AdminEnrollmentController::class, 'indexLogs'])->name('admin.loginLogs');
Route::post('/admin/login-logs/archive', [AdminEnrollmentController::class, 'archiveLogs'])->name('admin.loginLogs.archive');
Route::get('/admin/login-logs/archived', [AdminEnrollmentController::class, 'archivedLogs'])->name('admin.loginLogs.archived');
Route::post('/admin/login-logs/restore', [AdminEnrollmentController::class, 'restoreLogs'])->name('admin.loginLogs.restore');