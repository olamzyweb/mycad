<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        $admin = User::firstOrCreate([
            'email' => 'admin@prepstack.ng',
        ], [
            'name' => 'Main Admin',
            'password' => bcrypt('password'),
            'school_id' => $school->id,
            'type' => 'admin',
        ]);
        $admin->assignRole('admin');

        $subadmin = User::firstOrCreate([
            'email' => 'teacher@prepstack.ng',
        ], [
            'name' => 'Class Teacher',
            'password' => bcrypt('password'),
            'school_id' => $school->id,
            'type' => 'subadmin',
        ]);
        $subadmin->assignRole('subadmin');
    }
}
