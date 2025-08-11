<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Giocatori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Lista Giocatori ({{ $players->count() }} giocatori)</h2>
                    <a href="{{ route('players.import.form') }}" class="btn btn-success">Importa Giocatori</a>
                </div>

                <!-- Filtro -->
                <form method="GET" class="mb-4 row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="filter_role" class="form-label">Ruolo</label>
                        <select name="role" id="filter_role" class="form-select">
                            <option value="">Tutti</option>
                            <option value="P" {{ request('role') == 'P' ? 'selected' : '' }}>Portiere</option>
                            <option value="D" {{ request('role') == 'D' ? 'selected' : '' }}>Difensore</option>
                            <option value="C" {{ request('role') == 'C' ? 'selected' : '' }}>Centrocampista</option>
                            <option value="A" {{ request('role') == 'A' ? 'selected' : '' }}>Attaccante</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_team" class="form-label">Squadra</label>
                        <input type="text" name="team" id="filter_team" class="form-control" value="{{ request('team') }}" placeholder="Squadra...">
                    </div>
                    <div class="col-md-3">
                        <label for="filter_name" class="form-label">Nome</label>
                        <input type="text" name="name" id="filter_name" class="form-control" value="{{ request('name') }}" placeholder="Nome...">
                    </div>
                    <div class="col-md-2">
                        <label for="order_by" class="form-label">Ordina per</label>
                        <select name="order_by" id="order_by" class="form-select">
                            <option value="">--</option>
                            <option value="quotation" {{ request('order_by') == 'quotation' ? 'selected' : '' }}>Qt. Attuale</option>
                            <option value="initial_quotation" {{ request('order_by') == 'initial_quotation' ? 'selected' : '' }}>Qt. Iniziale</option>
                            <option value="difference" {{ request('order_by') == 'difference' ? 'selected' : '' }}>Differenza</option>
                            <option value="mantra_quotation" {{ request('order_by') == 'mantra_quotation' ? 'selected' : '' }}>Qt. Attuale Mantra</option>
                            <option value="initial_mantra_quotation" {{ request('order_by') == 'initial_mantra_quotation' ? 'selected' : '' }}>Qt. Iniziale Mantra</option>
                            <option value="mantra_difference" {{ request('order_by') == 'mantra_difference' ? 'selected' : '' }}>Differenza Mantra</option>
                            <option value="value" {{ request('order_by') == 'value' ? 'selected' : '' }}>Valore</option>
                            <option value="mantra_value" {{ request('order_by') == 'mantra_value' ? 'selected' : '' }}>Valore Mantra</option>
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
                        <a href="{{ route('players.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>

                @if($players->isEmpty())
                    <div class="alert alert-warning text-center">
                        <h4>Nessun giocatore trovato</h4>
                        <p>Non ci sono giocatori nel database. <a href="{{ route('players.import.form') }}">Importa il primo file Excel</a></p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Ruolo</th>
                                    <th>Ruolo Mantra</th>
                                    <th>Squadra</th>
                                    <th>Qt. Attuale</th>
                                    <th>Qt. Iniziale</th>
                                    <th>Differenza</th>
                                    <th>Qt. Attuale Mantra</th>
                                    <th>Qt. Iniziale Mantra</th>
                                    <th>Differenza Mantra</th>
                                    <th>Valore</th>
                                    <th>Valore Mantra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($players as $player)
                                <tr>
                                    <td>{{ $player->id }}</td>
                                    <td><strong><a href="{{ route('players.show', $player->id) }}">{{ $player->name }}</a></strong></td>
                                    <td>
                                        <span class="badge 
                                            @if($player->position == 'P')bg-warning text-dark
                                            @elseif($player->position == 'D') bg-success
                                            @elseif($player->position == 'C') bg-primary
                                            @elseif($player->position == 'A') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ $player->position }}
                                        </span>
                                    </td>
                                    <td>{{ $player->mantra_position }}</td>
                                    <td>{{ $player->team }}</td>
                                    <td>{{ number_format($player->quotation) }}</td>
                                    <td>{{ number_format($player->initial_quotation) }}</td>
                                    <td>
                                        <span class="badge {{ $player->difference >= 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $player->difference >= 0 ? '+' : '' }}{{ $player->difference }}
                                        </span>
                                    </td>
                                    <td>{{ $player->mantra_quotation }}</td>
                                    <td>{{ $player->initial_mantra_quotation }}</td>
                                    <td>{{ $player->mantra_difference }}</td>
                                    <td><strong>{{ number_format($player->value) }}</strong></td>
                                    <td>{{ $player->mantra_value }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
