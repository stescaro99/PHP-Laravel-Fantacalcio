<?php

namespace App\Http\Controllers;

use App\Imports\PlayersImport;
use App\Exports\PlayersTemplateExport;
use App\Models\Player;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;

class PlayerImportController extends Controller
{
    /**
     * Mostra la pagina principale dei giocatori con filtri e ordinamento
     */
    public function index(Request $request)
    {
        $query = Player::query();
        if ($request->filled('role')) {
            $query->where('position', $request->input('role'));
        }
        if ($request->filled('team')) {
            $query->where('team', 'like', '%'.$request->input('team').'%');
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->input('name').'%');
        }
        // Ordinamento
        $orderable = [
            'quotation', 'initial_quotation', 'difference',
            'mantra_quotation', 'initial_mantra_quotation', 'mantra_difference',
            'value', 'mantra_value'
        ];
        $orderBy = $request->input('order_by');
        $orderDir = $request->input('order_dir', 'asc');
        if (in_array($orderBy, $orderable)) {
            $query->orderBy($orderBy, $orderDir === 'desc' ? 'desc' : 'asc');
        }
        $players = $query->get();
        return view('players.index', compact('players'));
    }

    /**
     * Mostra il form per l'upload del file Excel
     */
    public function showImportForm()
    {
        return view('players.import');
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
            $import = new PlayersImport();
            Excel::import($import, $request->file('file'));
            return redirect()->back()->with('success', 'Giocatori importati con successo!');
        } catch (ExcelValidationException $e) {
            $messages = [];
            foreach ($e->failures() as $failure) {
                $messages[] = 'Riga '.$failure->row().' (colonna '.$failure->attribute().'): '.implode('; ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Errori di validazione: '.implode(' | ', $messages));
        } catch (\Throwable $e) {
            Log::error('Errore import giocatori', ['exception' => $e]);
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
            $path = $request->file('file')->store('temp');
            Excel::queueImport(new PlayersImport, $path);
            return redirect()->back()->with('success', 'Importazione avviata in background.');
        } catch (\Throwable $e) {
            Log::error('Errore import background giocatori', ['exception' => $e]);
            return redirect()->back()->with('error', 'Errore durante l\'importazione: '.$e->getMessage());
        }
    }

    /**
     * Scarica il template Excel di esempio
     */
    public function exportTemplate()
    {
        return Excel::download(new PlayersTemplateExport, 'template_giocatori.xlsx');
    }
}
