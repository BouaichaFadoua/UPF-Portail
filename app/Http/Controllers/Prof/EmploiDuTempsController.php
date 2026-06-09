<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        $prof = auth()->user()->professeur;
        if (!$prof) {
            abort(404, 'Profil enseignant introuvable.');
        }

        if ($request->has(['start', 'end']) || $request->ajax() || $request->wantsJson()) {
            $start = Carbon::parse($request->input('start'))->startOfDay();
            $end = Carbon::parse($request->input('end'))->endOfDay();

            $seances = Seance::with(['module', 'salle', 'groupe'])
                ->where('professeur_id', $prof->id)
                ->whereBetween('date', [$start, $end])
                ->get();

            $events = [];
            foreach ($seances as $s) {
                $events[] = [
                    'id' => $s->id,
                    'title' => ($s->module->nom ?? 'N/A') . ' (' . $s->type . ') - ' . ($s->salle->nom ?? 'N/A'),
                    'start' => Carbon::parse($s->date)->format('Y-m-d') . 'T' . $s->heure_debut,
                    'end' => Carbon::parse($s->date)->format('Y-m-d') . 'T' . $s->heure_fin,
                    'backgroundColor' => $s->type === 'Cours' ? '#3b82f6' : ($s->type === 'TD' ? '#10b981' : '#f59e0b'),
                    'borderColor' => 'transparent',
                    'extendedProps' => [
                        'groupe' => $s->groupe->nom ?? 'N/A',
                    ]
                ];
            }

            return response()->json($events);
        }

        return view('prof.edt.index');
    }
}
