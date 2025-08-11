<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Giocatore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <a href="{{ route('players.index') }}" class="btn btn-secondary mb-3">&larr; Torna alla lista</a>
        <h2>{{ $player->name }} <small class="text-muted">({{ $player->position }})</small></h2>
        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>Squadra:</strong> {{ $player->team }}</li>
            <li class="list-group-item"><strong>Ruolo Mantra:</strong> {{ $player->mantra_position }}</li>
            <li class="list-group-item"><strong>Quotazione attuale:</strong> {{ $player->quotation }}</li>
            <li class="list-group-item"><strong>Quotazione iniziale:</strong> {{ $player->initial_quotation }}</li>
            <li class="list-group-item"><strong>Differenza:</strong> {{ $player->difference }}</li>
            <li class="list-group-item"><strong>Valore:</strong> {{ $player->value }}</li>
            <li class="list-group-item"><strong>Quotazione attuale Mantra:</strong> {{ $player->mantra_quotation }}</li>
            <li class="list-group-item"><strong>Quotazione iniziale Mantra:</strong> {{ $player->initial_mantra_quotation }}</li>
            <li class="list-group-item"><strong>Differenza Mantra:</strong> {{ $player->mantra_difference }}</li>
            <li class="list-group-item"><strong>Valore Mantra:</strong> {{ $player->mantra_value }}</li>
        </ul>

        <h4>Statistiche</h4>
        @if($stats->isEmpty())
            <div class="alert alert-warning">Nessuna statistica disponibile per questo giocatore.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Stagione</th>
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
