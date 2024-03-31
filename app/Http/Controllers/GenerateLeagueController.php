<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Http\Request;

class GenerateLeagueController extends Controller
{
    public function generateLeague()
    {
        if (Fixture::exists()) {
            return redirect()->route('league-table')->with('error', 'Lig zaten oluşturulmuş!');
        }

        $opponents = Team::all()->shuffle()->toArray();

        $roundCount = count($opponents) - 1;
        $matchesPerRoundCount = count($opponents) / 2;
        $alternate = false;
        $offsetArray = [];

        // Fixture listeleri
        $firstHalfSeasonFixtures = $this->generateFixtures(0, $opponents, $roundCount, $matchesPerRoundCount, $alternate, $offsetArray);
        $secondHalfSeasonFixtures = $this->generateFixtures($roundCount, $opponents, $roundCount, $matchesPerRoundCount, $alternate, $offsetArray);

        foreach ($firstHalfSeasonFixtures as $singleMatch) {
            $singleMatch['season'] = 1;
            Fixture::insert($singleMatch);
        }
        foreach ($secondHalfSeasonFixtures as $singleMatch) {
            $singleMatch['season'] = 2;
            Fixture::insert($singleMatch);
        }

        return redirect()->route('league-table')->with('success', 'Lig başarıyla oluşturuldu!');
    }

    private function generateFixtures($roundNoOffset, $opponents, $roundCount, $matchesPerRoundCount, &$alternate, &$offsetArray)
    {
        $fixtures = [];
        $offsetArray = $this->generateOffsetArray(count($opponents));

        for ($roundNo = 1; $roundNo <= $roundCount; $roundNo++) {
            $alternate = !$alternate;

            $homes = $this->getHomes($roundNo, $opponents, $offsetArray, $matchesPerRoundCount);

            $aways = $this->getAways($roundNo, $opponents, $offsetArray, $matchesPerRoundCount);


            for ($matchIndex = 0; $matchIndex < $matchesPerRoundCount; $matchIndex++) {

                if ($alternate) {
                    $fixtures[] = [
                        'week' => $roundNo + $roundNoOffset,
                        'home_team_id' => $opponents[$homes[$matchIndex]]['id'],
                        'away_team_id' => $opponents[$aways[$matchIndex]]['id'],
                    ];
                } else {
                    $fixtures[] = [
                        'week' => $roundNo + $roundNoOffset,
                        'home_team_id' => $opponents[$aways[$matchIndex]]['id'],
                        'away_team_id' => $opponents[$homes[$matchIndex]]['id'],
                    ];
                }

                if ($homes[$matchIndex] == $aways[$matchIndex]) {
                    echo 'Teams cannot play themselves';
                }
            }
        }

        return $fixtures;
    }

    private function generateOffsetArray($length)
    {
        $offsetArray = [];

        for ($i = 1; $i < $length; $i++) {
            $offsetArray[] = $i;
        }

        $offsetArray = array_merge($offsetArray, $offsetArray);

        return $offsetArray;
    }

    private function getHomes($roundNo, $opponents, $offsetArray, $matchesPerRoundCount)
    {
        $offset = count($opponents) - $roundNo;
        $homes = array_slice($offsetArray, $offset, $matchesPerRoundCount - 1);

        return array_merge([0], $homes);
    }

    private function getAways($roundNo, $opponents, $offsetArray, $matchesPerRoundCount)
    {
        $offset = (count($opponents) - $roundNo) + (count($opponents) / 2 - 1);
        $aways = array_slice($offsetArray, $offset, count($opponents) / 2);

        return array_reverse($aways);
    }

    public function resetLeague()
    {
        Fixture::truncate();

        return redirect()->route('generate-league');
    }


    public function generateNextWeek(Request $request)
    {

        $week = $request->input('week');
        $fixtureWeeks = Fixture::all()->where('week', $week);

        foreach ($fixtureWeeks as $match) {
            $scores = simulateMatch($match->homeTeam, $match->awayTeam);
            $match->update($scores);
        }

        return redirect()->route('league-table')->with('success', 'Sonraki hafta başarıyla oluşturuldu!');
    }
}
