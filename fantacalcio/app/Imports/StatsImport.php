<?php

namespace App\Imports;

use App\Models\Stat;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

HeadingRowFormatter::default('none');

class StatsImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;

    protected $season;

    public function __construct($season = null)
    {
        $this->season = $season;
    }

    /**
    * @param array $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $normalized = [];
        foreach ($row as $key => $value)
        {
            $k = str_replace("\xC2\xA0", ' ', $key);
            $k = trim($k);
            $k = preg_replace('/\s+/', ' ', $k);
            $normalized[$k] = $value;
        }

        static $logged = 0;
        if ($logged < 3) {
            Log::debug('Import row raw keys', array_keys($row));
            Log::debug('Import row normalized keys', array_keys($normalized));
            $logged++;
        }

        $r = $normalized;

        if (!isset($r['Id']) || trim((string)$r['Id']) === '') {
            return null;
        }

        $attrs = [
            'player_id' => (int)($r['Id'] ?? 0),
            'position' => (string)($r['R'] ?? '-'),
            'mantra_position' => $r['RM'] ?? null,
            'name' => (string)($r['Nome'] ?? '-'),
            'team' => (string)($r['Squadra'] ?? '-'),
            'n_votes' => (int)($r['Pv'] ?? 0),
            'average_vote' => (float)($r['Mv'] ?? 0.0),
            'average_fantavote' => (float)($r['Fm'] ?? 0.0),
            'goals' => (int)($r['Gf'] ?? 0),
            'goals_conceded' => (int)($r['Gs'] ?? 0),
            'catched_penalties' => (int)($r['Rp'] ?? 0),
            'taken_penalties' => (int)($r['Rc'] ?? 0),
            'scored_penalties' => (int)($r['R+'] ?? 0),
            'missed_penalties' => (int)($r['R-'] ?? 0),
            'assists' => (int)($r['Ass'] ?? 0),
            'yellow_cards' => (int)($r['Amm'] ?? 0),
            'red_cards' => (int)($r['Esp'] ?? 0),
            'own_goals' => (int)($r['Au'] ?? 0),
            'season' => $this->season,
        ];

        // Upsert per evitare duplicati della stessa season per lo stesso player
        return Stat::updateOrCreate(
            ['player_id' => $attrs['player_id'], 'season' => $this->season],
            $attrs
        );
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'Id' => 'required|integer|min:1',
            'R' => 'required|string|max:50',
            'RM' => 'nullable|string|max:50',
            'Nome' => 'required|string|max:255',
            'Squadra' => 'required|string|max:255',
            'Pv' => 'required|integer|min:0',
            'Mv' => 'required|numeric|min:0',
            'Fm' => 'required|numeric|min:0',
            'Gf' => 'required|integer|min:0',
            'Gs' => 'required|integer|min:0',
            'Rp' => 'required|integer|min:0',
            'Rc' => 'required|integer|min:0',
            'R+' => 'required|integer|min:0',
            'R-' => 'required|integer|min:0',
            'Ass' => 'required|integer|min:0',
            'Amm' => 'required|integer|min:0',
            'Esp' => 'required|integer|min:0',
            'Au' => 'required|integer|min:0',
        ];
    }
}
