<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importa Giocatori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Importa Giocatori da Excel</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5>Formato File Excel richiesto:</h5>
                            <p>Il file Excel deve contenere le seguenti colonne (nomi esatti):</p>
                            <ul>
                                <li><strong>Id</strong> - ID univoco del giocatore</li>
                                <li><strong>Nome</strong> - Nome del giocatore</li>
                                <li><strong>R</strong> - Ruolo del giocatore (P, D, C, A)</li>
                                <li><strong>Squadra</strong> - Squadra di appartenenza</li>
                                <li><strong>Qt.A</strong> - Quotazione attuale</li>
                                <li><strong>Qt.I</strong> - Quotazione iniziale</li>
                                <li><strong>Diff.</strong> - Differenza di quotazione</li>
                                <li><strong>FVM</strong> - Valore del giocatore</li>
                                <li><strong>RM</strong> - Ruolo Mantra</li>
                                <li><strong>Qt.A M</strong> - Quotazione attuale Mantra</li>
                                <li><strong>Qt.I M</strong> - Quotazione iniziale Mantra</li>
                                <li><strong>Diff.M</strong> - Differenza Mantra</li>
                                <li><strong>FVM M</strong> - Valore Mantra</li>
                            </ul>
                        </div>

                        <!-- Form per importazione normale -->
                        <form action="{{ route('players.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Seleziona file Excel (max 2MB)</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Importa Giocatori</button>
                        </form>

                        <hr class="my-4">

                        <!-- Form per importazione in background (file grandi) -->
                        <h5>Importazione in Background (per file grandi)</h5>
                        <form action="{{ route('players.import.background') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file_bg" class="form-label">Seleziona file Excel (max 5MB)</label>
                                <input type="file" class="form-control" id="file_bg" name="file" accept=".xlsx,.xls,.csv" required>
                            </div>
                            <button type="submit" class="btn btn-success">Importa in Background</button>
                            <small class="form-text text-muted">
                                L'importazione avverrà in background. Utile per file molto grandi.
                            </small>
                        </form>

                        <div class="mt-4">
                            <h5>File di esempio:</h5>
                            <p>Per scaricare un template Excel di esempio, <a href="{{ route('players.export.template') }}" class="btn btn-outline-info btn-sm">clicca qui</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
