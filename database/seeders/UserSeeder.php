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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Simpeg',
            'email' => 'adminsimpeg@gmail.com',
            'role' => 'admin_simpeg',
            'password' => Hash::make('admin123'),
        ]);

        User::factory()->create([
            'name' => 'Admin DLH',
            'email' => 'admindlh@gmail.com',
            'role' => 'admin_sampah',
            'password' => Hash::make('admin123'),
        ]);
    }
}
