<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrailerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = \App\Models\Trailer::insert([
            [
                'name'  => 'Curtain-sided',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Cool skip',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Mega outrain-sider',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Paperline',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Box',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Double-deck',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => 'Frigo',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}
