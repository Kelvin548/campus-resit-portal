<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;
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

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Member 2: Student Registration & Student Portal Routes
Route::middleware('auth')->prefix('student')->name('student.')->group(function () {
    // Student Personal Dashboard
    Route::get('/dashboard', [StudentPortalController::class, 'dashboard'])->name('dashboard');

    // Available Courses Catalog & Details
    Route::get('/courses', [StudentPortalController::class, 'courses'])->name('courses');
    Route::get('/courses/{course}/register', [StudentPortalController::class, 'showRegistrationForm'])->name('registerCourseForm');

    // Registration Submission & Duplicate Prevention
    Route::post('/registrations', [StudentPortalController::class, 'storeRegistration'])->name('storeRegistration');

    // Mobile Money Test Payment Initiation (Bridging Member 2 & 3)
    Route::post('/registrations/{registration}/pay', [StudentPortalController::class, 'pay'])->name('pay');

    // Published Examination Timetables
    Route::get('/timetable', [StudentPortalController::class, 'timetable'])->name('timetable');

    // Printable Examination Admission Slip
    Route::get('/registrations/{registration}/slip', [StudentPortalController::class, 'examSlip'])->name('examSlip');
});
