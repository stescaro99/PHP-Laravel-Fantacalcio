<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dettaglio Giocatore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star { color: #ddd; cursor: pointer; }
        .star.filled { color: #f5c518; }
        select.select-rank.rank-top,
        select.select-rank.rank-semitop,
        select.select-rank.rank-3,
        select.select-rank.rank-4,
        select.select-rank.rank-5,
        select.select-rank.rank-6,
        select.select-rank.rank-7,
        select.select-rank.rank-8,
        select.select-rank.rank-9,
        select.select-rank.rank-10 { color:#fff !important; }
        select.select-rank.rank-top { background:#0d6efd !important; }
        select.select-rank.rank-semitop { background:#20c997 !important; }
        select.select-rank.rank-3 { background:#6610f2 !important; }
        select.select-rank.rank-4 { background:#e83e8c !important; }
        select.select-rank.rank-5 { background:#fd7e14 !important; }
        select.select-rank.rank-6 { background:#198754 !important; }
        select.select-rank.rank-7 { background:#dc3545 !important; }
        select.select-rank.rank-8 { background:#0dcaf0 !important; }
        select.select-rank.rank-9 { background:#6f42c1 !important; }
        select.select-rank.rank-10 { background:#343a40 !important; }
    </style>
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

        @auth
        <div class="card mb-4">
            <div class="card-header">Preferenze personali</div>
            <div class="card-body">
                <div class="mb-3 form-check">
                    <input type="checkbox" id="is_target" class="form-check-input" {{ ($pref && $pref->is_target) ? 'checked' : '' }}>
                    <label for="is_target" class="form-check-label">Target</label>
                </div>
                <div class="mb-3">
                    <label class="form-label">Titolarità</label><br>
                    @for($i=1;$i<=5;$i++)
                        <span class="star js-quality {{ ($pref && ($pref->quality ?? 0) >= $i) ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                    @endfor
                </div>
                <div class="mb-3">
                    <label class="form-label">Integrità</label><br>
                    @for($i=1;$i<=5;$i++)
                        <span class="star js-injuries {{ ($pref && ($pref->injuries ?? 0) >= $i) ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                    @endfor
                </div>
                <div class="mb-3">
                    <label class="form-label">Rank</label>
                    <select id="rank" class="form-select select-rank" style="max-width:200px">
                        <option value="0" {{ ($pref->rank ?? 0)==0?'selected':'' }}>-</option>
                        <option value="1" {{ ($pref->rank ?? 0)==1?'selected':'' }}>Top</option>
                        <option value="2" {{ ($pref->rank ?? 0)==2?'selected':'' }}>Semitop</option>
                        @for($r=3;$r<=10;$r++)
                            <option value="{{ $r }}" {{ ($pref->rank ?? 0)==$r?'selected':'' }}>{{ match($r){3=>'terza',4=>'quarta',5=>'quinta',6=>'sesta',7=>'settima',8=>'ottava',9=>'nona',10=>'decima'} }} fascia</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <textarea id="notes" class="form-control" rows="3">{{ $pref->notes ?? '' }}</textarea>
                </div>
                <button id="saveNotes" class="btn btn-primary">Salva</button>
            </div>
        </div>
        @endauth
    </div>

    @auth
    <script>
    function rankClassFor(val){
        if(val===1) return 'rank-top';
        if(val===2) return 'rank-semitop';
        if(val>=3 && val<=10) return 'rank-'+val;
        return null;
    }
    function applyRankColor(sel){
        ['rank-top','rank-semitop','rank-3','rank-4','rank-5','rank-6','rank-7','rank-8','rank-9','rank-10'].forEach(c=>sel.classList.remove(c));
        const v = parseInt(sel.value);
        const cls = rankClassFor(v);
        if(cls){ sel.classList.add(cls); }
    }

    async function upsertPref(payload) {
        const res = await fetch('{{ route('player_prefs.upsert') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(Object.assign({ player_id: {{ $player->id }} }, payload))
        });
        return res.json();
    }

    document.getElementById('is_target')?.addEventListener('change', async (e) => {
        await upsertPref({ is_target: e.target.checked });
    });

    function bindStars(selector, key) {
        document.querySelectorAll(selector).forEach(star => {
            star.addEventListener('click', async () => {
                const value = parseInt(star.getAttribute('data-value'));
                await upsertPref({ [key]: value });
                document.querySelectorAll(selector).forEach(s => s.classList.toggle('filled', parseInt(s.getAttribute('data-value')) <= value));
            });
        });
    }
    bindStars('.js-quality', 'quality');
    bindStars('.js-injuries', 'injuries');

    const rankSel = document.getElementById('rank');
    if (rankSel) {
        if (parseInt(rankSel.value) !== 0) applyRankColor(rankSel);
        rankSel.addEventListener('change', async (e) => {
            await upsertPref({ rank: parseInt(e.target.value) });
            applyRankColor(rankSel);
        });
    }
    </script>
    @endauth
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
