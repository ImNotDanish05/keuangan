<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        // Owner account for admin access
        if (!User::where('username', 'root')->exists()) {
            User::create([
                'name' => 'Root',
                'username' => 'root',
                'email' => 'root@example.test',
                'password' => Hash::make('Admin#1234'),
                'role' => 'owner',
                'is_approved' => true,
            ]);
        }
    }
}
