<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin user
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('abc123'),
            'role_id' => Role::getIdByCode(Role::SUPER_ADMIN),
        ]);

        // Normal user 1
        User::create([
            'name' => 'Normal User 1',
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('abc123'),
            'role_id' => Role::getIdByCode(Role::CUSTOMER),
        ]);

        // Normal user 2
        User::create([
            'name' => 'Normal User 2',
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => Hash::make('abc123'),
            'role_id' => Role::getIdByCode(Role::CUSTOMER),
        ]);
    }
}
