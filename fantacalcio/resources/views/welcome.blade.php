<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fantacalcio - Home</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        @endif
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Fantacalcio</a>
                <div class="navbar-nav">
                    <a class="nav-link" href="{{ route('players.index') }}">Giocatori</a>
                    <a class="nav-link" href="{{ route('stats.index') }}">Statistiche</a>
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
                        @if (Route::has('login'))
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h1 class="h3 mb-3">Benvenuto</h1>
                            <p class="text-muted">Scegli una sezione per iniziare:</p>
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-4">
                                    <a href="{{ route('players.index') }}" class="btn btn-primary w-100">Lista Giocatori</a>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <a href="{{ route('stats.index') }}" class="btn btn-outline-primary w-100">Statistiche</a>
                                </div>
                                @auth
                                <div class="col-sm-6 col-md-4">
                                    <a href="{{ route('team.builder') }}" class="btn btn-success w-100">Team Builder</a>
                                </div>
                                @endauth
                                <div class="col-sm-6 col-md-4">
                                    <a href="{{ route('players.import.form') }}" class="btn btn-outline-secondary w-100">Import Giocatori</a>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <a href="{{ route('stats.import.form') }}" class="btn btn-outline-secondary w-100">Import Statistiche</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
