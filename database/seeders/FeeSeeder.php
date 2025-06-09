<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fee;
use App\Models\School;
use App\Models\Classroom;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $classroom = Classroom::first();

        Fee::firstOrCreate([
            'school_id' => $school->id,
            'classroom_id' => $classroom->id,
        ], [
            'tuition' => 30000,
            'uniform' => 5000,
            'books' => 7000,
        ]);
    }
}