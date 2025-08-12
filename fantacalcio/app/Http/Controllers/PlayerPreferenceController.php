<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Player;
use App\Models\PlayerPreference;
use Illuminate\Support\Facades\Auth;

class PlayerPreferenceController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $user = Auth::user();
        $players = $user->players()->withPivot(['is_target','value','integrity','quality','notes','rank'])->get();
        return response()->json([
            'user_id' => $user->id,
            'players' => $players,
        ]);
    }

    public function upsert(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $data = $request->validate([
            'player_id' => ['required','integer','exists:players,id'],
            'is_target' => ['sometimes','boolean'],
            'value' => ['sometimes','integer','min:0'],
            'integrity' => ['sometimes','integer','min:0','max:5'],
            'quality' => ['sometimes','integer','min:0','max:5'],
            'notes' => ['sometimes','nullable','string'],
            'rank' => ['sometimes','integer','min:0','max:10'],
        ]);
        $user = Auth::user();
        $attrs = collect($data)->only(['is_target','value','integrity','quality','notes','rank'])->toArray();
        $pref = $user->upsertPlayerPreference((int)$data['player_id'], $attrs);

        return response()->json([
            'status' => 'ok',
            'preference' => $pref,
        ]);
    }

    public function remove(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $data = $request->validate([
            'player_id' => ['required','integer','exists:players,id'],
        ]);
        $user = Auth::user();
        $user->removePlayerPreference((int)$data['player_id']);
        return response()->json(['status' => 'ok']);
    }

    public function get(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $data = $request->validate([
            'player_id' => ['required','integer','exists:players,id'],
        ]);
        $user = Auth::user();
        $pref = $user->getPlayerPreference((int)$data['player_id']);
        if (!$pref)
            return response()->json(['message' => 'Not found'], 404);
        return response()->json(['preference' => $pref]);
    }
}
