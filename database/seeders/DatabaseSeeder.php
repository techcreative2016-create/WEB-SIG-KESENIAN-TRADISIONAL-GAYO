<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Master Admin
        User::updateOrCreate(
            ['email' => 'master@gayoheritage.id'],
            [
                'name' => 'Master Admin',
                'password' => bcrypt('password'),
                'role' => 'master',
            ]
        );

        // Regular Admin
        User::updateOrCreate(
            ['email' => 'admin@gayoheritage.id'],
            [
                'name' => 'Admin Seni',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
    }
}
