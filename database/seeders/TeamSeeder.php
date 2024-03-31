<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            ['name' => 'Liverpool', 'power_rating' => 90],
            ['name' => 'Chelsea', 'power_rating' => 88],
            ['name' => 'Manchester City', 'power_rating' => 86],
            ['name' => 'Arsenals', 'power_rating' => 85],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}
