<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // change to a strong password
            'role' => 'owner', // must be one of the enum values: owner, tenant, both
            'is_admin' => 1,   // admin flag
        ]);
    }
}
