<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            PositionSeeder::class,
            UserSeeder::class,
            TrailerSeeder::class,
            TractorSeeder::class,
            MiscellaneouSeeder::class,
            LanguageSeeder::class
        ]);
    }
}
