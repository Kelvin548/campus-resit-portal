# Project Plan & Progress Tracker: Course Registration and Examination Management System (`PP-kelvyn.md`)

**Project Name:** Campus Course Registration and Examination Management System (Resit Portal)  
**Lead Developer:** Kelvyn  
**Last Updated:** 2026-11-08  
**Current Status:** USTED Institutional Branding Update Complete; Co-partner Modules Integrated & Verified  

---

## 1. Operating Protocol & Workflow Rules

To ensure strict project coordination, maintain code integrity, and prevent unauthorized file modifications:

1. **Explicit Authorization Required:**
   - **No file may be created, edited, or deleted without the explicit command and confirmation from the user.**
   - Autonomous modification of repository files is strictly prohibited.
2. **Mandatory Change Reporting:**
   - Whenever any file is added, modified, or deleted upon receiving explicit user instruction, a formal change report must be returned immediately.
   - The report must state:
     - **File Path:** Absolute or relative repository path.
     - **Operation:** Created / Modified / Deleted.
     - **Line Number Ranges:** Exact start and end line numbers of code added, updated, or deleted.
     - **Summary:** Concise breakdown of code changes introduced.
3. **Continuous Plan Updates:**
   - `PP-kelvyn.md` serves as the single source of truth for all decisions (current and deferred), tasks in progress, and remaining backlog.
   - Any new program flow additions made to `course_registration_program_flow_and_work_division.txt` or decided collaboratively must be integrated into this document.

---

## 2. Program Flow Overview

### A. Student Program Flow
1. **Access & Authentication:** Student visits the portal, registers an account, or logs in.
2. **Course Discovery:** Student browses available courses/resits with credit hours, academic levels (100–400), semesters, and registration fees.
3. **Eligibility & Deadline Verification:** System checks `registration_start` and `registration_deadline` on courses to ensure registration window is active (`isRegistrationOpen()`).
4. **Registration Submission:** Student submits course registration; initial record created with status `pending_payment`.
5. **Mobile Money Payment Integration:** System initiates Mobile Money test payment via test gateway webhook/callback hooks.
6. **Authorization & Callback:** Student authorizes transaction; gateway sends callback to update payment status (`successful` / `failed`).
7. **Transaction Verification & Status Update:** Successful payment updates registration status to `paid` (or allows student to retry if failed).
8. **Chief Examiner Review:** Registration moves to the Chief Examiner queue awaiting approval.
9. **Notification & Timetable Access:** Student receives automated email when examination date, time, and venue are published.
10. **Examination Details & Results:** Student views/downloads official timetable and can view published results after exams.

### B. Chief Examiner Program Flow
1. **Access & Role Check:** Chief Examiner logs in; system verifies account role (`chief_examiner`).
2. **Examiner Dashboard:** Examiner views registration metrics with multi-field filters (by course, academic year, semester, payment status).
3. **Individual Registration Review:** Examiner exercises approval/rejection toggle for each paid student registration (with optional remarks).
4. **Timetable Builder:** Examiner schedules exams, resits, and supplementary quizzes:
   - Assigns course, academic year, semester, date, start time, end time, venue, and seating capacity.
   - Built-in conflict prevention scope (`scopeConflicting()`) checking against double-booking of venues at overlapping time slots.
5. **Timetable Publishing Engine:** Examiner reviews and officially changes status from `draft` to `published`.
6. **Automated Notification Dispatch:** System marks timetable visible to students and triggers automated Laravel Mailables (`ExamTimetablePublished`) dispatched to registered students.
7. **Result Management:** Examiner/admin enters student scores, computes letter grades, and publishes official results.

---

## 3. Work Division & Module Ownership

