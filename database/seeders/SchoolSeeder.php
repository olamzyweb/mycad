<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::firstOrCreate([
            'name' => 'PrepStack Academy',
            'address' => '123 Example St',
            'email' => 'info@prepstack.ng',
            'phone' => '08012345678',
        ]);
    }
}