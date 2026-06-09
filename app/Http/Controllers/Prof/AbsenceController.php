<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Etudiant;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenceController extends Controller
{
    public function index()
    {
        $prof = auth()->user()->professeur;
        // List sessions of this week
        $seances = Seance::with(['module', 'groupe', 'salle'])
            ->where('professeur_id', $prof->id)
            ->orderBy('date', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->paginate(15);

        return view('prof.absences.index', compact('seances'));
    }

    public function appel(Seance $seance)
    {
        $prof = auth()->user()->professeur;

        if ($seance->professeur_id !== $prof->id) {
            abort(403, 'Vous n\'êtes pas l\'enseignant de cette séance.');
        }

        // Get students in this session's group
        $etudiants = Etudiant::with('user')->where('groupe_id', $seance->groupe_id)->get();

        // Get already recorded absences for this session
        $absent_ids = Absence::where('seance_id', $seance->id)->pluck('etudiant_id')->toArray();

        return view('prof.absences.seance', compact('seance', 'etudiants', 'absent_ids'));
    }

    public function enregistrer(Request $request, Seance $seance)
    {
        $prof = auth()->user()->professeur;

        if ($seance->professeur_id !== $prof->id) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'absences' => 'nullable|array',
            'absences.*' => 'exists:etudiants,id',
        ]);

        $absents_input = $request->input('absences', []);

        DB::transaction(function() use ($seance, $absents_input) {
            // Get all students in the group
            $all_student_ids = Etudiant::where('groupe_id', $seance->groupe_id)->pluck('id')->toArray();

            foreach ($all_student_ids as $etudiant_id) {
                $is_absent = in_array($etudiant_id, $absents_input);

                $existingAbsence = Absence::where('seance_id', $seance->id)
                    ->where('etudiant_id', $etudiant_id)
                    ->first();

                if ($is_absent) {
                    if (!$existingAbsence) {
                        Absence::create([
                            'seance_id' => $seance->id,
                            'etudiant_id' => $etudiant_id,
                            'justifiee' => false,
                            'motif' => null,
                        ]);
                    }
                } else {
                    if ($existingAbsence) {
                        // Delete absence unless it was already validated as justified by Admin
                        if (!$existingAbsence->justifiee) {
                            $existingAbsence->delete();
                        }
                    }
                }
            }
        });

        return redirect()->route('prof.absences.index')
            ->with('success', 'La liste d\'appel a été enregistrée avec succès.');
    }
}
