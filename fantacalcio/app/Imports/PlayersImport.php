<?php

namespace App\Imports;

use App\Models\Player;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

HeadingRowFormatter::default('none');

class PlayersImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;

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

        $id = (int)($r['Id'] ?? 0);
        if ($id <= 0) {
            return null;
        }

        $attrs = [
            'position' => (string)($r['R'] ?? '-'),
            'mantra_position' => $r['RM'] ?? null,
            'name' => (string)($r['Nome'] ?? '-'),
            'team' => (string)($r['Squadra'] ?? '-'),
            'quotation' => (int)($r['Qt.A'] ?? 0),
            'initial_quotation' => (int)($r['Qt.I'] ?? 0),
            'difference' => (int)($r['Diff.'] ?? 0),
            'mantra_quotation' => isset($r['Qt.A M']) && $r['Qt.A M'] !== '' ? (int)$r['Qt.A M'] : null,
            'initial_mantra_quotation' => isset($r['Qt.I M']) && $r['Qt.I M'] !== '' ? (int)$r['Qt.I M'] : null,
            'mantra_difference' => isset($r['Diff.M']) && $r['Diff.M'] !== '' ? (int)$r['Diff.M'] : null,
            'value' => (int)($r['FVM'] ?? 0),
            'mantra_value' => isset($r['FVM M']) && $r['FVM M'] !== '' ? (int)$r['FVM M'] : null,
        ];

        // Se esiste, aggiorna; altrimenti crea nuovo record
        $existing = Player::find($id);
        if ($existing) {
            $existing->fill($attrs);
            $existing->save();
            return null; // Evita che Laravel-Excel provi a inserire nuovamente
        }

        return new Player(array_merge(['id' => $id], $attrs));
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
            'Qt\.A' => 'required|integer|min:0',
            'Qt\.I' => 'required|integer|min:0',
            'Diff\.' => 'required|integer',
            'Qt\.A M' => 'nullable|integer|min:0',
            'Qt\.I M' => 'nullable|integer|min:0',
            'Diff\.M' => 'nullable|integer',
            'FVM' => 'required|integer|min:0',
            'FVM M' => 'nullable|integer|min:0',
        ];
    }
}
