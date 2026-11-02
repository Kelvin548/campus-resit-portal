# Project Plan & Decisions Tracking: Campus Resit Portal
**Document:** `PP-edwin.md`  
**Branch:** `edwin`  
**Date Initialized:** 2026-10-31  
**Last Updated:** 2026-11-02  

---

## 1. Operating Rules & Workflow Protocol

1. **Explicit Command Rule**: Do not create, edit, or delete any file in the workspace unless an explicit command to do so is given by the user.
2. **Change Reporting Rule**: Whenever a file is created, modified, or deleted following an explicit command, a report must immediately be provided containing:
   - The file path/name edited, created, or deleted.
   - The line number range of code added, updated, or deleted.
   - A summary of the changes made.
3. **Continuous Plan Maintenance**: Keep this file (`PP-edwin.md`) continuously updated with all decisions made, pending decisions, work in progress, and remaining work with corresponding dates.
4. **Git & Team Integrity**:
   - Work on the dedicated `edwin` branch.
   - Do not commit environment configuration files (`.env`).
   - Coordinate shared files (database migrations, routes, models, layouts) to avoid merge conflicts.

---

## 2. Project Decisions Log

### A. Current Decisions (Made as of 2026-10-31)
| ID | Decision Item | Selected Option / Choice | Date | Rationale / Notes |
|---|---|---|---|---|
| **DEC-001** | Tracking & Planning File | Create and maintain `PP-edwin.md` | 2026-10-31 | Centralized source of truth for flow, decisions, and tasks. |
| **DEC-002** | Base Framework | Laravel (PHP) on XAMPP | 2026-10-31 | Pre-installed stack in `c:\xampp\htdocs\campus-resit-portal`. |
| **DEC-003** | Work Division Structure | 4-Member Split per specification | 2026-10-31 | Module separation across Auth/Courses, Student Portal, Payments, and Chief Examiner. |
| **DEC-004** | Change Reporting Protocol | Strict file & line range reports | 2026-10-31 | Full visibility of all modifications made to the repository. |
| **DEC-005** | Baseline Program Flow | Derived from `course_registration_program_flow_and_work_division.txt` | 2026-10-31 | Establishes student and chief examiner step-by-step life cycles. |
| **DEC-012** | Role Assignment: Member 2 | Assigned to Edwin: Student Registration & Student Portal | 2026-10-31 | Focus on course display, registration forms, submission handling, duplicate checks, student dashboard, and timetable views. |
| **DEC-013** | Database Migration & Test Seeder Setup | Executed `migrate:fresh --seed` against MySQL `campus_resit_portal` | 2026-11-02 | Seeded 7 core tables: users, courses, registrations, payments, exams, timetables, results with default test accounts. |
| **DEC-014** | Member 2 Student Portal Implementation | Built StudentPortalController, AuthController, Tailwind layouts, registration forms, duplicate prevention, payment simulation, and exam slip | 2026-11-02 | Fulfills Member 2 module specifications completely and verified via automated Feature tests. |
| **DEC-015** | Session Driver Configuration | Configured `SESSION_DRIVER=file` in local `.env` | 2026-11-02 | Uses file-based sessions in local storage to prevent session table missing errors. |

### B. Decisions to Make Later (Pending / Under Review)
| ID | Decision Needed | Options Available | Target Date | Status |
|---|---|---|---|---|
| **DEC-006** | Mobile Money Gateway Selection | Paystack (Test), Flutterwave (Test), Hubtel, or Mock Gateway | 2026-11-02 | Pending Discussion |
| **DEC-007** | Chief Examiner Approval Mode | Option A: Auto-approve after successful payment.<br>Option B: Manual approval/rejection required per registration. | 2026-11-02 | Pending Discussion |
| **DEC-008** | Registration Limit per Student | Single course per submission vs. multi-course cart/batch registration | 2026-11-03 | Pending Discussion |
| **DEC-009** | Timetable Conflict & Venue Capacity Handling | Automated clash detection vs. manual assignment by Chief Examiner | 2026-11-04 | Pending Discussion |
| **DEC-010** | Email Delivery Service | Mailpit/Log (Local dev), Mailtrap, or live SMTP provider | 2026-11-04 | Pending Discussion |
| **DEC-011** | Frontend UI Stack | Blade with Tailwind CSS vs. Blade with Bootstrap 5 | 2026-11-02 | Pending Discussion |

---

## 3. Program Flow (Reference Baseline)

*Source: `course_registration_program_flow_and_work_division.txt`*

