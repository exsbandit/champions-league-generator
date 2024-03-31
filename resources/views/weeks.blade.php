@extends('layouts.app')

@section('content')
    <h1>Haftalar</h1>

    @for ($week = 1; $week <= $totalWeeks; $week++)
        <h2>Week {{ $week }}</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ev Sahibi</th>
                    <th>Deplasman</th>
                    <th>Skor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fixtures[$week] as $fixture)
                    <tr>
                        <td>{{ $fixture->homeTeam->name }}</td>
                        <td>{{ $fixture->awayTeam->name }}</td>
                        <td>{{ $fixture->home_team_score }} - {{ $fixture->away_team_score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endfor
@endsection