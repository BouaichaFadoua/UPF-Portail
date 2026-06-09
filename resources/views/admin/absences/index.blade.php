@extends('layouts.app')
@section('title', 'Consultation des Absences')
@section('header-title', 'Consultation des Absences')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex gap-2">
                <a href="{{ route('admin.absences.index') }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-xl">Absences & Totaux</a>
                <a href="{{ route('admin.absences.justificatifs') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Justificatifs</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow p-5">
                <p class="text-xs font-bold text-slate-500 uppercase">Total absences</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ $statsGlobales['total_absences'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-rose-100 shadow p-5">
                <p class="text-xs font-bold text-rose-500 uppercase">Non justifiées</p>
                <p class="text-2xl font-bold text-rose-700 mt-1">{{ $statsGlobales['non_justifiees'] }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-emerald-100 shadow p-5">
                <p class="text-xs font-bold text-emerald-500 uppercase">Justifiées</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $statsGlobales['justifiees'] }}</p>
            </div>
        </div>

        <form action="{{ route('admin.absences.index') }}" method="GET" class="flex flex-wrap items-center gap-3 mb-6">
            <select name="filiere_id" class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-primary-400">
                <option value="">Toutes les filières</option>
                @foreach($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ $filiere_id == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }}</option>
                @endforeach
            </select>
            <select name="groupe_id" class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-primary-400">
                <option value="">Tous les groupes</option>
                @foreach($groupes as $groupe)
                    <option value="{{ $groupe->id }}" {{ $groupe_id == $groupe->id ? 'selected' : '' }}>{{ $groupe->nom }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher étudiant…" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition">Filtrer</button>
        </form>

        <!-- Export Buttons -->
        <div class="flex justify-end mb-4 gap-2">
            <div x-data="{ openExport: false }" class="relative">
                <button @click="openExport = !openExport" type="button"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-sm">
                    <svg class="h-4 w-4 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Exporter la liste
                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openExport" @click.outside="openExport = false" style="display:none"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-20 overflow-hidden">
                    <a href="{{ route('admin.absences.export-excel', ['filiere_id' => request('filiere_id'), 'groupe_id' => request('groupe_id')]) }}"
                       class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        <svg class="h-4 w-4 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel (CSV)
                    </a>
                    <a href="{{ route('admin.absences.export-pdf', ['filiere_id' => request('filiere_id'), 'groupe_id' => request('groupe_id')]) }}"
                       class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition">
                        <svg class="h-4 w-4 mr-2 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Filière / Groupe</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Justifiées</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Non justifiées</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($etudiants as $etudiant)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $etudiant->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $etudiant->matricule }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $etudiant->filiere->code ?? '—' }} / {{ $etudiant->groupe?->nom ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800">{{ $etudiant->total_absences }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800">{{ $etudiant->absences_justifiees }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-800">{{ $etudiant->absences_non_justifiees }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.absences.show', $etudiant) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-500">Voir détail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-sm">Aucun étudiant trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($etudiants->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $etudiants->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