### 3.1 Student Program Flow
1. **Access Site**: Student visits the website.
2. **Auth**: Student registers for an account or logs in.
3. **Course Exploration**: Student views available resit courses and associated registration fees.
4. **Course Selection**: Student selects the course(s) to register for.
5. **Eligibility & Period Validation**: System checks that the registration period is open and deadline has not passed.
6. **Form Submission**: Student completes and submits the registration form.
7. **Record Initialization**: System generates the registration record with initial status (`pending_payment`).
8. **Payment Checkout**: Student proceeds to checkout.
9. **MoMo Gateway Trigger**: System initiates Mobile Money payment request via the test payment gateway.
10. **MoMo Prompt**: Student receives an authorization prompt on their mobile device and approves/declines.
11. **Gateway Callback/Webhook**: Payment gateway notifies system of payment outcome.
12. **Verification & Status Update**: System verifies transaction token/reference and sets status (`paid`, `failed`, or `pending`).
13. **Failure/Retry Path**: If payment fails/pendings, student can view status and retry if registration period is valid.
14. **Awaiting Examiner Action**: If payment succeeds, registration status changes to `awaiting_examiner_action`.
15. **Timetable & Decision Processing**: Chief Examiner processes registration and assigns timetable details.
16. **Notification**: System triggers email notification once decision/timetable is published.
17. **Status & Details Access**: Student logs into the portal to view confirmation, examination date, time, and venue.
18. **Download**: Student views or downloads the published examination slip/timetable.

### 3.2 Chief Examiner Program Flow
1. **Access & Login**: Chief Examiner logs into the website.
2. **Role Verification**: System verifies credentials and Chief Examiner role.
3. **Dashboard Overview**: Chief Examiner enters administrative dashboard.
4. **Registration Filtering**: Views registered students filtered by course or academic/resit period.
5. **Paid Registration Review**: Reviews list of confirmed paid registrations.
6. **Approval Processing**: If individual approval is enabled, approves or rejects each registration.
7. **Timetable Preparation**: Prepares examination timetable slots.
8. **Schedule Assignment**: Assigns examination date, start/end time, and venue/room to each course.
9. **Review & Publication**: Chief Examiner reviews timetable and hits "Publish".
10. **Student Availability**: Published schedule becomes visible to all approved students.
11. **Email Broadcast**: System dispatches automated email notifications with full exam details.
12. **Audit & Reporting**: Chief Examiner monitors published timetables and associated enrolled students.

---

## 4. Division of Work (4 Modules)

- **MEMBER 1: Authentication, Users and Course Management**
  - Student, Administrator, and Chief Examiner login and role-based access control (RBAC).
  - Student profile and user account management.
  - Course CRUD operations (Create, Read, Update, Delete).
  - Setting course registration fees and registration deadlines.
  - Enforcing registration cut-off logic (blocking registration past deadline).
  - Shared master layout and main navigation structure.

- **MEMBER 2: Student Registration and Student Portal (Edwin)**
  - Display available courses and fee breakdown to students.
  - Course registration forms and validation.
  - Registration submission and record storage.
  - Duplicate registration prevention logic.
  - Student personal dashboard.
  - Registration status tracking (submitted, paid, approved, scheduled).
  - Examination timetable and venue display.

- **MEMBER 3: Mobile Money Payment Integration**
  - Payment gateway test environment integration (Mobile Money API).
  - Initiating payment requests and prompts.
  - Handling webhooks and asynchronous callbacks.
  - Transaction verification and idempotency (prevent double processing).
  - Payment status updates (`pending`, `successful`, `failed`).
  - Payment history, receipts, and linking transactions to registrations.

- **MEMBER 4: Chief Examiner, Timetable and Email Notifications**
  - Chief Examiner dashboard.
  - Displaying and filtering paid registrations.
  - Registration review and approval/rejection actions.
  - Timetable builder: assigning exam dates, times, and venues.
  - Timetable publishing workflow.
  - Email notification engine for registration approvals and timetable releases.

---

## 5. Work Roadmap & Tasks (What Is Left to Do)

