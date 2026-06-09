<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CahierTexte;
use App\Models\Professeur;
use App\Models\Module;
use Illuminate\Http\Request;

class CahierTexteController extends Controller
{
    public function index(Request $request)
    {
        $professeur_id = $request->input('professeur_id');
        $module_id = $request->input('module_id');
        $date = $request->input('date');

        $query = CahierTexte::with(['professeur.user', 'module', 'seance.groupe']);

        if ($professeur_id) {
            $query->where('professeur_id', $professeur_id);
        }

        if ($module_id) {
            $query->where('module_id', $module_id);
        }

        if ($date) {
            $query->where('date', $date);
        }

        $cahiers = $query->latest('date')->paginate(15);
        $professeurs = Professeur::with('user')->get();
        $modules = Module::all();

        return view('admin.cahier-textes.index', compact('cahiers', 'professeurs', 'modules', 'professeur_id', 'module_id', 'date'));
    }
}