| Module / Member | Primary Responsibilities | Target Components |
|---|---|---|
| **Member 1: Authentication, Users & Course Management** | • Student, Admin & Chief Examiner authentication & RBAC<br>• Profile & user account management<br>• Course CRUD, credit hours, levels, fees<br>• Registration window (opening dates & hard deadlines)<br>• Shared layout, base templates & navigation | `app/Models/User.php`, `app/Models/Course.php`, Auth controllers, middleware, master layout blade templates |
| **Member 2: Student Registration & Portal** | • Available course catalog & fee display<br>• Course registration form submission & storage<br>• Duplicate registration prevention logic<br>• Student portal dashboard<br>• Real-time registration & payment status tracking<br>• Timetable & venue viewing interface | `app/Models/Registration.php`, RegistrationController, Student dashboard views |
| **Member 3: Mobile Money Payment Integration** | • Test payment gateway integration<br>• MoMo payment request initialization<br>• Webhook and callback handling<br>• Server-to-server transaction verification<br>• Idempotent payment processing (replay prevention)<br>• Payment history & receipt generation | `app/Models/Payment.php`, PaymentGatewayService, WebhookController, transaction tables |
| **Member 4 (Kelvyn): Chief Examiner, Timetable, Notifications & Results** | • Chief Examiner dashboard & registration filtering<br>• Registration approval/rejection toggle with remarks<br>• Timetable builder (date, time, venue assignment, seating limit)<br>• Venue conflict / double-booking check<br>• Timetable publishing workflow<br>• Automated email notification system (`ExamTimetablePublished`)<br>• Results grading and publishing schema | `app/Models/Timetable.php`, `app/Models/Exam.php`, `app/Models/Result.php`, ChiefExaminerController, Mailables |

---

## 4. Database Schema & Eloquent Architecture

All 9 migrations have been successfully migrated (`Batch 1 Ran`) in the local MySQL database (`campus_resit_portal`), and corresponding Eloquent Models are scaffolded:

### 1. `User` Model (`app/Models/User.php` -> `users` table)
- **Fillable:** `full_name`, `email`, `password`, `role`, `student_id_number`, `phone_number`
- **Role Helpers:** `isStudent()`, `isChiefExaminer()`, `isAdmin()`
- **Relations:** `registrations()`, `payments()`, `approvedRegistrations()`, `publishedTimetables()`, `enteredResults()`

### 2. `Course` Model (`app/Models/Course.php` -> `courses` table)
- **Fillable:** `course_code`, `course_name`, `credit_hours`, `level`, `semester`, `registration_fee`, `registration_start`, `registration_deadline`, `is_active`
- **Helpers:** `isRegistrationOpen()` (checks active state and date window)
- **Relations:** `registrations()`, `exams()`, `timetables()`

### 3. `Registration` Model (`app/Models/Registration.php` -> `registrations` table)
- **Fillable:** `user_id`, `course_id`, `semester`, `academic_year`, `status`, `approval_remarks`, `approved_by`, `approved_at`
- **Constants:** `STATUS_PENDING_PAYMENT`, `STATUS_PAID`, `STATUS_APPROVED`, `STATUS_REJECTED`
- **Helpers:** `isPaid()`, `isApproved()`, `isRejected()`, `isPendingPayment()`
- **Relations:** `user()`, `course()`, `approvedBy()`, `payment()`, `payments()`, `results()`

### 4. `Payment` Model (`app/Models/Payment.php` -> `payments` table)
- **Fillable:** `registration_id`, `user_id`, `transaction_reference`, `amount`, `payment_method`, `phone_number`, `network`, `status`, `gateway_reference`, `gateway_response`, `paid_at`
- **Constants:** `STATUS_PENDING`, `STATUS_SUCCESSFUL`, `STATUS_FAILED`
- **Helpers:** `isSuccessful()`, `isPending()`, `isFailed()`
- **Relations:** `registration()`, `user()`

### 5. `Exam` Model (`app/Models/Exam.php` -> `exams` table)
- **Fillable:** `course_id`, `title`, `academic_year`, `semester`, `exam_type`, `duration_minutes`, `total_marks`, `status`
- **Constants:** `TYPE_RESIT`, `TYPE_REGULAR`, `TYPE_SUPPLEMENTARY_QUIZ`
- **Relations:** `course()`, `timetables()`, `results()`

### 6. `Timetable` Model (`app/Models/Timetable.php` -> `timetables` table)
- **Fillable:** `exam_id`, `course_id`, `academic_year`, `semester`, `exam_date`, `start_time`, `end_time`, `venue`, `seating_capacity`, `status`, `published_by`, `published_at`
- **Constants:** `STATUS_DRAFT`, `STATUS_PUBLISHED`
- **Helpers & Scopes:** `isPublished()`, `scopeConflicting($venue, $examDate, $startTime, $endTime, $excludeId)` (prevents double-booking halls)
- **Relations:** `exam()`, `course()`, `publishedBy()`

### 7. `Result` Model (`app/Models/Result.php` -> `results` table)
- **Fillable:** `registration_id`, `exam_id`, `score`, `grade`, `remarks`, `is_published`, `entered_by`, `published_at`
- **Relations:** `registration()`, `exam()`, `enteredBy()`

