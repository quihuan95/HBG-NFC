<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_DEFAULT_EMAIL', 'admin@thehbg.com')],
            [
                'name' => 'Admin The HBG',
                'password' => Hash::make(env('ADMIN_DEFAULT_PASSWORD', 'password12345')),
            ]
        );
    }
}
