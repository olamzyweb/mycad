<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\School;
use App\Models\Classroom;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $classroom = Classroom::first();

        Subject::firstOrCreate([
            'name' => 'Mathematics',
            'school_id' => $school->id,
            'classroom_id' => $classroom->id,
        ]);
    }
}