---

## 5. Decisions Log

### 5.1 Decisions Made (Updated 2026-11-01)

| Decision ID | Area | Decision Summary | Date Decided | Status |
|---|---|---|---|---|
| **DEC-001** | Stack & Framework | Laravel 11 with MySQL (via XAMPP). | 2026-10-31 | **Adopted** |
| **DEC-002** | Work Division | 4-member modular architecture. Kelvyn leads Member 4 (Chief Examiner, Timetables, Notifications, Results). | 2026-10-31 | **Adopted** |
| **DEC-003** | Git Workflow | Feature branches per module; local uncommitted `.env`. | 2026-10-31 | **Adopted** |
| **DEC-004** | Operation Guardrail | Strict user authorization required before creating/modifying/deleting files. Line-number level reporting after execution. | 2026-10-31 | **Enforced** |
| **DEC-005** | Payment Mode | Test Mobile Money gateway environment handling push prompts, webhooks, and status polling/verification. | 2026-10-31 | **Adopted** |
| **DEC-006** | State Machine | Registration lifecycle: `pending_payment` -> `paid` -> `approved` / `rejected`. | 2026-10-31 | **Adopted** |
| **DEC-007** | Migration Architecture | Consolidated migration schema with ordered timestamps executed in MySQL (Batch 1 Ran). | 2026-11-01 | **Adopted** |
| **DEC-008** | Timetable Conflict Check | Double-booking protection implemented in `Timetable` model via `scopeConflicting()` and composite DB index `[venue, exam_date]`. | 2026-11-01 | **Adopted** |
| **DEC-009** | Eloquent Relationship Strategy | Bi-directional relationships established across all models with proper fillables and casts. | 2026-11-01 | **Adopted** |

---

### 5.2 Decisions To Make Later (Upcoming Decisions)

| Decision ID | Topic / Question | Context & Options | Target Date | Status |
|---|---|---|---|---|
| **DEC-LAT-001** | Payment Gateway Provider | Select specific MoMo sandbox provider (e.g., Paystack, Flutterwave, Hubtel, or MTN MoMo API sandbox). | 2026-11-03 | **Pending Decision** |
| **DEC-LAT-002** | Individual Approval Auto-toggle | System config option: whether all paid registrations auto-approve or mandate individual Chief Examiner click. | 2026-11-04 | **Pending Decision** |
| **DEC-LAT-003** | Email Transport Configuration | Mailpit/Mailtrap vs SMTP server and queue worker setup (`QUEUE_CONNECTION=database`). | 2026-11-07 | **Pending Decision** |
| **DEC-LAT-004** | Timetable PDF Export Driver | Choose PDF generation library (e.g. `barryvdh/laravel-dompdf` vs native browser print styling). | 2026-11-08 | **Pending Decision** |

---

## 6. Work Breakdown, Timelines & Progress Tracking

### Phase 0: Project Initiation, Database & Models
- [x] Initial Laravel repository structure established *(Completed: 2026-10-30)*
- [x] Review program flow and division of labor document *(Completed: 2026-10-31)*
- [x] Create project tracking and decisions file `PP-kelvyn.md` *(Completed: 2026-10-31)*
- [x] Scaffold and validate database schema migrations for all 7 core tables *(Completed: 2026-11-01)*
- [x] Run database migrations into local MySQL database (`php artisan migrate`) *(Completed: 2026-11-01)*
- [x] Scaffold and configure Eloquent models (`User`, `Course`, `Registration`, `Payment`, `Exam`, `Timetable`, `Result`) with relationships and fillables *(Completed: 2026-11-01)*

---

### Phase 1: Authentication, Users & Course Management (Member 1)
*Target Window: 2026-11-01 to 2026-11-05*

- [x] **Task 1.1:** Scaffold User migration & model with roles (`student`, `chief_examiner`, `admin`) and phone number. *(Completed: 2026-11-01)*
- [ ] **Task 1.2:** Build user profile views and update logic. *(Date: 2026-11-02)*
- [x] **Task 1.3:** Scaffold Courses migration & model (`Course.php`: code, title, fee, registration_start, registration_deadline, credit_hours, level). *(Completed: 2026-11-01)*
- [ ] **Task 1.4:** Build Admin/Course management controller and views (CRUD operations, fee configuration, date deadlines). *(Date: 2026-11-03)*
- [ ] **Task 1.5:** Implement deadline enforcement middleware/helper to block expired registrations. *(Date: 2026-11-04)*
- [ ] **Task 1.6:** Finalize shared master layout, flash messaging, and navigation bar. *(Date: 2026-11-05)*

