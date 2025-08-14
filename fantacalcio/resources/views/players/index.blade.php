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
        .rank-top { background:#d32f2f; color:#fff; }
        .rank-semitop { background:#0d6efd; color:#fff; }
        .rank-0 { background:#6c757d; color:#fff; }
        .rank-3 { background:#198754; color:#fff; }
        .rank-4 { background:#ffc107; color:#212529; }
        .rank-5 { background:#C4A484; color:#212529; }
        .rank-6 { background:#6f42c1; color:#fff; }
        .rank-7 { background:#fd7e14; color:#212529; }
        .rank-8 { background:#6c757d; color:#fff; }            
        .table-wide { min-width: 1600px; }
        .table-sm td, .table-sm th { padding: .3rem .5rem; }
        th, td { white-space: nowrap; }
        .th-sort { cursor: pointer; user-select: none; }
        .th-sort .arrow { opacity: .3; }
        .th-sort.active.asc .arrow.up, .th-sort.active.desc .arrow.down { opacity: 1; }
        .btn-role.active { box-shadow: inset 0 0 0 2px #00000020; }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Lista Giocatori ({{ $players->count() }} giocatori)</h2>
                    <a href="{{ route('players.import.form') }}" class="btn btn-success">Importa Giocatori</a>
                </div>

                @php
                    $ordinal = function($n){
                        return match($n){
                            3=>'terza',4=>'quarta',5=>'quinta',6=>'sesta',7=>'settima',8=>'ottava',9=>'nona',10=>'decima', default=>''
                        };
                    };
                    $selectedRoles = (array) request('roles', []);
                    $orderBy = request('order_by');
                    $orderDir = request('order_dir', 'asc');
                @endphp

                <!-- Barra filtri rapidi -->
                <form method="GET" id="quickFilters" class="mb-3">
                    <div class="d-flex flex-wrap gap-2 align-items-end">
                        <div class="input-group" style="max-width: 480px;">
                            <span class="input-group-text">Cerca</span>
                            <input type="text" class="form-control" name="name" placeholder="Giocatore..." value="{{ request('name') }}">
                            <select name="team" class="form-select">
                                <option value="">Tutte le squadre</option>
                                @isset($allTeams)
                                    @foreach($allTeams as $t)
                                        <option value="{{ $t }}" {{ request('team')===$t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>

                        <div class="btn-group" role="group" aria-label="Filtra Ruoli">
                            @php $roles = ['P'=>'Port','D'=>'Dif','C'=>'Cen','A'=>'Att']; @endphp
                            @foreach($roles as $code=>$label)
                                @php $active = in_array($code, $selectedRoles); @endphp
                                <input type="checkbox" class="btn-check" id="role-{{ $code }}" autocomplete="off" name="roles[]" value="{{ $code }}" {{ $active?'checked':'' }}>
                                <label class="btn btn-outline-primary btn-role" for="role-{{ $code }}">{{ $label }}</label>
                            @endforeach
                        </div>

                        <input type="hidden" name="order_by" value="{{ $orderBy }}">
                        <input type="hidden" name="order_dir" value="{{ $orderDir }}">

                        <button type="submit" class="btn btn-primary">Applica</button>
                        <a href="{{ route('players.index') }}" class="btn btn-secondary">Reset</a>
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
                                    @php
                                        $thSort = function($key, $label) use($orderBy, $orderDir) {
                                            $isActive = $orderBy === $key;
                                            $dir = $isActive && $orderDir === 'asc' ? 'desc' : 'asc';
                                            $class = 'th-sort'.($isActive? ' active '.$orderDir : '');
                                            $url = request()->fullUrlWithQuery(['order_by'=>$key,'order_dir'=>$dir]);
                                            return "<th class=\"$class\"><a href=\"$url\" class=\"text-white text-decoration-none\">$label <span class=\"arrow up\">▲</span><span class=\"arrow down\">▼</span></a></th>";
                                        };
                                    @endphp
                                    {!! $thSort('id','ID') !!}
                                    {!! $thSort('name','Nome') !!}
                                    {!! $thSort('position','Ruolo') !!}
                                    {!! $thSort('mantra_position','Ruolo M') !!}
                                    {!! $thSort('team','Squadra') !!}
                                    {!! $thSort('quotation','Qt. A') !!}
                                    {!! $thSort('initial_quotation','Qt. I') !!}
                                    {!! $thSort('difference','Diff.') !!}
                                    {!! $thSort('mantra_quotation','Qt. AM') !!}
                                    {!! $thSort('initial_mantra_quotation','Qt. IM') !!}
                                    {!! $thSort('mantra_difference','Diff. M') !!}
                                    {!! $thSort('value','Valore') !!}
                                    {!! $thSort('mantra_value','Valore M') !!}
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
                                            @for($r=3;$r<=5;$r++)
                                                <option value="{{ $r }}" class="rank-{{ $r }}" {{ $rank==$r?'selected':'' }}>{{ $ordinal($r) }} fascia</option>
                                            @endfor
                                            <option value="6" class="rank-6" {{ $rank==6?'selected':'' }}>Scommessa</option>
                                            <option value="7" class="rank-7" {{ $rank==7?'selected':'' }}>Per coppia</option>
                                            <option value="8" class="rank-8" {{ $rank==8?'selected':'' }}>Riserva</option>
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
        if(val>=3 && val<=8) return 'rank-'+val;
        return null;
    }
    function clearRankClasses(el){
        ['rank-top','rank-semitop','rank-3','rank-4','rank-5','rank-6','rank-7','rank-8'].forEach(c=>el.classList.remove(c));
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

    <script>
    // Evidenziazione colonna ordinata
    (function(){
        const params = new URLSearchParams(window.location.search);
        const orderBy = params.get('order_by');
        const orderDir = params.get('order_dir') || 'asc';
        if (!orderBy) return;
        document.querySelectorAll('thead .th-sort').forEach(th => {
            const a = th.querySelector('a');
            if (!a) return;
            const href = new URL(a.getAttribute('href'), window.location);
            if (href.searchParams.get('order_by') === orderBy) {
                th.classList.add('active', orderDir);
            }
        });
    })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
