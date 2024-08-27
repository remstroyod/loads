<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MiscellaneouSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = \App\Models\Miscellaneou::insert([
            [
                'name'  => 'Jumbo road train',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }
}
