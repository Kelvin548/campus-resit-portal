<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChiefExaminerController;
use App\Http\Controllers\ChiefExaminerProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\SupplementaryCycleController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('login');
});

// Public, read-only design previews; these contain illustrative data only.
Route::view('/staff/login', 'auth.staff')->name('staff.login');
Route::view('/preview/examiner', 'preview.examiner')->name('preview.examiner');
// Route::view('/preview/admin', 'preview.admin')->name('preview.admin');

Route::post('/staff/login', [AuthController::class, 'staffLogin'])->name('staff.authenticate')->middleware('guest');
Route::get('/staff/invitation', [AuthController::class, 'showStaffInvitation'])->name('staff.invitation')->middleware('guest');
Route::post('/staff/invitation/verify', [AuthController::class, 'verifyStaffInvitation'])->name('staff.invitation.verify')->middleware(['guest', 'throttle:6,1']);
Route::post('/staff/invitation/complete', [AuthController::class, 'completeStaffInvitation'])->name('staff.invitation.complete')->middleware(['guest', 'throttle:6,1']);


// Role-protected Administrator workspace (Felicia's admin panel)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [AdminController::class, 'students'])->name('students.index');
    Route::get('/staff-invitations/create', [AuthController::class, 'showStaffInvitationForm'])->name('staff-invitations.create');
    Route::post('/staff-invitations', [AuthController::class, 'sendStaffInvitation'])->name('staff-invitations.store')->middleware('throttle:10,1');
    Route::patch('/students/{user}/status',[AdminAccountController::class, 'updateStudentStatus'])->name('update-student-status');
    Route::patch('/staff/{user}/status', [AdminAccountController::class, 'updateStaffStatus'])->name('staff.status');
    Route::get('/profile', [AdminAccountController::class, 'showProfile'])
    ->name('profile.show');
    Route::patch('/profile', [AdminAccountController::class, 'updateProfile'])
    ->name('profile.update');
    Route::patch('/profile/email', [AdminAccountController::class, 'requestEmailChange'])
    ->name('profile.email.request');
    Route::patch('/profile/email/confirm', [AdminAccountController::class, 'confirmEmailChange'])
    ->name('profile.email.confirm');
});

