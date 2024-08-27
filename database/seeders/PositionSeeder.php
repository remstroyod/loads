<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = \App\Models\Position::insert([
            [
                'name'  => 'Administration/management',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Accounts/finances',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Scheduling/planning',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Driver',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Management/proprietor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Logistics/transport allocation',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Contact person',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}
