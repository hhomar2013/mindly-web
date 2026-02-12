<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\subjects;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(CountriesSeeder::class);
        // $this->call(GovernorateSeeder::class);
        // $this->call(CitySeeder::class);

        subjects::query()->create([
            'name' => [
                'ar' => 'اللغه العربيه',
                'en' => 'Arabic Language'
            ]
        ]);
        subjects::query()->create([
            'name' => [
                'ar' => 'الرياضيات',
                'en' => 'Math'
            ]
        ]);

        $this->call([
            CountriesSeeder::class,
            GovernorateSeeder::class,
            CitySeeder::class,
            EducationSystemSeeder::class,
            EducationStageSeeder::class,
            StageGradeSeeder::class,
            SecondaryTrackSeeder::class,
            SecondaryBranchSeeder::class,
            SecondaryGradeSeeder::class,
            SecondarySubBranchSeeder::class,
            SecondarySpecializationSeeder::class,
            UniversityFacultySeeder::class,
            UniversityInstituteSeeder::class,
            UniversityAYSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
