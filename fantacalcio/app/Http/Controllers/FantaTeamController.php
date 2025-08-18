<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Player;
use App\Models\FantaTeam;
use App\Models\PlayerPreference;

class FantaTeamController extends Controller
{
    public function index()
    {
        $teams = FantaTeam::where('user_id', Auth::id())
            ->withCount('players')
            ->orderByDesc('updated_at')
            ->get();
        return view('team.index', compact('teams'));
    }

    public function builder(Request $request)
    {
        $budget = (int) ($request->input('budget', 500));
        $teamId = $request->route('id');
        $initial = null;
        if ($teamId)
        {
            $team = FantaTeam::where('user_id', Auth::id())
                ->with('players:id,name,team,position')
                ->findOrFail($teamId);
            $initial = [
                'id' => $team->id,
                'name' => $team->name,
                'budget' => $team->budget,
                'players' => [
                    'P' => $team->players->where('position','P')->pluck('id')->values(),
                    'D' => $team->players->where('position','D')->pluck('id')->values(),
                    'C' => $team->players->where('position','C')->pluck('id')->values(),
                    'A' => $team->players->where('position','A')->pluck('id')->values(),
                ],
            ];
            $budget = $team->budget;
        }
        return view('team.builder', compact('budget', 'initial'));
    }

    public function searchPlayers(Request $request)
    {
        $role = $request->query('role');
        $q = trim((string) $request->query('q', ''));
        if (!in_array($role, ['P','D','C','A']))
            return response()->json([]);
        $query = Player::query()->where('position', $role);
        if ($q !== '')
            $query->where('name', 'like', $q.'%');
        $players = $query->orderBy('name')->limit(20)->get(['id','name','team']);

        $rankById = [];
        $valueById = [];
        if (Auth::check())
        {
            $prefs = PlayerPreference::where('user_id', Auth::id())
                ->whereIn('player_id', $players->pluck('id'))
                ->get(['player_id','rank','value'])
                ->keyBy('player_id');
            foreach ($players as $p)
            {
                $pref = $prefs->get($p->id);
                $rankById[$p->id] = optional($pref)->rank ?? 0;
                $valueById[$p->id] = (int) (optional($pref)->value ?? 0);
            }
        }

        $data = $players->map(function($p) use ($rankById, $valueById) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'team' => $p->team,
                'value' => $valueById[$p->id] ?? 0,
                'rank' => $rankById[$p->id] ?? 0,
            ];
        });
        return response()->json($data);
    }

    public function save(Request $request)
    {
        $isAuto = $request->wantsJson() || $request->boolean('autosave');

        $rules = [
            'name' => 'required|string|max:100',
            'budget' => 'required|integer|min:0',
            'players' => 'nullable|array',
            'team_id' => 'nullable|integer',
        ];

        if ($isAuto)
        {
            $rules['players.P'] = 'nullable|array|max:3';
            $rules['players.D'] = 'nullable|array|max:8';
            $rules['players.C'] = 'nullable|array|max:8';
            $rules['players.A'] = 'nullable|array|max:7';
        }
        else
        {
            $rules['players.P'] = 'required|array|size:3';
            $rules['players.D'] = 'required|array|size:8';
            $rules['players.C'] = 'required|array|size:8';
            $rules['players.A'] = 'required|array|size:7';
        }

        $validated = $request->validate($rules);

        $user = Auth::user();
        $teamId = $request->input('team_id');
        if ($teamId)
        {
            $team = FantaTeam::where('user_id', $user->id)->findOrFail($teamId);
            $team->name = $request->input('name');
        }
        else
        {
            $team = FantaTeam::firstOrNew([
                'user_id' => $user->id,
                'name' => $request->input('name'),
            ]);
        }
        $team->budget = (int) $request->input('budget');
        $team->save();
        $ids = collect($request->input('players.P', []))
            ->merge($request->input('players.D', []))
            ->merge($request->input('players.C', []))
            ->merge($request->input('players.A', []))
            ->map(fn($v) => (int)$v)
            ->filter()
            ->unique()
            ->values();
        $team->players()->sync($ids);

        if ($request->wantsJson())
        {
            return response()->json([
                'status' => 'ok',
                'team_id' => $team->id,
                'draft' => $isAuto,
            ]);
        }

        return redirect()->route('team.builder')->with('status', 'Squadra salvata!');
    }

    public function playersByIds(Request $request)
    {
        $ids = $request->query('ids');
        if (is_string($ids))
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        if (!is_array($ids))
            $ids = [];
        if (empty($ids))
            return response()->json([]);

        $players = Player::whereIn('id', $ids)->get(['id','name','team','position']);
        $prefs = collect();
        if (Auth::check())
        {
            $prefs = PlayerPreference::where('user_id', Auth::id())
                ->whereIn('player_id', $players->pluck('id'))
                ->get(['player_id','rank','value'])
                ->keyBy('player_id');
        }
        $out = $players->map(function($p) use ($prefs) {
            $pref = $prefs->get($p->id);
            return [
                'id' => $p->id,
                'name' => $p->name,
                'team' => $p->team,
                'role' => $p->position,
                'value' => (int) (optional($pref)->value ?? 0),
                'rank' => (int) (optional($pref)->rank ?? 0),
            ];
        })->values();
        
        return response()->json($out);
    }
}
