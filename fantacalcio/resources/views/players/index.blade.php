<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Giocatori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star { color: #ddd; }
        .star.filled { color: #f5c518; }
        .row-target { background: #fff3cd !important; font-weight: 700; }
        .rank-badge { padding: 0.25rem 0.5rem; border-radius: .25rem; color: #fff; font-weight: 600; }
        .rank-top { background:#0d6efd; }
        .rank-semitop { background:#20c997; }
        .rank-0 { background:#6c757d; }
        .rank-3 { background:#6610f2; }
        .rank-4 { background:#e83e8c; }
        .rank-5 { background:#fd7e14; }
        .rank-6 { background:#198754; }
        .rank-7 { background:#dc3545; }
        .rank-8 { background:#0dcaf0; }
        .rank-9 { background:#6f42c1; }
        .rank-10 { background:#343a40; }
        /* Wider table to show all columns */
        .table-wide { min-width: 1600px; }
        .table-sm td, .table-sm th { padding: .3rem .5rem; }
        th, td { white-space: nowrap; }
        /* Colorize select only when closed (not focused) */
        select.select-rank:not(:focus).rank-top,
        select.select-rank:not(:focus).rank-semitop,
        select.select-rank:not(:focus).rank-3,
        select.select-rank:not(:focus).rank-4,
        select.select-rank:not(:focus).rank-5,
        select.select-rank:not(:focus).rank-6,
        select.select-rank:not(:focus).rank-7,
        select.select-rank:not(:focus).rank-8,
        select.select-rank:not(:focus).rank-9,
        select.select-rank:not(:focus).rank-10 { color:#fff !important; }
        select.select-rank:not(:focus).rank-top { background:#0d6efd !important; }
        select.select-rank:not(:focus).rank-semitop { background:#20c997 !important; }
        select.select-rank:not(:focus).rank-3 { background:#6610f2 !important; }
        select.select-rank:not(:focus).rank-4 { background:#e83e8c !important; }
        select.select-rank:not(:focus).rank-5 { background:#fd7e14 !important; }
        select.select-rank:not(:focus).rank-6 { background:#198754 !important; }
        select.select-rank:not(:focus).rank-7 { background:#dc3545 !important; }
        select.select-rank:not(:focus).rank-8 { background:#0dcaf0 !important; }
        select.select-rank:not(:focus).rank-9 { background:#6f42c1 !important; }
        select.select-rank:not(:focus).rank-10 { background:#343a40 !important; }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Lista Giocatori ({{ $players->count() }} giocatori)</h2>
                    <a href="{{ route('players.import.form') }}" class="btn btn-success">Importa Giocatori</a>
                </div>

                @php
                    // Ordinale per rank (usato sia nei filtri che nella tabella)
                    $ordinal = function($n){
                        return match($n){
                            3=>'terza',4=>'quarta',5=>'quinta',6=>'sesta',7=>'settima',8=>'ottava',9=>'nona',10=>'decima', default=>''
                        };
                    };
                @endphp

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
                            <option value="quotation" {{ request('order_by') == 'quotation' ? 'selected' : '' }}>Qt. Attiva</option>
                            <option value="initial_quotation" {{ request('order_by') == 'initial_quotation' ? 'selected' : '' }}>Qt. Iniziale</option>
                            <option value="difference" {{ request('order_by') == 'difference' ? 'selected' : '' }}>Diff.</option>
                            <option value="mantra_quotation" {{ request('order_by') == 'mantra_quotation' ? 'selected' : '' }}>Qt. Attiva Mantra</option>
                            <option value="initial_mantra_quotation" {{ request('order_by') == 'initial_mantra_quotation' ? 'selected' : '' }}>Qt. Iniziale Mantra</option>
                            <option value="mantra_difference" {{ request('order_by') == 'mantra_difference' ? 'selected' : '' }}>Diff. Mantra</option>
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
                    <div class="col-md-12"><hr class="my-3"><strong>Filtri preferenze personali</strong></div>
                    <div class="col-md-2">
                        <label for="pref_target" class="form-label">Solo Target</label>
                        <select name="pref_target" id="pref_target" class="form-select">
                            <option value="">--</option>
                            <option value="1" {{ request('pref_target')==='1'?'selected':'' }}>Sì</option>
                            <option value="0" {{ request('pref_target')==='0'?'selected':'' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="pref_quality_min" class="form-label">Titolarità min</label>
                        <input type="number" min="0" max="5" step="1" name="pref_quality_min" id="pref_quality_min" class="form-control" value="{{ request('pref_quality_min') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="pref_integrity_min" class="form-label">Integrità min</label>
                        <input type="number" min="0" max="5" step="1" name="pref_integrity_min" id="pref_integrity_min" class="form-control" value="{{ request('pref_integrity_min') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="pref_rank" class="form-label">Rank</label>
                        <select name="pref_rank" id="pref_rank" class="form-select">
                            <option value="">--</option>
                            <option value="1" {{ request('pref_rank')==='1'?'selected':'' }}>Top</option>
                            <option value="2" {{ request('pref_rank')==='2'?'selected':'' }}>Semitop</option>
                            @for($r=3;$r<=10;$r++)
                                <option value="{{ $r }}" {{ request('pref_rank')===(string)$r?'selected':'' }}>{{ $ordinal($r) }} fascia</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="pref_value_min" class="form-label">Val. pref min</label>
                        <input type="number" min="0" step="1" name="pref_value_min" id="pref_value_min" class="form-control" value="{{ request('pref_value_min') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="pref_value_max" class="form-label">Val. pref max</label>
                        <input type="number" min="0" step="1" name="pref_value_max" id="pref_value_max" class="form-control" value="{{ request('pref_value_max') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Filtra</button>
                    </div>
                    <div class="col-md-2">
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
                        <table class="table table-striped align-middle table-sm table-wide">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Ruolo</th>
                                    <th>Ruolo M</th>
                                    <th>Squadra</th>
                                    <th>Qt. A</th>
                                    <th>Qt. I</th>
                                    <th>Diff.</th>
                                    <th>Qt. AM</th>
                                    <th>Qt. IM</th>
                                    <th>Diff. M</th>
                                    <th>Valore</th>
                                    <th>Valore M</th>
                                    @auth
                                    <th class="text-center">Target</th>
                                    <th class="text-center">Titolarità</th>
                                    <th class="text-center">Integrità</th>
                                    <th class="text-center">Val. pref</th>
                                    <th class="text-center">Rank</th>
                                    @endauth
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($players as $player)
                                @php
                                    $pref = isset($preferences) ? $preferences->get($player->id) : null;
                                    $rank = $pref->rank ?? 0;
                                    $rankClass = $rank == 1 ? 'rank-top' : ($rank == 2 ? 'rank-semitop' : 'rank-'.$rank);
                                @endphp
                                <tr data-player-id="{{ $player->id }}" class="{{ ($pref && $pref->is_target) ? 'row-target' : '' }}">
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
                                    @auth
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input js-target" {{ ($pref && $pref->is_target) ? 'checked' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        @for($i=1;$i<=5;$i++)
                                            <span class="star js-quality {{ ($pref && ($pref->quality ?? 0) >= $i) ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                                        @endfor
                                    </td>
                                    <td class="text-center">
                                        @for($i=1;$i<=5;$i++)
                                            <span class="star js-integrity {{ ($pref && ($pref->integrity ?? 0) >= $i) ? 'filled' : '' }}" data-value="{{ $i }}">★</span>
                                        @endfor
                                    </td>
                                    <td class="text-center" style="min-width:110px">
                                        <input type="number" min="0" step="1" class="form-control form-control-sm js-pref-value" style="width:100px; display:inline-block;" value="{{ $pref->value ?? 0 }}">
                                    </td>
                                    <td class="text-center">
                                        <select class="form-select form-select-sm js-rank select-rank" style="min-width:120px">
                                            <option value="0" class="rank-0" {{ $rank==0?'selected':'' }}>-</option>
                                            <option value="1" class="rank-top" {{ $rank==1?'selected':'' }}>Top</option>
                                            <option value="2" class="rank-semitop" {{ $rank==2?'selected':'' }}>Semitop</option>
                                            @for($r=3;$r<=10;$r++)
                                                <option value="{{ $r }}" class="rank-{{ $r }}" {{ $rank==$r?'selected':'' }}>{{ $ordinal($r) }} fascia</option>
                                            @endfor
                                        </select>
                                    </td>
                                    @endauth
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    @auth
    function rankClassFor(val){
        if(val===1) return 'rank-top';
        if(val===2) return 'rank-semitop';
        if(val>=3 && val<=10) return 'rank-'+val;
        return null;
    }
    function clearRankClasses(el){
        ['rank-top','rank-semitop','rank-3','rank-4','rank-5','rank-6','rank-7','rank-8','rank-9','rank-10'].forEach(c=>el.classList.remove(c));
    }
    function applyRankClass(el){
        clearRankClasses(el);
        const v = parseInt(el.value);
        const cls = rankClassFor(v);
        if(cls){ el.classList.add(cls); }
    }

    async function upsertPref(playerId, payload) {
        const res = await fetch('{{ route('player_prefs.upsert') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(Object.assign({ player_id: playerId }, payload))
        });
        if (!res.ok) {
            console.error('Errore salvataggio preferenza');
        }
        return res.json();
    }

    document.querySelectorAll('tr[data-player-id]').forEach(row => {
        const playerId = parseInt(row.getAttribute('data-player-id'));
        const chk = row.querySelector('.js-target');
        const rankSel = row.querySelector('.js-rank');
        const qualityStars = row.querySelectorAll('.js-quality');
        const integrityStars = row.querySelectorAll('.js-integrity');
        const valueInput = row.querySelector('.js-pref-value');

        if (chk) {
            chk.addEventListener('change', async (e) => {
                const checked = e.target.checked;
                await upsertPref(playerId, { is_target: checked });
                row.classList.toggle('row-target', checked);
            });
        }

        if (rankSel) {
            // Color when closed; remove color while focused (open)
            if (parseInt(rankSel.value) !== 0) applyRankClass(rankSel);
            rankSel.addEventListener('focus', () => clearRankClasses(rankSel));
            rankSel.addEventListener('blur', () => applyRankClass(rankSel));
            rankSel.addEventListener('change', async (e) => {
                const rank = parseInt(e.target.value);
                await upsertPref(playerId, { rank });
                applyRankClass(rankSel);
            });
        }

        function handleStars(nodeList, key) {
            nodeList.forEach(star => {
                star.style.cursor = 'pointer';
                star.addEventListener('click', async () => {
                    const value = parseInt(star.getAttribute('data-value'));
                    await upsertPref(playerId, { [key]: value });
                    nodeList.forEach(s => s.classList.toggle('filled', parseInt(s.getAttribute('data-value')) <= value));
                });
            });
        }
        handleStars(qualityStars, 'quality');
        handleStars(integrityStars, 'integrity');

        if (valueInput) {
            const commit = async () => {
                const v = parseInt(valueInput.value);
                await upsertPref(playerId, { value: isNaN(v) ? 0 : v });
            };
            valueInput.addEventListener('blur', commit);
            valueInput.addEventListener('keydown', async (e) => {
                if (e.key === 'Enter') { e.preventDefault(); await commit(); valueInput.blur(); }
            });
        }
    });
    @endauth
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