Route::middleware(['auth', 'role:chief_examiner'])->prefix('chief-examiner')->name('chief-examiner.')->group(function () {
    Route::get('/dashboard', [ChiefExaminerController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ChiefExaminerProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ChiefExaminerProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/email', [ChiefExaminerProfileController::class, 'requestEmailChange'])->name('profile.email.request');
    Route::patch('/profile/email/confirm', [ChiefExaminerProfileController::class, 'confirmEmailChange'])->name('profile.email.confirm');
    Route::post('/registrations/{registration}/approve', [ChiefExaminerController::class, 'approveRegistration'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [ChiefExaminerController::class, 'rejectRegistration'])->name('registrations.reject');
    Route::get('/cycle-settings', [SupplementaryCycleController::class, 'edit'])->name('cycle-settings.edit');
    Route::post('/cycle-settings', [SupplementaryCycleController::class, 'save'])->name('cycle-settings.save');

    // Course Management (CRUD)
    Route::post('/courses', [ChiefExaminerController::class, 'storeCourse'])->name('courses.store');
    Route::put('/courses/{course}', [ChiefExaminerController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{course}', [ChiefExaminerController::class, 'destroyCourse'])->name('courses.destroy');

    // Global Registration Toggle (Applications Open / Closed)
    Route::post('/toggle-applications', [ChiefExaminerController::class, 'toggleApplications'])->name('toggle-applications');

    // Supplementary Applications Review & Decisions (Medical Reports & Letters)
    Route::get('/resit-requests/{resitRequest}/document/{type}', [ChiefExaminerController::class, 'viewDocument'])->name('resit-requests.document');
    Route::post('/resit-requests/{resitRequest}/approve', [ChiefExaminerController::class, 'approveSupplementary'])->name('resit-requests.approve');
    Route::post('/resit-requests/{resitRequest}/reject', [ChiefExaminerController::class, 'rejectSupplementary'])->name('resit-requests.reject');

    // Broadcast Announcements to Students & Email Dispatch
    Route::get('/broadcasts', [ChiefExaminerController::class, 'broadcastsIndex'])->name('broadcasts.index');
    Route::post('/broadcasts', [ChiefExaminerController::class, 'storeBroadcast'])->name('broadcasts.store');
    Route::delete('/broadcasts/{broadcast}', [ChiefExaminerController::class, 'destroyBroadcast'])->name('broadcasts.destroy');

    // Examination Timetable Management & Builder
    Route::get('/timetables', [ChiefExaminerController::class, 'timetablesIndex'])->name('timetables.index');
    Route::post('/timetables', [ChiefExaminerController::class, 'storeTimetable'])->name('timetables.store');
    Route::put('/timetables/{timetable}', [ChiefExaminerController::class, 'updateTimetable'])->name('timetables.update');
    Route::post('/timetables/{timetable}/toggle-status', [ChiefExaminerController::class, 'toggleTimetableStatus'])->name('timetables.toggle-status');
    Route::delete('/timetables/{timetable}', [ChiefExaminerController::class, 'destroyTimetable'])->name('timetables.destroy');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.authenticate');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/verify-password-reset', [PasswordResetController::class, 'showVerificationForm'])->name('password.verify.form');
    Route::post('/verify-password-reset', [PasswordResetController::class, 'verifyResetCode'])->name('password.verify')->middleware('throttle:5,1');
    Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update')->middleware('throttle:10,1');
});

// Paystack Asynchronous Webhook
Route::post('/paystack/webhook', [PaymentController::class, 'handleWebhook'])->name('paystack.webhook');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Member 2: Student Registration & Student Portal Routes
Route::middleware(['auth', 'role:student', 'student.cycle'])->prefix('student')->name('student.')->group(function () {
    // Student Personal Dashboard
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');

    // Available Courses Catalog & Details
    Route::get('/courses', [StudentPortalController::class, 'courses'])->name('courses');
    // Registration hub page (replaces Available Courses)
    Route::get('/registration', [StudentPortalController::class, 'registration'])->name('registration');
    Route::get('/courses/{course}/register', [StudentPortalController::class, 'showRegistrationForm'])->name('registerCourseForm');

    // Registration Submission & Duplicate Prevention
    Route::post('/registrations', [StudentPortalController::class, 'storeRegistration'])->name('storeRegistration');

    // Member 3: Mobile Money Payment Flow (PaymentController)
    Route::post('/registrations/{registration}/pay', [PaymentController::class, 'initiate'])->name('pay');
    Route::get('/payments/callback', [PaymentController::class, 'handleGatewayCallback'])->name('payments.callback');
    Route::get('/payments/{payment}/authorize', [PaymentController::class, 'showAuthorize'])->name('payments.authorize');
    Route::get('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/{payment}/process', [PaymentController::class, 'processCallback'])->name('payments.process');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');

    // Published Examination Timetables
    Route::get('/timetable', [StudentPortalController::class, 'timetable'])->name('timetable');

    // Printable Examination Admission Slip
    Route::get('/registrations/{registration}/slip', [StudentPortalController::class, 'examSlip'])->name('examSlip');

    // Official Announcements & Updates from Chief Examiner
    Route::get('/announcements', [StudentPortalController::class, 'announcements'])->name('announcements');

    // New Resit / Supplementary Exam Form
    Route::get('/resit-form', [\App\Http\Controllers\ResitExamController::class, 'showForm'])->name('resit-form');
    Route::post('/resit-form', [\App\Http\Controllers\ResitExamController::class, 'submitForm'])->name('resit-form.submit');

    // View Student Profile Page
    Route::get('/profile', [StudentProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [StudentProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/email', [StudentProfileController::class, 'requestEmailChange'])
    ->name('profile.email.request');

    Route::patch('/profile/email/confirm', [StudentProfileController::class, 'confirmEmailChange'])
        ->name('profile.email.confirm');
});
