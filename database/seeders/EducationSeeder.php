<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Educations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EducationSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear the educations table
        DB::table('education_vrmodel')->truncate(); // Clear pivot table first
        DB::table('educations')->truncate();

        // Enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $educations = [
            'Auto',
            'Automatik',
            'Business',
            'Data',
            'Elektriker',
            'Elektronik',
            'Gastronomi',
            'Industriteknik',
            'Operatør',
            'Produktør',
            'Smed',
            'Struktør',
            'Tømrer',
            'VVS'
        ];

        foreach ($educations as $education) {
            Educations::create([
                'title' => $education
            ]);
        }
    }
}