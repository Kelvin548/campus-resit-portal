<?php

namespace Database\Seeders;

use App\Models\BroadcastMessage;
use App\Models\Course;
use App\Models\SupplementaryCycle;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with core foundation data.
     */
    public function run(): void
    {
        // 1. Create Chief Examination Officer User (Prof. Kwame Mensah)
        User::updateOrCreate(
            ['email' => 'examiner@campus.edu'],
            [
                'full_name' => 'Prof. Kwame Mensah (Chief Examination Officer)',
                'password' => Hash::make('password123'),
                'role' => 'chief_examiner',
                'phone_number' => '+233240000002',
            ]
        );

        // Keep system administrator account aligned with Chief Examiner permissions
        User::updateOrCreate(
            ['email' => 'admin@campus.edu'],
            [
                'full_name' => 'System Administrator (Academic Affairs)',
                'password' => Hash::make('password123'),
                'role' => 'chief_examiner',
                'phone_number' => '+233240000001',
            ]
        );

        // 2. Create Sample Students
        User::updateOrCreate(
            ['email' => 'student1@campus.edu'],
            [
                'full_name' => 'Kelvyn Kofi Doe',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id_number' => '2026000001',
                'phone_number' => '+233241234567',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student2@campus.edu'],
            [
                'full_name' => 'Akosua Serwaa',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'student_id_number' => '2026000002',
                'phone_number' => '+233249876543',
            ]
        );

        // 3. Seed Official USTED Courses — ICT Education, AI, Business, Social Sciences, Entrepreneurship
        $courses = [
            // ── ICT Education Core Courses ─────────────────────────────────────
            [
                'course_code' => 'ICT112',
                'course_name' => 'Principles of Programming',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 2',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ITC231',
                'course_name' => 'Database Concepts and Design',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 1',
                'registration_fee' => 180.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT232',
                'course_name' => 'Web Technology and Design',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 1',
                'registration_fee' => 180.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT244',
                'course_name' => 'Systems Analysis and Design',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 180.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT351',
                'course_name' => 'Computer Networks and Security',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 1',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT362',
                'course_name' => 'Object-Oriented Software Development',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 2',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT131',
                'course_name' => 'Computer Hardware and Networking',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 1',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT241',
                'course_name' => 'Mobile Application Development',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 180.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT311',
                'course_name' => 'Software Engineering and Project Management',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 1',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT321',
                'course_name' => 'Operating Systems and Computer Architecture',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 1',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT371',
                'course_name' => 'E-Commerce and Digital Business',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 2',
                'registration_fee' => 180.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT381',
                'course_name' => 'Information Systems Management',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 2',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            // ── Artificial Intelligence & Data Science ─────────────────────────
            [
                'course_code' => 'AI201',
                'course_name' => 'Artificial Intelligence and Machine Learning',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 220.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'AI301',
                'course_name' => 'Deep Learning and Neural Networks',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 2',
                'registration_fee' => 220.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT271',
                'course_name' => 'Cyber Security Fundamentals',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT281',
                'course_name' => 'Cloud Computing and Infrastructure',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ICT361',
                'course_name' => 'Internet of Things (IoT) and Embedded Systems',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 1',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            // ── Business & Entrepreneurship ────────────────────────────────────
            [
                'course_code' => 'BUS101',
                'course_name' => 'Principles of Business and Entrepreneurship',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 1',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'BUS211',
                'course_name' => 'Marketing and Digital Communication',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 1',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'BUS221',
                'course_name' => 'Financial Literacy and Accounting Basics',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 1',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'ENT101',
                'course_name' => 'Innovation and Technology Entrepreneurship',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 2',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'BUS231',
                'course_name' => 'Project Planning and Management',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            // ── Social Sciences & Education ────────────────────────────────────
            [
                'course_code' => 'SOC101',
                'course_name' => 'Introduction to Sociology and Social Studies',
                'credit_hours' => 2,
                'level' => 100,
                'semester' => 'Semester 1',
                'registration_fee' => 120.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'SOC201',
                'course_name' => 'Community Development and Civic Education',
                'credit_hours' => 2,
                'level' => 200,
                'semester' => 'Semester 1',
                'registration_fee' => 120.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'EDU101',
                'course_name' => 'Educational Psychology and Learning Theories',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 2',
                'registration_fee' => 130.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'EDU211',
                'course_name' => 'Curriculum Design and Assessment',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 2',
                'registration_fee' => 130.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            // ── Mathematics & Communication ────────────────────────────────────
            [
                'course_code' => 'MAT101',
                'course_name' => 'Applied Mathematics for Technology',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 1',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'course_code' => 'COM101',
                'course_name' => 'Technical Communication and Report Writing',
                'credit_hours' => 2,
                'level' => 100,
                'semester' => 'Semester 1',
                'registration_fee' => 120.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(25),
                'is_active' => true,
            ],
            // ── Retained test-compatibility courses ────────────────────────────
            // Retain foundation test courses for backward compatibility with feature test assertions
            [
                'course_code' => 'CS101',
                'course_name' => 'Introduction to Computer Science',
                'credit_hours' => 3,
                'level' => 100,
                'semester' => 'Semester 1',
                'registration_fee' => 150.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'course_code' => 'CS204',
                'course_name' => 'Data Structures and Algorithms',
                'credit_hours' => 3,
                'level' => 200,
                'semester' => 'Semester 1',
                'registration_fee' => 180.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'course_code' => 'CS305',
                'course_name' => 'Database Management Systems',
                'credit_hours' => 3,
                'level' => 300,
                'semester' => 'Semester 1',
                'registration_fee' => 200.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'course_code' => 'CS410',
                'course_name' => 'Software Engineering & Cloud Architecture',
                'credit_hours' => 4,
                'level' => 400,
                'semester' => 'Semester 1',
                'registration_fee' => 250.00,
                'registration_start' => now()->subDays(5),
                'registration_deadline' => now()->addDays(20),
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['course_code' => $course['course_code']],
                $course
            );
        }

        // 4. Ensure current active supplementary cycle is configured and open
        $cycle = SupplementaryCycle::query()->where('is_current', true)->first();
        if (! $cycle) {
            SupplementaryCycle::create([
                'name' => 'USTED November 2026 Supplementary & Resit Cycle',
                'registration_starts_at' => now()->subDays(2),
                'registration_ends_at' => now()->addDays(30),
                'student_access_ends_at' => now()->addDays(60),
                'is_current' => true,
                'is_open' => true,
            ]);
        } else {
            $cycle->update([
                'is_open' => true,
                'registration_starts_at' => now()->subDays(2),
                'registration_ends_at' => now()->addDays(30),
                'student_access_ends_at' => now()->addDays(60),
            ]);
        }

        // 5. Seed initial Chief Examiner Broadcast Notices
        $examiner = User::where('role', 'chief_examiner')->first();
        BroadcastMessage::updateOrCreate(
            ['title' => 'Welcome & Examination Registration Schedule (November 2026 Session)'],
            [
                'message' => "Welcome candidates. The supplementary and resit examination application window is now open. All registered candidates are advised to verify their course selections and proceed with Mobile Money payments. Timetables will be uploaded continuously.",
                'category' => 'general',
                'created_by' => $examiner?->id,
                'is_active' => true,
                'emails_sent_count' => 2,
            ]
        );
    }
}