---

### Phase 2: Student Registration & Student Portal (Member 2)
*Target Window: 2026-11-05 to 2026-11-09*

- [x] **Task 2.1:** Scaffold `registrations` migration & model (`Registration.php`: student_id, course_id, status, academic_year, semester, approval status). *(Completed: 2026-11-01)*
- [ ] **Task 2.2:** Build student course catalog view with real-time fee and deadline displays. *(Date: 2026-11-06)*
- [ ] **Task 2.3:** Implement course registration submission form with duplicate registration validation. *(Date: 2026-11-07)*
- [ ] **Task 2.4:** Develop Student Dashboard displaying current registrations, payment statuses, and action prompts. *(Date: 2026-11-08)*
- [ ] **Task 2.5:** Create student timetable view interface displaying assigned examination date, time, and venue. *(Date: 2026-11-09)*

---

### Phase 3: Mobile Money Payment Gateway Integration (Member 3)
*Target Window: 2026-11-09 to 2026-11-13*

- [x] **Task 3.1:** Scaffold `payments` schema & model (`Payment.php`: transaction_reference, registration_id, amount, phone_number, network, status, gateway_response). *(Completed: 2026-11-01)*
- [ ] **Task 3.2:** Configure MoMo payment gateway credentials and client abstraction service in Laravel. *(Date: 2026-11-10)*
- [ ] **Task 3.3:** Implement Payment Checkout initiation endpoint and prompt handler. *(Date: 2026-11-11)*
- [ ] **Task 3.4:** Build secure webhook endpoint to receive asynchronous gateway callbacks. *(Date: 2026-11-11)*
- [ ] **Task 3.5:** Implement idempotent verification service to prevent replay attacks or duplicate records. *(Date: 2026-11-12)*
- [ ] **Task 3.6:** Build student payment history, status retry flow, and receipt generation view. *(Date: 2026-11-13)*

---

### Phase 4: Chief Examiner Dashboard, Timetables, Notifications & Results (Member 4 - Kelvyn)
*Target Window: 2026-11-13 to 2026-11-17*

- [x] **Task 4.0:** Scaffold `exams`, `timetables`, and `results` migrations and models (`Exam.php`, `Timetable.php`, `Result.php`). *(Completed: 2026-11-01)*
- [ ] **Task 4.1:** Build Chief Examiner Dashboard with filters (by course, academic year, payment status). *(Date: 2026-11-13)*
- [ ] **Task 4.2:** Implement registration approval/review actions (accept/reject toggle with remarks). *(Date: 2026-11-14)*
- [ ] **Task 4.3:** Build Timetable Builder with double-booking venue/time clash validation. *(Date: 2026-11-15)*
- [ ] **Task 4.4:** Implement timetable review and publication mechanism. *(Date: 2026-11-16)*
- [ ] **Task 4.5:** Build Laravel Mailable notifications (`ExamTimetablePublished`) triggered upon timetable publication. *(Date: 2026-11-17)*
- [ ] **Task 4.6:** Build Result Entry and Result Publishing engine. *(Date: 2026-11-17)*

---

### Phase 5: Team Integration, Testing & Final Verification
*Target Window: 2026-11-18 to 2026-11-22*

- [ ] **Task 5.1:** Cross-module route and migration integration review. *(Date: 2026-11-18)*
- [ ] **Task 5.2:** End-to-end testing: Student registration -> MoMo payment -> Examiner review -> Timetable publication -> Email alert. *(Date: 2026-11-19)*
- [ ] **Task 5.3:** Security checks: CSRF, authorization policies, payment tampering prevention. *(Date: 2026-11-20)*
- [ ] **Task 5.4:** User Acceptance Testing (UAT) & edge case handling (expired deadlines, declined payments). *(Date: 2026-11-21)*
- [ ] **Task 5.5:** Main branch integration & documentation wrap-up. *(Date: 2026-11-22)*

---

## 7. Audit & File Change Log

