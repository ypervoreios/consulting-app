<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name'=>'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'akis@aplan.gr'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Assign admin role
        $admin->assignRole($adminRole);
    }
}
