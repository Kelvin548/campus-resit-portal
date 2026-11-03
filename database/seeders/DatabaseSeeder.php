<?php

namespace Database\Seeders;

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
        // 1. Create Default Administrative Users
        User::updateOrCreate(
            ['email' => 'admin@campus.edu'],
            [
                'full_name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone_number' => '+233240000001',
            ]
        );

        User::updateOrCreate(
            ['email' => 'examiner@campus.edu'],
            [
                'full_name' => 'Prof. Kwame Mensah (Chief Examiner)',
                'password' => Hash::make('password123'),
                'role' => 'chief_examiner',
                'phone_number' => '+233240000002',
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

        // 3. Create Sample Course Catalog
        $courses = [
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

        // 4. Add the initial cycle only when no current cycle is already configured.
        if (! SupplementaryCycle::query()->where('is_current', true)->exists()) {
            SupplementaryCycle::create([
                'name' => 'November 2026 Supplementary Cycle',
                'registration_starts_at' => '2026-11-06 15:30:00',
                'registration_ends_at' => '2026-11-10 15:35:00',
                'student_access_ends_at' => '2026-11-12 15:40:00',
                'is_current' => true,
            ]);
        }
    }
}
