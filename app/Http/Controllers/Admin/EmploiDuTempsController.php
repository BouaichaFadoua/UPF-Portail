<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Module;
use App\Models\Groupe;
use App\Models\Salle;
use App\Models\Professeur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        $groupe_id = $request->input('groupe_id');

        if ($request->has(['start', 'end']) || $request->ajax() || $request->wantsJson()) {
            $start = Carbon::parse($request->input('start'))->startOfDay();
            $end = Carbon::parse($request->input('end'))->endOfDay();

            $query = Seance::with(['module', 'groupe', 'salle', 'professeur.user'])
                ->whereBetween('date', [$start, $end]);

            if ($groupe_id) {
                $query->where('groupe_id', $groupe_id);
            }

            $seances = $query->get();

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
                        'professeur' => $s->professeur->user->name ?? 'N/A',
                        'groupe' => $s->groupe->nom ?? 'N/A',
                    ]
                ];
            }

            return response()->json($events);
        }

        $groupes = Groupe::with('filiere')->get();

        return view('admin.edt.index', compact('groupes', 'groupe_id'));
    }

    public function create()
    {
        $modules = Module::with('filiere')->get();
        $groupes = Groupe::with('filiere')->get();
        $salles = Salle::where('disponible', true)->get();
        $professeurs = Professeur::with('user')->get();
        return view('admin.edt.create', compact('modules', 'groupes', 'salles', 'professeurs'));
    }

    public function store(Request $request)
    {
        $data = $this->validateSeance($request);
        $errors = $this->checkConflits($data);

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        Seance::create($data);

        return redirect()->route('admin.edt.index', ['groupe_id' => $request->groupe_id])
            ->with('success', 'Séance ajoutée avec succès à l\'emploi du temps.');
    }

    public function edit(Seance $seance)
    {
        $modules = Module::with('filiere')->get();
        $groupes = Groupe::with('filiere')->get();
        $salles = Salle::where('disponible', true)->get();
        $professeurs = Professeur::with('user')->get();
        return view('admin.edt.edit', compact('seance', 'modules', 'groupes', 'salles', 'professeurs'));
    }

    public function update(Request $request, Seance $seance)
    {
        $data = $this->validateSeance($request);
        $errors = $this->checkConflits($data, $seance->id);

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $seance->update($data);

        return redirect()->route('admin.edt.index', ['groupe_id' => $request->groupe_id])
            ->with('success', 'Séance modifiée avec succès.');
    }

    public function destroy(Seance $seance)
    {
        $seance->delete();
        return back()->with('success', 'Séance supprimée de l\'emploi du temps.');
    }

    private function validateSeance(Request $request): array
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'groupe_id' => 'required|exists:groupes,id',
            'salle_id' => 'required|exists:salles,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i,H:i:s',
            'heure_fin' => 'required|date_format:H:i,H:i:s|after:heure_debut',
            'type' => 'required|in:Cours,TD,TP',
        ]);

        $debut = strlen($request->heure_debut) === 5 ? $request->heure_debut . ':00' : $request->heure_debut;
        $fin = strlen($request->heure_fin) === 5 ? $request->heure_fin . ':00' : $request->heure_fin;

        return [
            'module_id' => $request->module_id,
            'groupe_id' => $request->groupe_id,
            'salle_id' => $request->salle_id,
            'professeur_id' => $request->professeur_id,
            'date' => $request->date,
            'heure_debut' => $debut,
            'heure_fin' => $fin,
            'type' => $request->type,
            'semaine' => Carbon::parse($request->date)->weekOfYear,
        ];
    }

    private function checkConflits(array $data, ?int $excludeId = null): array
    {
        $errors = [];
        $dateStr = $data['date'];
        $debut = $data['heure_debut'];
        $fin = $data['heure_fin'];

        $baseQuery = fn() => Seance::where('date', $dateStr)
            ->where('heure_debut', '<', $fin)
            ->where('heure_fin', '>', $debut)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId));

        if ($baseQuery()->where('salle_id', $data['salle_id'])->exists()) {
            $errors['salle_id'] = 'La salle est déjà occupée sur ce créneau.';
        }

        if ($baseQuery()->where('professeur_id', $data['professeur_id'])->exists()) {
            $errors['professeur_id'] = 'Le professeur a déjà un cours sur ce créneau.';
        }

        if ($baseQuery()->where('groupe_id', $data['groupe_id'])->exists()) {
            $errors['groupe_id'] = 'Ce groupe a déjà un cours sur ce créneau.';
        }

        return $errors;
    }
}
