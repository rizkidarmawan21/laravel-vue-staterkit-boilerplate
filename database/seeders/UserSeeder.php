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
            'Administrator',
            'Kasir',
        ];

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('rahasia123'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole($roles[0]);

        // Create regular users
        User::factory()->count(50)->create();
    }
}
