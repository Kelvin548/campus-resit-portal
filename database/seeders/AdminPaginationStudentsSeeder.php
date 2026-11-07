<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminPaginationStudentsSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            ['Ama Ofori', 'admin-pagination-student03@campus.edu'],
            ['Kojo Agyeman', 'admin-pagination-student04@campus.edu'],
            ['Abena Mensah', 'admin-pagination-student05@campus.edu'],
            ['Yaw Boateng', 'admin-pagination-student06@campus.edu'],
            ['Akua Owusu', 'admin-pagination-student07@campus.edu'],
            ['Kofi Asare', 'admin-pagination-student08@campus.edu'],
            ['Efua Appiah', 'admin-pagination-student09@campus.edu'],
            ['Kwame Frimpong', 'admin-pagination-student10@campus.edu'],
        ];

        foreach ($students as $offset => [$name, $email]) {
            $existingStudent = User::where('email', $email)->first();
            $indexNumber = $existingStudent?->student_id_number;

            if (! $indexNumber) {
                $candidateNumber = 2999000001 + $offset;

                while (User::where('student_id_number', (string) $candidateNumber)->exists()) {
                    $candidateNumber++;
                }

                $indexNumber = (string) $candidateNumber;
            }

            User::updateOrCreate(
                ['email' => $email],
                [
                    'full_name' => $name,
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'student_id_number' => $indexNumber,
                ]
            );
        }
    }
}
