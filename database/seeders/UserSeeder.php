<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            User::ADMINISTRATOR => 'Administrator',
            User::ADMIN => 'Admin',
            User::WAREHOUSE => 'Warehouse',
            User::PRODUCTION => 'Production',
            User::SALES => 'Sales',
            User::MITRA => 'Mitra',
        ];

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('rahasia123'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($roles[User::ADMINISTRATOR]);

        // Create regular users
        User::factory()->count(50)->create();
    }
}
