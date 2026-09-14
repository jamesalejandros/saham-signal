<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('admin');

        $user = User::updateOrCreate(
            [
                'email' => 'user@example.com',
            ],
            [
                'name' => 'User Demo',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('user');
    }
}