| Status | Phase | Task Description | Target Date | Assignee / Notes |
|:---:|---|---|:---:|---|
| [x] | Phase 0 | Initialize project tracking file (`PP-edwin.md`) | 2026-10-31 | Edwin |
| [ ] | Phase 1 | Finalize architecture decisions (DEC-006 to DEC-011) | 2026-11-02 | Team / Edwin |
| [x] | Phase 1 | Design database ERD & schema migrations (Users, Roles, Courses, Registrations, Payments, Timetables) | 2026-11-02 | Shared (Completed & Seeded) |
| [x] | Phase 2 | Setup user roles & authentication (Student, Chief Examiner, Admin) | 2026-11-02 | Edwin (Auth controllers & quick-login ready) |
| [ ] | Phase 2 | Build Course Management with fees and deadline constraints | 2026-11-07 | Member 1 |
| [x] | Phase 2 | Build Student Portal & course registration workflow | 2026-11-02 | Member 2 (Edwin) |
| [ ] | Phase 3 | Implement Mobile Money payment gateway test integration & webhooks | 2026-11-12 | Member 3 |
| [ ] | Phase 3 | Implement Chief Examiner dashboard, review & timetable management | 2026-11-14 | Member 4 |
| [ ] | Phase 4 | Implement email notifications for status updates and timetable publishing | 2026-11-16 | Member 4 |
| [ ] | Phase 4 | End-to-end integration testing across all 4 modules | 2026-11-18 | Team / Edwin |
| [ ] | Phase 5 | Final system verification, UI polishing, and merge preparation | 2026-11-20 | Team / Edwin |

---

## 6. Changelog & Activity Audit

| Timestamp (UTC/Local) | Action | File Affected | Line Range | Description |
|---|---|---|---|---|
| 2026-10-31 | File Created | `PP-edwin.md` | Lines 1–146 | Initialized project tracking document with operating rules, decision logs, full baseline program flows, work division, and roadmap. |
| 2026-10-31 | File Updated | `PP-edwin.md` | Lines 34, 98, 134, 148 | Recorded DEC-012 assigning Member 2 (Student Registration & Student Portal) to Edwin; updated work division and roadmap. |
| 2026-11-02 | File Updated | `PP-edwin.md` | Lines 4, 35–36, 133–136, 151 | Logged DEC-013 & DEC-014; updated roadmap statuses for Member 2 completion; appended activity log. |
| 2026-11-02 | Database Initialized | MySQL Database | 7 Migrations | Ran `migrate:fresh --seed` creating users, courses, registrations, payments, exams, timetables, and results with default seed data. |
| 2026-11-02 | File Created | `app/Http/Controllers/AuthController.php` | Lines 1–121 | Authentication controller handling student/staff login, registration, role-based redirects, and session logout. |
| 2026-11-02 | File Created | `app/Http/Controllers/StudentPortalController.php` | Lines 1–290 | Student Portal controller handling dashboard, course catalog, registration form, duplicate prevention, payment simulation, timetable, and exam slip. |
| 2026-11-02 | File Created | `resources/views/layouts/app.blade.php` | Lines 1–152 | Master responsive layout with navigation, flash notifications, and Tailwind styling. |
| 2026-11-02 | File Created | `resources/views/auth/login.blade.php` | Lines 1–86 | Login page featuring one-click test account autofill buttons for Student, Examiner, and Admin. |
| 2026-11-02 | File Created | `resources/views/auth/register.blade.php` | Lines 1–76 | Student account self-registration form. |
| 2026-11-02 | File Created | `resources/views/student/dashboard.blade.php` | Lines 1–287 | Student dashboard with metrics, registered courses table, MoMo payment modal, and exam alerts. |
| 2026-11-02 | File Created | `resources/views/student/courses.blade.php` | Lines 1–155 | Resit course catalog with search, level filters, deadline countdowns, and registration triggers. |
| 2026-11-02 | File Created | `resources/views/student/register-course.blade.php` | Lines 1–101 | Course registration confirmation form with academic session selector and duplicate check notices. |
| 2026-11-02 | File Created | `resources/views/student/timetable.blade.php` | Lines 1–109 | Published examination timetable with venue and date details. |
| 2026-11-02 | File Created | `resources/views/student/exam-slip.blade.php` | Lines 1–165 | Printable official examination admission slip with payment verification and signature lines. |
| 2026-11-02 | File Updated | `routes/web.php` | Lines 1–48 | Configured auth routes and protected student portal route group with clean name aliases. |
| 2026-11-02 | File Created | `tests/Feature/StudentPortalTest.php` | Lines 1–135 | Automated Feature test suite verifying login, dashboard, course browsing, duplicate prevention, MoMo payments, and exam slip. |
| 2026-11-02 | Git Push | GitHub Remote (`origin/edwin`) | Commit `d3adf12` | Successfully staged, committed, and pushed all Member 2 modules and tracking files to GitHub remote branch `edwin`. |
| 2026-11-02 | Environment Updated | `.env` | Line 30 | Updated `SESSION_DRIVER=file` for local session persistence and cleared config cache. |