| Date | File Path | Action | Line Range | Description |
|---|---|---|---|---|
| 2026-10-31 | `PP-kelvyn.md` | Created | Lines 1–170 | Initial creation of project plan, protocol rules, decision registers, program flow, and work division tracker. |
| 2026-11-01 | `database/migrations/create_courses_table.php` | Deleted | Lines 1–9 | Removed untracked, non-timestamped, incomplete courses file. |
| 2026-11-01 | `database/migrations/2026_10_30_152425_create_users_table.php` | Modified | Lines 18–19 | Added `phone_number` and `rememberToken()` to support MoMo payments and standard auth sessions. |
| 2026-11-01 | `database/migrations/2026_10_31_160000_create_courses_table.php` | Created | Lines 1–35 | Scaffolding complete `courses` table with code, name, credit hours, level, semester, registration fee, deadlines, and active flag. |
| 2026-11-01 | `database/migrations/2026_10_31_163156_create_registrations_table.php` | Modified | Lines 20–23 | Added `status` (`pending_payment`, `paid`, `approved`, `rejected`), `approval_remarks`, `approved_by`, and `approved_at`. |
| 2026-11-01 | `database/migrations/2026_11_01_000001_create_payments_table.php` | Created | Lines 1–38 | Created `payments` table schema with transaction reference, amount, phone number, network, status, gateway reference, and indexes. |
| 2026-11-01 | `database/migrations/2026_11_01_000002_create_exams_table.php` | Created | Lines 1–35 | Created `exams` table schema with course foreign key, title, academic year, semester, exam type, duration, marks, and status. |
| 2026-11-01 | `database/migrations/2026_11_01_000003_create_timetables_table.php` | Created | Lines 1–40 | Created `timetables` table schema with course/exam relations, exam date, start/end time, venue, seating capacity, draft/published status, and double-booking index. |
| 2026-11-01 | `database/migrations/2026_11_01_000004_create_results_table.php` | Created | Lines 1–37 | Created `results` table schema with registration/exam relations, score, grade, remarks, publication flag, and grader foreign key. |
| 2026-11-01 | `database` (local MySQL) | Migrated | Batch 1 (9 migrations) | Successfully executed `php artisan migrate` into local MySQL `campus_resit_portal`. |
| 2026-11-01 | `app/Models/User.php` | Modified | Lines 1–110 | Configured fillables, role helper methods (`isStudent()`, `isChiefExaminer()`, `isAdmin()`), and oversight relationships. |
| 2026-11-01 | `app/Models/Course.php` | Created | Lines 1–92 | Created Course model with fillables, casts, registration window checker `isRegistrationOpen()`, and relations to registrations, exams, timetables. |
| 2026-11-01 | `app/Models/Registration.php` | Created | Lines 1–118 | Created Registration model with lifecycle status constants, helpers, and relations to user, course, approvedBy, payment, and results. |
| 2026-11-01 | `app/Models/Payment.php` | Created | Lines 1–83 | Created Payment model with status constants, casts, and relations to registration and user. |
| 2026-11-01 | `app/Models/Exam.php` | Created | Lines 1–75 | Created Exam model with exam type constants, status constants, and relations to course, timetables, results. |
| 2026-11-01 | `app/Models/Timetable.php` | Created | Lines 1–101 | Created Timetable model with double-booking clash checker scope `scopeConflicting()`, publication status helper, and relations. |
| 2026-11-01 | `app/Models/Result.php` | Created | Lines 1–66 | Created Result model with decimal score casts, publication status, and relations to registration, exam, and enteredBy user. |
| 2026-11-01 | `database/seeders/DatabaseSeeder.php` | Modified | Lines 1–105 | Configured core seed data: 4 users (Admin, Chief Examiner, 2 Students) and 4 initial courses (CS101, CS204, CS305, CS410). |
| 2026-11-01 | `PP-kelvyn.md` | Modified | Lines 1–272 | Added Partner Setup & Database Synchronization Guide and updated audit log. |
| 2026-11-03 | `Git Branch` | Merged | Fast-Forward | Integrated branches `edwin` (Member 2) and `felicia` (Member 1) cleanly into `main`. |
| 2026-11-03 | `public/images/ammusted-logo.jpg` | Created | Binary | Added official AMMUSTED crest logo asset for portal branding. |
| 2026-11-03 | `resources/views/layouts/app.blade.php` | Modified | Lines 21–65, 165 | Configured AMMUSTED wine (`#7a1326`) and gold theme in Tailwind, added logo to header, and updated institutional footer. |
| 2026-11-03 | `resources/views/auth/login.blade.php` | Modified | Lines 1–150 | Redesigned login interface with AMMUSTED branding, removed demo buttons, streamlined alert banner, and enhanced form readability. |
| 2026-11-03 | `resources/views/auth/staff.blade.php` | Modified | Lines 1–105 | Replaced generic blue styling with AMMUSTED wine theme and official crest for staff portal sign-in. |
| 2026-11-03 | `app/Http/Controllers/AuthController.php` | Modified | Lines 38–58 | Added flexible login identifier fallback supporting both email and student index numbers to prevent regressions. |
| 2026-11-03 | `tests/Feature/ExampleTest.php` | Modified | Line 17 | Updated root redirect assertion to `/login` ensuring 100% test suite pass rate. |
| 2026-11-03 | `PP-kelvyn.md` | Modified | Lines 1–305 | Documented AMMUSTED UI redesign, co-partner integration, and updated audit change log. |
| 2026-11-08 | System-wide (`layouts`, `auth/login`, `auth/staff`, `emails`, `PaymentController`, `PasswordResetMail`) | Modified | Multiple | Updated institutional identity from AAMUSTED to USTED ("University of Skills Training and Entrepreneurial Development") across all portal UI views, email templates, and controllers. |
| 2026-11-08 | `app/Http/Controllers/AuthController.php` & `tests/Feature/StaffLoginTest.php` | Modified / Created | Lines 60–108, 406–420 | Fixed Chief Examiner / Staff sign-in so credentials entered on either the primary login page or the staff login screen authenticate and redirect directly to their workspace dashboard. |
| 2026-11-08 | `resources/views/layouts/app.blade.php`, `chief-examiner/dashboard.blade.php`, `DatabaseSeeder.php` | Modified | Multiple | Shortened nav buttons to 'Dashboard', 'Broadcast', and 'Cycle Settings' with tailored CSS pills and icons; added 30-second smooth auto-dismiss timer to flash notifications; updated title, role, and seeder identity to 'Chief Examination Officer'. |
| 2026-11-08 | `resources/views/chief-examiner/dashboard.blade.php` | Modified | Line 13 | Simplified main banner title to 'Chief Examination Officer' by removing 'Academic Hub'. |
| 2026-11-08 | `ChiefExaminerController`, `resources/views/chief-examiner/timetables.blade.php`, `chief-examiner/dashboard.blade.php`, `layouts/app.blade.php`, `routes/web.php` | Created / Modified | Multiple | Built Chief Examination Officer Timetable Builder & Master Schedule with clash detection, venue allocation, one-click publishing, edit/delete actions, and instant student view integration. |
| 2026-11-08 | `app/Http/Controllers/StudentPortalController.php` & `tests/Feature/StudentPortalTest.php` | Modified | Lines 299–320 | Fixed student timetable QueryException by correctly reading registered course IDs from `registrations` and parsing `course_entries` JSON from `resit_requests`. |
| 2026-11-09 | `resources/views/chief-examiner/dashboard.blade.php` & `tests/Feature/ChiefExaminerAndStudentWorkflowTest.php` | Modified | Lines 350–445, 980–1110 | Added real-time instant search by course code and name in Course Management; enforced 15-course initial limit with 'View All' toggle button; verified via automated feature test. |

