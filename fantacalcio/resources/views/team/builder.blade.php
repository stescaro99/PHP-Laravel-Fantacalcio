<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crea la tua squadra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background: linear-gradient(125deg, #f8f9fa 0%, #eef4ff 50%, #fef6ef 100%); }
    .role-col { min-width: 340px; }
    .player-chip { display:flex; align-items:center; justify-content:space-between; gap:.5rem; padding:.45rem .6rem; border:1px solid #dee2e6; border-radius:.5rem; background:#fff; box-shadow: 0 2px 6px rgba(0,0,0,.05); }
    .chip-meta { font-size:.85rem; color:#6c757d; }
    .chip-rank { font-weight:700; padding: .1rem .35rem; border-radius:.35rem; }
    .rank-top { background:#d32f2f; color:#fff; }
    .rank-semitop { background:#0d6efd; color:#fff; }
    .rank-3 { background:#198754; color:#fff; }
    .rank-4 { background:#ffc107; color:#212529; }
    .rank-5 { background:#C4A484; color:#212529; }
    .rank-6 { background:#6f42c1; color:#fff; }
    .rank-7 { background:#fd7e14; color:#212529; }
    .rank-8 { background:#6c757d; color:#fff; }
    .chip-remove { cursor:pointer; color:#dc3545; font-weight:700; }
    .suggest-item { cursor:pointer; padding:.35rem .6rem; }
    .suggest-item:hover { background:#f8f9fa; }
    .budget-box { position:sticky; top:0; z-index:10; }
    .card.role-col { border: none; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
    .card.role-col .card-header { background: #ffffff; font-weight: 600; }
    .saving-indicator { font-size: .9rem; color:#6c757d; display:none; align-items:center; gap:.4rem; }
    .saving-indicator.active { display:inline-flex; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">Home</a>
    <div class="navbar-nav">
      <a class="nav-link" href="{{ route('players.index') }}">Giocatori</a>
      <a class="nav-link" href="{{ route('stats.index') }}">Statistiche</a>
      <a class="nav-link active" aria-current="page" href="{{ route('team.builder') }}">Team Builder</a>
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

<div class="container-fluid py-3">
  <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <div>
      <h2 class="mb-0">{{ isset($initial) ? 'Modifica squadra' : 'Crea la tua squadra' }}</h2>
      @isset($initial)
        <small class="text-muted">ID: {{ $initial['id'] }}</small>
      @endisset
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('team.create') }}" class="btn btn-outline-primary">+ Nuova squadra</a>
      <a href="{{ route('team.index') }}" class="btn btn-secondary">Le mie squadre</a>
      <a href="{{ route('players.index') }}" class="btn btn-light">Torna alla lista</a>
    </div>
  </div>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <form id="teamForm" method="POST" action="{{ route('team.save') }}" class="mb-4">
    @csrf
    <input type="hidden" name="team_id" id="team_id" value="{{ $initial['id'] ?? '' }}">
    <div class="row g-3 align-items-end mb-2">
      <div class="col-auto">
        <label class="form-label">Nome Squadra</label>
        <input type="text" name="name" class="form-control" placeholder="Es. Real Baggiana" required value="{{ $initial['name'] ?? '' }}" />
      </div>
      <div class="col-auto">
        <label class="form-label">Budget</label>
        <input type="number" name="budget" class="form-control" value="{{ $initial['budget'] ?? $budget }}" min="0" />
      </div>
      <div class="col-auto budget-box">
        <div class="alert alert-info mb-1">Budget residuo: <strong id="budgetLeft">{{ $initial['budget'] ?? $budget }}</strong></div>
        <div class="progress" style="height: 20px; min-width: 260px; max-width: 360px;">
          <div id="budgetProgress" class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100">0 / {{ $initial['budget'] ?? $budget }}</div>
        </div>
      </div>
      <div class="col-auto ms-auto d-flex align-items-center gap-2">
        <span id="savingIndicator" class="saving-indicator">
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          <span>Salvataggio...</span>
        </span>
      </div>
    </div>

    <div class="row g-4">
      @php $roles = ['P' => 3, 'D' => 8, 'C' => 8, 'A' => 7]; @endphp
      @foreach($roles as $role => $count)
      <div class="col-md-6 col-lg-3">
        <div class="card role-col">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ $role }} ({{ $count }})</span>
            <small class="text-muted"><span class="js-count" data-role="{{ $role }}">0</span>/{{ $count }} • Tot: <span class="js-total" data-role="{{ $role }}">0</span></small>
          </div>
          <div class="card-body">
            <div class="mb-2 position-relative">
              <input type="text" class="form-control js-search" data-role="{{ $role }}" placeholder="Cerca {{ $role }}..." autocomplete="off" />
              <div class="list-group position-absolute w-100 shadow-sm js-suggest" style="max-height: 260px; overflow:auto; display:none;"></div>
            </div>
            <div class="vstack gap-2 js-picked" data-role="{{ $role }}"></div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div id="hiddenInputs"></div>
  </form>
</div>

<script>
(function(){
  const form = document.getElementById('teamForm');
  const budgetInput = form.querySelector('input[name="budget"]');
  const nameInput = form.querySelector('input[name="name"]');
  const teamIdInput = document.getElementById('team_id');
  const savingIndicator = document.getElementById('savingIndicator');
  const budgetLeftEl = document.getElementById('budgetLeft');
  const budgetProgress = document.getElementById('budgetProgress');
  const pickedByRole = { P: [], D: [], C: [], A: [] };
  const initial = @json($initial ?? null);

  function showSaving(on){ savingIndicator.classList.toggle('active', !!on); }

  function rankLabel(v){
    switch(v){
      case 1: return {text: 'Top', cls: 'rank-top'};
      case 2: return {text: 'Semitop', cls: 'rank-semitop'};
      case 3: return {text: 'terza fascia', cls: 'rank-3'};
      case 4: return {text: 'quarta fascia', cls: 'rank-4'};
      case 5: return {text: 'quinta fascia', cls: 'rank-5'};
      case 6: return {text: 'Scommessa', cls: 'rank-6'};
      case 7: return {text: 'Per coppia', cls: 'rank-7'};
      case 8: return {text: 'Riserva', cls: 'rank-8'};
      default: return {text: '-', cls: ''};
    }
  }

  function updateRoleMeta(role){
    const list = pickedByRole[role] || [];
    const countEl = document.querySelector(`.js-count[data-role="${role}"]`);
    const totalEl = document.querySelector(`.js-total[data-role="${role}"]`);
    if(countEl) countEl.textContent = list.length;
    const tot = list.reduce((s,p)=> s + (parseInt(p.value||0)||0), 0);
    if(totalEl) totalEl.textContent = tot;
  }

  function recalcBudget(){
    const total = Object.values(pickedByRole).flat().reduce((sum,p)=>sum + (parseInt(p.value||0)||0), 0);
    const budget = parseInt(budgetInput.value || '0');
    const left = budget - total;
    budgetLeftEl.textContent = left;
    budgetLeftEl.parentElement.className = 'alert ' + (left < 0 ? 'alert-danger' : 'alert-info');

    if (budgetProgress) {
      let percent = 0;
      if (budget > 0) {
        percent = Math.round((total / budget) * 100);
        if (percent < 0) percent = 0;
        if (percent > 100) percent = 100;
      } else if (total > 0) {
        percent = 100;
      }
      budgetProgress.style.width = percent + '%';
      budgetProgress.setAttribute('aria-valuenow', String(percent));
      budgetProgress.textContent = `${total} / ${budget}`;
      budgetProgress.classList.remove('bg-success','bg-warning','bg-danger','text-dark');
      if (left < 0) {
        budgetProgress.classList.add('bg-danger');
      } else if (percent >= 80) {
        budgetProgress.classList.add('bg-warning','text-dark');
      } else {
        budgetProgress.classList.add('bg-success');
      }
    }
  }

  function renderPicked(role){
    const container = document.querySelector('.js-picked[data-role="'+role+'"]');
    container.innerHTML = '';
    pickedByRole[role].forEach((p,idx) => {
      const r = rankLabel(parseInt(p.rank||0));
      const row = document.createElement('div');
      row.className = 'player-chip';
      row.innerHTML = `
        <div>
          <div><strong>${p.name}</strong> <span class="chip-meta">(${p.team})</span></div>
          <div class="chip-meta">Pref. Value: <strong>${p.value}</strong> | <span class="chip-rank ${r.cls}">${r.text}</span></div>
        </div>
        <span class="chip-remove" title="Rimuovi">✕</span>
      `;
      row.querySelector('.chip-remove').addEventListener('click', () => {
        pickedByRole[role].splice(idx,1);
        renderPicked(role);
        updateRoleMeta(role);
        recalcBudget();
        updateHiddenInputs();
        scheduleSave();
      });
      container.appendChild(row);
    });
  }

  function updateHiddenInputs(){
    const hidden = document.getElementById('hiddenInputs');
    hidden.innerHTML = '';
    Object.entries(pickedByRole).forEach(([role, list]) => {
      list.forEach((p, idx) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `players[${role}][${idx}]`;
        input.value = p.id;
        hidden.appendChild(input);
      });
    });
  }

  async function doSave(){
    const url = '{{ route('team.save') }}';
    const payload = new FormData(form);
    ['P','D','C','A'].forEach(role => {
      (pickedByRole[role]||[]).forEach((p, idx) => {
        payload.append(`players[${role}][${idx}]`, p.id);
      });
    });
    showSaving(true);
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: payload
    });
    showSaving(false);
    if (res.ok) {
      const data = await res.json().catch(()=> null);
      if (data && data.team_id) {
        teamIdInput.value = data.team_id;
      }
    }
  }

  let saveTimer = null;
  function scheduleSave(){
    if (saveTimer) clearTimeout(saveTimer);
    saveTimer = setTimeout(doSave, 500);
  }

  function addPicked(role, p){
    const limits = { P:3, D:8, C:8, A:7 };
    if(pickedByRole[role].length >= limits[role]) return;
    if (Object.values(pickedByRole).flat().some(x => x.id === p.id)) return;
    pickedByRole[role].push(p);
    renderPicked(role);
    updateRoleMeta(role);
    recalcBudget();
    updateHiddenInputs();
    scheduleSave();
  }

  async function fetchPlayersByIds(ids){
    if (!ids || !ids.length) return [];
    const url = new URL('{{ route('team.playersByIds') }}', window.location.origin);
    url.searchParams.set('ids', ids.join(','));
    const res = await fetch(url.toString());
    if (!res.ok) return [];
    return res.json();
  }

  async function preloadInitial(){
    if (!initial) return;
    const roles = ['P','D','C','A'];
    for (const r of roles) {
      const ids = (initial.players && initial.players[r]) ? initial.players[r] : [];
      if (!ids.length) continue;
      const players = await fetchPlayersByIds(ids);
      // preserve original order roughly by ids list
      const byId = Object.fromEntries(players.map(p => [p.id, p]));
      ids.forEach(id => {
        const p = byId[id];
        if (p && p.role === r) {
          pickedByRole[r].push({ id: p.id, name: p.name, team: p.team, value: p.value, rank: p.rank });
        }
      });
      renderPicked(r);
      updateRoleMeta(r);
    }
    document.querySelector('input[name="name"]').value = initial.name || '';
    document.querySelector('input[name="budget"]').value = initial.budget || {{ $budget }};
    recalcBudget();
    updateHiddenInputs();
  }

  async function search(role, q){
    const url = new URL('{{ route('team.search') }}', window.location.origin);
    url.searchParams.set('role', role);
    url.searchParams.set('q', q);
    const res = await fetch(url.toString());
    if(!res.ok) return [];
    return res.json();
  }

  function attachSearch(el){
    const role = el.getAttribute('data-role');
    const box = el.parentElement.querySelector('.js-suggest');
    let last = '';

    el.addEventListener('input', async () => {
      const q = el.value.trim();
      last = q;
      if(q.length === 0){ box.style.display='none'; box.innerHTML=''; return; }
      const results = await search(role, q);
      if(last !== q) return;
      box.innerHTML = '';
      results.forEach(p => {
        const r = rankLabel(parseInt(p.rank||0));
        const item = document.createElement('div');
        item.className = 'list-group-item list-group-item-action suggest-item';
        item.innerHTML = `${p.name} <span class=\"text-muted\">(${p.team})</span> • Pref. Val: <strong>${p.value}</strong> • <span class=\"badge ${r.cls}\">${r.text}</span>`;
        item.addEventListener('click', () => {
          addPicked(role, p);
          el.value = '';
          box.style.display='none'; box.innerHTML='';
        });
        box.appendChild(item);
      });
      box.style.display = results.length ? 'block' : 'none';
    });

    document.addEventListener('click', (e) => {
      if(!box.contains(e.target) && e.target !== el){ box.style.display='none'; }
    });
  }

  document.querySelectorAll('.js-search').forEach(attachSearch);

  ['P','D','C','A'].forEach(updateRoleMeta);
  budgetInput.addEventListener('input', () => { recalcBudget(); scheduleSave(); });
  nameInput.addEventListener('input', scheduleSave);
  preloadInitial();
  recalcBudget();
})();
</script>
</body>
</html>
