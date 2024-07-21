<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\Project;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => 1,
            'name' => 'Paolo',
            'email' => 'hello@paolocatalan.com',
            'password' => bcrypt('U9HsTpbxkbQjJVB'),
            'email_verified_at' => now()
        ]);

        Project::factory(9)->create();
        Booking::factory(12)->create();
    }
}
