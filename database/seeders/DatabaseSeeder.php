<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use App\Models\Project;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(3)
            ->has(Project::factory()->count(3), 'project')
            ->create();
        Booking::factory()->count(9)->create();
    }
}