---

## 8. Partner Setup & Database Synchronization Guide

*Share these instructions with Member 1, Member 2, and Member 3 so they have the exact same database and sample data running on their machines.*

### How Team Members Get the Exact Same Database:
1. **Pull the latest project files from GitHub:**
   ```bash
   git pull origin main
   ```
2. **Ensure MySQL is running in XAMPP** and create the database in phpMyAdmin:
   - Database name: `campus_resit_portal`
   - Make sure their local `.env` has:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=campus_resit_portal
     DB_USERNAME=root
     DB_PASSWORD=
     ```
3. **Run the migrations and seed default data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

### Default Seeded Test Accounts (Password for all: `password123`):
- **Administrator:** `admin@campus.edu` (Role: `admin`)
- **Chief Examiner:** `examiner@campus.edu` (Role: `chief_examiner`)
- **Student 1:** `student1@campus.edu` (Student ID: `STU-2026-001`, Role: `student`)
- **Student 2:** `student2@campus.edu` (Student ID: `STU-2026-002`, Role: `student`)

### Default Seeded Courses:
- **CS101:** Introduction to Computer Science (Level 100, Fee: GH₵ 150.00)
- **CS204:** Data Structures and Algorithms (Level 200, Fee: GH₵ 180.00)
- **CS305:** Database Management Systems (Level 300, Fee: GH₵ 200.00)
- **CS410:** Software Engineering & Cloud Architecture (Level 400, Fee: GH₵ 250.00)
