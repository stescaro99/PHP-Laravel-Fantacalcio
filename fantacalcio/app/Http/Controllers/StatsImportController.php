<?php

namespace App\Http\Controllers;

use App\Imports\StatsImport;
use App\Exports\StatsTemplateExport;
use App\Models\Stat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class StatsImportController extends Controller
{
    /**
     * Mostra la pagina principale dei giocatori con filtri e ordinamento
     */
    public function index(Request $request)
    {
        $query = Stat::query();
        if ($request->filled('role')) {
            $query->where('position', $request->input('role'));
        }
        if ($request->filled('team')) {
            $query->where('team', 'like', '%'.$request->input('team').'%');
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->input('name').'%');
        }
        if ($request->filled('season')) {
            $query->where('season', $request->input('season'));
        }
        $orderable = [
            'n_votes', 'average_vote', 'average_fantavote', 'goals', 'goals_conceded',
            'catched_penalties', 'taken_penalties', 'scored_penalties', 'missed_penalties',
            'assists', 'yellow_cards', 'red_cards', 'own_goals'
        ];
        $orderBy = $request->input('order_by');
        $orderDir = $request->input('order_dir', 'asc');
        if (in_array($orderBy, $orderable)) {
            $query->orderBy($orderBy, $orderDir === 'desc' ? 'desc' : 'asc');
        }
        $stats = $query->get();
        return view('stats.index', compact('stats'));
    }

    /**
     * Mostra il form per l'upload del file Excel
     */
    public function showImportForm()
    {
        return view('stats.import');
    }

    /**
     * Importa i giocatori dal file Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $filename = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $import = new StatsImport($filename);
            Excel::import($import, $request->file('file'));
            return redirect()->back()->with('success', 'Statistiche importate con successo!');
        } catch (ExcelValidationException $e) {
            $messages = [];
            foreach ($e->failures() as $failure) {
                $messages[] = 'Riga '.$failure->row().' (colonna '.$failure->attribute().'): '.implode('; ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Errori di validazione: '.implode(' | ', $messages));
        } catch (\Throwable $e) {
            Log::error('Errore import statistiche', ['exception' => $e]);
            return redirect()->back()->with('error', 'Errore durante l\'importazione: '.$e->getMessage());
        }
    }

    /**
     * Importa i giocatori in background (per file grandi)
     */
    public function importInBackground(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $filename = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);
            $path = $request->file('file')->store('temp');
            Excel::queueImport(new StatsImport($filename), $path);
            return redirect()->back()->with('success', 'Importazione avviata in background.');
        } catch (\Throwable $e) {
            Log::error('Errore import background statistiche', ['exception' => $e]);
            return redirect()->back()->with('error', 'Errore durante l\'importazione: '.$e->getMessage());
        }
    }

    /**
     * Scarica il template Excel di esempio
     */
    public function exportTemplate()
    {
        return Excel::download(new StatsTemplateExport, 'template_stats.xlsx');
    }
}
