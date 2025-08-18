<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiche Giocatori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- App Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">Home</a>
        <div class="navbar-nav">
          <a class="nav-link" href="{{ route('players.index') }}">Giocatori</a>
          <a class="nav-link active" aria-current="page" href="{{ route('stats.index') }}">Statistiche</a>
          @auth
            <a class="nav-link" href="{{ route('team.builder') }}">Team Builder</a>
          @endauth
          <a class="nav-link" href="{{ route('players.import.form') }}">Import Giocatori</a>
          <a class="nav-link" href="{{ route('stats.import.form') }}">Import Statistiche</a>
        </div>
        <div class="ms-auto navbar-nav">
          @auth
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-link nav-link p-0">Logout</button>
            </form>
          @else
            <a class="nav-link" href="{{ route('login') }}">Login</a>
            <a class="nav-link" href="{{ route('register') }}">Register</a>
          @endauth
        </div>
      </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Statistiche Giocatori</h2>
            <a href="{{ route('stats.import.form') }}" class="btn btn-success">Importa Statistiche</a>
        </div>
        <form method="GET" class="mb-4 row g-2 align-items-end">
            <div class="col-md-2">
                <label for="filter_season" class="form-label">Stagione</label>
                <input type="text" name="season" id="filter_season" class="form-control" value="{{ request('season') }}" placeholder="Stagione...">
            </div>
            <div class="col-md-2">
                <label for="filter_role" class="form-label">Ruolo</label>
                <select name="role" id="filter_role" class="form-select">
                    <option value="">Tutti</option>
                    <option value="P" {{ request('role') == 'P' ? 'selected' : '' }}>Portiere</option>
                    <option value="D" {{ request('role') == 'D' ? 'selected' : '' }}>Difensore</option>
                    <option value="C" {{ request('role') == 'C' ? 'selected' : '' }}>Centrocampista</option>
                    <option value="A" {{ request('role') == 'A' ? 'selected' : '' }}>Attaccante</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter_team" class="form-label">Squadra</label>
                <input type="text" name="team" id="filter_team" class="form-control" value="{{ request('team') }}" placeholder="Squadra...">
            </div>
            <div class="col-md-2">
                <label for="filter_name" class="form-label">Nome</label>
                <input type="text" name="name" id="filter_name" class="form-control" value="{{ request('name') }}" placeholder="Nome...">
            </div>
            <div class="col-md-2">
                <label for="order_by" class="form-label">Ordina per</label>
                <select name="order_by" id="order_by" class="form-select">
                    <option value="">--</option>
                    <option value="n_votes" {{ request('order_by') == 'n_votes' ? 'selected' : '' }}>Presenze</option>
                    <option value="average_vote" {{ request('order_by') == 'average_vote' ? 'selected' : '' }}>MV</option>
                    <option value="average_fantavote" {{ request('order_by') == 'average_fantavote' ? 'selected' : '' }}>FM</option>
                    <option value="goals" {{ request('order_by') == 'goals' ? 'selected' : '' }}>Gol</option>
                    <option value="goals_conceded" {{ request('order_by') == 'goals_conceded' ? 'selected' : '' }}>Gol subiti</option>
                    <option value="catched_penalties" {{ request('order_by') == 'catched_penalties' ? 'selected' : '' }}>Rig. parati</option>
                    <option value="taken_penalties" {{ request('order_by') == 'taken_penalties' ? 'selected' : '' }}>Rig. causati</option>
                    <option value="scored_penalties" {{ request('order_by') == 'scored_penalties' ? 'selected' : '' }}>Rig. segnati</option>
                    <option value="missed_penalties" {{ request('order_by') == 'missed_penalties' ? 'selected' : '' }}>Rig. sbagliati</option>
                    <option value="assists" {{ request('order_by') == 'assists' ? 'selected' : '' }}>Assist</option>
                    <option value="yellow_cards" {{ request('order_by') == 'yellow_cards' ? 'selected' : '' }}>Ammonizioni</option>
                    <option value="red_cards" {{ request('order_by') == 'red_cards' ? 'selected' : '' }}>Espulsioni</option>
                    <option value="own_goals" {{ request('order_by') == 'own_goals' ? 'selected' : '' }}>Autogol</option>
                </select>
            </div>
            <div class="col-md-1">
                <label for="order_dir" class="form-label">&nbsp;</label>
                <select name="order_dir" id="order_dir" class="form-select">
                    <option value="asc" {{ request('order_dir') == 'asc' ? 'selected' : '' }}>Crescente</option>
                    <option value="desc" {{ request('order_dir') == 'desc' ? 'selected' : '' }}>Decrescente</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Filtra</button>
            </div>
            <div class="col-md-1">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <a href="{{ route('stats.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
        @if($stats->isEmpty())
            <div class="alert alert-warning text-center">
                <h4>Nessuna statistica trovata</h4>
                <p>Non ci sono statistiche nel database. <a href="{{ route('stats.import.form') }}">Importa il primo file Excel</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Stagione</th>
                            <th>Nome</th>
                            <th>Ruolo</th>
                            <th>Squadra</th>
                            <th>Presenze</th>
                            <th>MV</th>
                            <th>FM</th>
                            <th>Gol</th>
                            <th>Gol subiti</th>
                            <th>Rig. parati</th>
                            <th>Rig. causati</th>
                            <th>Rig. segnati</th>
                            <th>Rig. sbagliati</th>
                            <th>Assist</th>
                            <th>Ammonizioni</th>
                            <th>Espulsioni</th>
                            <th>Autogol</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats as $stat)
                        <tr>
                            <td>{{ $stat->season }}</td>
                            <td>
                                @if($stat->player)
                                    <a href="{{ route('players.show', $stat->player->id) }}">{{ $stat->name }}</a>
                                @else
                                    {{ $stat->name }}
                                @endif
                            </td>
                            <td>{{ $stat->position }}</td>
                            <td>{{ $stat->team }}</td>
                            <td>{{ $stat->n_votes }}</td>
                            <td>{{ $stat->average_vote }}</td>
                            <td>{{ $stat->average_fantavote }}</td>
                            <td>{{ $stat->goals }}</td>
                            <td>{{ $stat->goals_conceded }}</td>
                            <td>{{ $stat->catched_penalties }}</td>
                            <td>{{ $stat->taken_penalties }}</td>
                            <td>{{ $stat->scored_penalties }}</td>
                            <td>{{ $stat->missed_penalties }}</td>
                            <td>{{ $stat->assists }}</td>
                            <td>{{ $stat->yellow_cards }}</td>
                            <td>{{ $stat->red_cards }}</td>
                            <td>{{ $stat->own_goals }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
