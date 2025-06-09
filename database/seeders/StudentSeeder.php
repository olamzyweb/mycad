<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\School;
use App\Models\Classroom;
use App\Models\User;


class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $classroom = Classroom::first();

        // Create a user account for the student
        $user = User::firstOrCreate([
            'email' => 'student@example.com',
        ], [
            'name' => 'John Doe',
            'password' => bcrypt('password'),
            'type' => 'student',
            'school_id' => $school->id,
        ]);

        // Link the user to a student profile
        $student = Student::firstOrCreate([
            'user_id' => $user->id,
        ], [
            'school_id' => $school->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => $user->email,
            'admission_number' => 'ADM001',
            'date_of_birth' => '2010-05-10',
            'gender' => 'Male',
        ]);

        // Optionally assign student to a classroom
        if ($classroom) {
          $student->classrooms()->sync([
    $classroom->id => ['school_id' => $school->id]
]);
        }
    }
}

