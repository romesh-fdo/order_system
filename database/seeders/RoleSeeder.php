<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role' => Role::SUPER_ADMIN, 'code' => 'SUPER_ADMIN'],
            ['role' => Role::CUSTOMER, 'code' => 'CUSTOMER'],
        ];

        // Insert roles into the database
        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                ['role' => $role['role']]
            );
        }
    }
}
