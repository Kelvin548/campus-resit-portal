<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplementaryCycle;
use Carbon\Carbon;

class ForceOpenSupplementaryCycleSeeder extends Seeder
{
    public function run()
    {
        // Remove any existing cycles
        SupplementaryCycle::truncate();

        // Create a fresh cycle that is open for registration now
        SupplementaryCycle::create([
            'name' => 'Current Cycle (forced open)',
            'registration_starts_at' => Carbon::now()->subDays(1),
            'registration_ends_at'   => Carbon::now()->addDays(7),
            'student_access_ends_at' => Carbon::now()->addDays(14),
            'is_current' => true,
        ]);
    }
}
