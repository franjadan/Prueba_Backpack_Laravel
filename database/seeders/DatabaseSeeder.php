<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('qwerasdf'), // password
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::factory(10)->create();
    }
}
