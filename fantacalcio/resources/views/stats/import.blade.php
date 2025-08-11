<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importa Statistiche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Importa Statistiche da Excel</h3>
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
                                <li><strong>Id</strong> - ID giocatore (deve corrispondere a un Player già importato)</li>
                                <li><strong>R</strong> - Ruolo</li>
                                <li><strong>RM</strong> - Ruolo Mantra</li>
                                <li><strong>Nome</strong> - Nome</li>
                                <li><strong>Squadra</strong> - Squadra</li>
                                <li><strong>Pv</strong> - Presenze</li>
                                <li><strong>Mv</strong> - Media Voto</li>
                                <li><strong>Fm</strong> - Media Fantavoto</li>
                                <li><strong>Gf</strong> - Gol fatti</li>
                                <li><strong>Gs</strong> - Gol subiti</li>
                                <li><strong>Rp</strong> - Rigori parati</li>
                                <li><strong>Rc</strong> - Rigori causati</li>
                                <li><strong>R+</strong> - Rigori segnati</li>
                                <li><strong>R-</strong> - Rigori sbagliati</li>
                                <li><strong>Ass</strong> - Assist</li>
                                <li><strong>Amm</strong> - Ammonizioni</li>
                                <li><strong>Esp</strong> - Espulsioni</li>
                                <li><strong>Au</strong> - Autogol</li>
                            </ul>
                            <p>La stagione verrà dedotta dal nome del file (senza estensione).</p>
                        </div>
                        <form action="{{ route('stats.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Seleziona file Excel (max 2MB)</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Importa Statistiche</button>
                        </form>
                        <hr class="my-4">
                        <h5>File di esempio:</h5>
                        <p>Per scaricare un template Excel di esempio, <a href="{{ route('stats.export.template') }}" class="btn btn-outline-info btn-sm">clicca qui</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
