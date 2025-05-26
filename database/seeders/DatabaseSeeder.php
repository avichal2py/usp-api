<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'emp_id' => '10001',
            'name' => 'John Doe',
            'username' => 'JohnD',
            'password' => md5('admin123'),
            'email' => 'john.doe@example.com',
            'role' => '1'
        ]);
    }
}
