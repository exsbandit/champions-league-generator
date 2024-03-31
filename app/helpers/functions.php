<?php

function simulateMatch($homeTeam, $awayTeam)
{
    $homeTeamScore = rand(0, 4);
    $awayTeamScore = rand(0, 4);

    if ($homeTeam->power_rating > $awayTeam->power_rating) {
        $homeTeamScore += rand(0, 2);
    } elseif ($awayTeam->power_rating > $homeTeam->power_rating) {
        $awayTeamScore += rand(0, 2);
    }

    return [
        'home_team_score' => $homeTeamScore,
        'away_team_score' => $awayTeamScore,
        'status' => true
    ];
}
