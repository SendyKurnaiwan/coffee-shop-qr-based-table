<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'username' => 'adminsuper',
            'password' => 'password123', // This will be automatically hashed by the mutator
            'role' => 'owner',
        ]);
    }
}
