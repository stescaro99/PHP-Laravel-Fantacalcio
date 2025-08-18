<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Le mie squadre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">Home</a>
    <div class="navbar-nav">
      <a class="nav-link" href="{{ route('players.index') }}">Giocatori</a>
      <a class="nav-link" href="{{ route('stats.index') }}">Statistiche</a>
      <a class="nav-link active" aria-current="page" href="{{ route('team.index') }}">Le mie squadre</a>
      <a class="nav-link" href="{{ route('team.create') }}">Nuova squadra</a>
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

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Le mie squadre</h2>
    <a class="btn btn-primary" href="{{ route('team.create') }}">+ Crea nuova squadra</a>
  </div>

  @if($teams->isEmpty())
    <div class="alert alert-info">Non hai ancora creato squadre. Inizia da <a href="{{ route('team.create') }}">qui</a>.</div>
  @else
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Giocatori</th>
            <th>Budget</th>
            <th>Aggiornata</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($teams as $t)
          <tr>
            <td>{{ $t->name }}</td>
            <td>{{ $t->players_count }}</td>
            <td>{{ $t->budget }}</td>
            <td>{{ $t->updated_at?->format('d/m/Y H:i') }}</td>
            <td>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('team.edit', ['id' => $t->id]) }}">Modifica</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
</body>
</html>
