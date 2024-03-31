<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'power_rating'];


    public function homeFixtures()
    {
        return $this->hasMany(Fixture::class, 'home_team_id')->whereNotNull('home_team_score')->orderBy('week');
    }

    public function awayFixtures()
    {
        return $this->hasMany(Fixture::class, 'away_team_id')->whereNotNull('home_team_score')->orderBy('week');
    }

    public function allFixtures()
    {
        return $this->homeFixtures->merge($this->awayFixtures)->sortBy('week');;
    }

    public function totalPoints()
    {
        $pointDetails = [
            'won' => 0,
            'draw' => 0,
            'lost' => 0,
            'gf' => 0,
            'ga' => 0,
        ];
        foreach ($this->allFixtures() as $fixture) {
            $pointDetails = $this->pointDetail($fixture, $pointDetails);
        }
        return $pointDetails;
    }

    private function pointDetail(Fixture $fixture, $pointDetails)
    {
        if ($fixture->home_team_id === $this->id) {
            if ($fixture->home_team_score > $fixture->away_team_score) {
                $pointDetails['won']++;
                $pointDetails['gf'] += $fixture->home_team_score;
                $pointDetails['ga'] += $fixture->away_team_score;
            } else if ($fixture->home_team_score === $fixture->away_team_score) {
                $pointDetails['draw']++;
                $pointDetails['gf'] += $fixture->home_team_score;
                $pointDetails['ga'] += $fixture->away_team_score;
            } else {
                $pointDetails['lost']++;
                $pointDetails['gf'] += $fixture->home_team_score;
                $pointDetails['ga'] += $fixture->away_team_score;
            }
        } else {
            if ($fixture->away_team_score > $fixture->home_team_score) {
                $pointDetails['won']++;
                $pointDetails['gf'] += $fixture->away_team_score;
                $pointDetails['ga'] += $fixture->home_team_score;
            } else if ($fixture->home_team_score === $fixture->away_team_score) {
                $pointDetails['draw']++;
                $pointDetails['gf'] += $fixture->away_team_score;
                $pointDetails['ga'] += $fixture->home_team_score;
            } else {
                $pointDetails['lost']++;
                $pointDetails['gf'] += $fixture->away_team_score;
                $pointDetails['ga'] += $fixture->home_team_score;
            }
        }
        return $pointDetails;
    }
}
