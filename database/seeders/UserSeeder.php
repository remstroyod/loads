<?php

namespace Database\Seeders;

use App\Enums\UserStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = \App\Models\User::create([
            'name'                  => 'admin',
            'email'                 => 'admin@example.com',
            'password'              => Hash::make(123456),
            'email_verified_at'     => now(),
            'created_at'            => now(),
            'updated_at'            => now(),
            'status'                => UserStatusEnum::Active
        ]);

    }
}
