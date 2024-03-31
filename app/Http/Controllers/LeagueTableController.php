<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;

class LeagueTableController extends Controller
{
    public function index()
    {

        $fixtures = Fixture::all();
        $week = null;
        foreach ($fixtures as $fixture) {
            if (!$fixture->home_team_score) {
                $week = $fixture->week;
                break;
            }
        }

        $teams = Team::all();
        $standings = $this->calculateStandings($teams);
        $weekFixtures = [];
        if ($week > 1 || is_null($week)) {
            $weekFixtures = Fixture::all()->where('status', true);
        }

        return view('league-table', compact('standings', 'week', 'weekFixtures'));
    }

    private function calculateStandings($teams)
    {
        $standings = [];
        foreach ($teams as $team) {
            $fixtures = $team->allFixtures();

            $pointDetails = $team->totalPoints();

            $standings[] = [
                'team' => $team->name,
                'played' => $fixtures->count(),
                'won' => $pointDetails['won'],
                'drawn' => $pointDetails['draw'],
                'lost' => $pointDetails['lost'],
                'points' => $pointDetails['won'] * 3 + $pointDetails['draw'],
                'gf' => $pointDetails['gf'],
                'ga' => $pointDetails['ga'],
                'goal_difference' => $pointDetails['gf'] - $pointDetails['ga'],
            ];
        }

        usort($standings, function ($a, $b) {
            return $b['points'] <=> $a['points'];
        });

        return $standings;
    }
}
