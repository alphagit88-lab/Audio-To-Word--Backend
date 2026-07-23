<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Seed the initial admin account.
     * Credentials: admin@email.com / Test@123
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name'      => 'Administrator',
                'email'     => 'admin@email.com',
                'password'  => Hash::make('Test@123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        $this->command->info('Admin seeded: admin@email.com / Test@123');
    }
}
