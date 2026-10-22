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
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@household.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0300-1112223',
                'address' => 'Main Admin Office, Lahore',
            ]
        );

        // 2. Customer User
        User::firstOrCreate(
            ['email' => 'customer@household.com'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0300-4445556',
                'address' => 'House #45, Block B, Model Town, Lahore',
            ]
        );

        // 3. Service Provider User (approved, with availability)
        User::firstOrCreate(
            ['email' => 'provider@household.com'],
            [
                'name' => 'Ali Service Provider',
                'password' => Hash::make('password123'),
                'role' => 'provider',
                'phone' => '0300-7778889',
                'address' => 'Shop #12, Services Market, Lahore',
                'is_approved' => true,
                'available_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
                'available_from' => '09:00',
                'available_to' => '18:00',
                'service_areas' => 'Model Town, Johar Town, DHA, Gulberg — Lahore',
            ]
        );

        // 4. Pending Service Provider (demonstrates the admin approval flow)
        User::firstOrCreate(
            ['email' => 'pending.provider@household.com'],
            [
                'name' => 'Sara Pending Provider',
                'password' => Hash::make('password123'),
                'role' => 'provider',
                'phone' => '0300-9990001',
                'address' => 'Flat #3, Township, Lahore',
                'is_approved' => false,
            ]
        );
    }
}
