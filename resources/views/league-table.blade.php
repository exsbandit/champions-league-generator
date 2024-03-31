@extends('layouts.app')

@section('content')
    <h1>Lig Tablosu</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sıra</th>
                <th>Takım</th>
                <th>Oynanan</th>
                <th>Galibiyet</th>
                <th>Beraberlik</th>
                <th>Mağlubiyet</th>
                <th>Puan</th>
                <th>AG</th>
                <th>YG</th>
                <th>Averaj</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($standings as $standing)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $standing['team'] }}</td>
                    <td>{{ $standing['played'] }}</td>
                    <td>{{ $standing['won'] }}</td>
                    <td>{{ $standing['drawn'] }}</td>
                    <td>{{ $standing['lost'] }}</td>
                    <td class="bold-gray">{{ $standing['points'] }}</td>
                    <td>{{ $standing['gf'] }}</td>
                    <td>{{ $standing['ga'] }}</td>
                    <td>{{ $standing['goal_difference'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

     <div class="d-flex justify-content-between">
        <form method="GET" action="{{ url('/reset-league') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Fikstürü Sıfırla</button>
        </form>

        @if ($week)
           <form  method="POST" action="{{ url('/generate-next-week') }}">
            @csrf
            <input type="hidden" name="week" value="{{ $week }}">
            <button  type="submit" class="btn btn-primary"> {{ $week == 1 ? 'İlk' : 'Bir Sonraki'}} Haftayı Oluştur({{ $week }}. Hafta)</button>
        </form> 
        @endif
    </div>
    
    @if ($weekFixtures)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ev Sahibi</th>
                    <th>Gol (Ev Sahibi)</th>
                    <th>Gol (Misafir)</th>
                    <th>Misafir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weekFixtures as $fixture)
                    <tr>
                        <td class="{{ $fixture->home_team_score > $fixture->away_team_score ? 'text-success' : ($fixture->home_team_score == $fixture->away_team_score  ? 'text-info' : 'text-danger') }}">{{ $fixture->homeTeam->name }}</td>
                        <td class="bold">{{ $fixture->home_team_score }}</td>
                        <td class="bold">{{ $fixture->away_team_score }}</td>
                         <td class="{{ $fixture->away_team_score > $fixture->home_team_score ? 'text-success' : ($fixture->home_team_score == $fixture->away_team_score  ? 'text-info' : 'text-danger') }}">{{ $fixture->awayTeam->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
     @endif
@endsection