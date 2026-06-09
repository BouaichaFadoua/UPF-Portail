@extends('layouts.app')
@section('title', 'Appel - ' . $seance->module->nom)
@section('header-title', 'Feuille d\'Émargement')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header details -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('prof.absences.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour aux séances
            </a>
            
            <div class="text-right">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white capitalize mb-1">
                    {{ $seance->groupe->nom }}
                </span>
                <p class="text-xs text-slate-500 font-semibold">{{ \Carbon\Carbon::parse($seance->date)->translatedFormat('l d F Y') }} ({{ substr($seance->heure_debut, 0, 5) }} - {{ substr($seance->heure_fin, 0, 5) }})</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center flex-wrap gap-3">
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Faire l'appel</h3>
                    <p class="text-xs text-slate-500 mt-1">Cochez la case <span class="font-bold text-rose-600">Absent</span> si l'étudiant n'est pas présent en cours.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="markAllPresent()" class="text-xs font-bold text-slate-500 hover:text-slate-800 px-3 py-1.5 bg-slate-100 rounded-lg transition">Tous présents</button>
                    <button type="button" onclick="markAllAbsent()" class="text-xs font-bold text-rose-600 hover:text-rose-800 px-3 py-1.5 bg-rose-50 rounded-lg transition">Tous absents</button>
                </div>
            </div>

            <form action="{{ route('prof.absences.enregistrer', $seance->id) }}" method="POST">
                @csrf

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Matricule</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nom de l'étudiant</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($etudiants as $etudiant)
                                @php
                                    $isAbsent = in_array($etudiant->id, $absent_ids);
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-600">
                                        {{ $etudiant->matricule }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-slate-800">{{ $etudiant->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $etudiant->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <label class="relative inline-flex items-center cursor-pointer select-none bg-slate-100 p-1 rounded-xl">
                                            <input type="checkbox" name="absences[]" value="{{ $etudiant->id }}" 
                                                class="sr-only peer absence-checkbox" {{ $isAbsent ? 'checked' : '' }}
                                                onchange="updateRowStyle(this)">
                                            
                                            <!-- 'Présent' option -->
                                            <div class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all text-emerald-700 bg-white shadow-sm peer-checked:text-slate-500 peer-checked:bg-transparent peer-checked:shadow-none">
                                                Présent
                                            </div>
                                            
                                            <!-- 'Absent' option -->
                                            <div class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-500 bg-transparent shadow-none peer-checked:text-rose-700 peer-checked:bg-white peer-checked:shadow-sm">
                                                Absent
                                            </div>
                                        </label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 text-sm">Aucun étudiant dans ce groupe.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Action Bar -->
                @if($etudiants->isNotEmpty())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end space-x-3">
                        <a href="{{ route('prof.absences.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">Annuler</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            Enregistrer l'appel
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateRowStyle(checkbox) {
        const row = checkbox.closest('tr');
        if (checkbox.checked) {
            row.classList.add('bg-rose-50/20');
        } else {
            row.classList.remove('bg-rose-50/20');
        }
    }

    function markAllPresent() {
        document.querySelectorAll('.absence-checkbox').forEach(cb => {
            cb.checked = false;
            updateRowStyle(cb);
        });
    }

    function markAllAbsent() {
        document.querySelectorAll('.absence-checkbox').forEach(cb => {
            cb.checked = true;
            updateRowStyle(cb);
        });
    }

    // Initialize row styles on load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.absence-checkbox').forEach(cb => {
            updateRowStyle(cb);
        });
    });
</script>
@endsection
