# Fixture Generator

Introducing a dynamic football simulation project:

In this project, new teams can be added to the database, and fixtures are generated randomly. The simulated matches of the week are then played, and the results are instantly reflected in the league standings. The updated standings are displayed in a user-friendly format.

Key Features:

Match Simulation: Simulates the matches based on team strengths data.
Real-time Standings Updates: Instantly reflects the results of simulated matches on the league table.

User-friendly Interface: Provides a clear and easy-to-understand view of the league standings.

## How to use

php artisan migrate:fresh --seed

On the page you can access by going to 'host/league-table', you can reset the fixture with the "Reset Fixture" button or play the next week's matches with the "Next" button located on the right side.

## API Usage

#### Get Team List

```http
  GET /league-table
```

| Description          |
| :------------------- |
| **All team listed**. |
