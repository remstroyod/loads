<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = \App\Models\Tractor::insert([
            [
                'name'  => 'Standart tractor unit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Three-axle tractor unit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Mega tractor unit',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}
