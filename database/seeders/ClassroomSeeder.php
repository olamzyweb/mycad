<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\School;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        Classroom::firstOrCreate([
            'name' => 'JSS 1',
            'school_id' => $school->id,
        ]);
    }
}
