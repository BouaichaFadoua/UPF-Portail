@extends('layouts.app')
@section('title', 'Gestion des Notes')
@section('header-title', 'Gestion des Notes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium px-4 py-3 rounded-xl">
                <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters bar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm mb-6">
            <form action="{{ route('admin.notes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Filière</label>
                    <select name="filiere_id" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-primary-400">
                        <option value="">Toutes les filières</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere->id }}" {{ $filiere_id == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Groupe</label>
                    <select name="groupe_id" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-primary-400">
                        <option value="">Tous les groupes</option>
                        @foreach($groupes as $groupe)
                            <option value="{{ $groupe->id }}" {{ $groupe_id == $groupe->id ? 'selected' : '' }}>{{ $groupe->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Module</label>
                    <select name="module_id" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-primary-400">
                        <option value="">Tous les modules</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" {{ $module_id == $module->id ? 'selected' : '' }}>{{ $module->nom }} ({{ $module->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Rechercher Étudiant</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nom de l'étudiant…" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                </div>

                <div class="md:col-span-4 flex items-center justify-between mt-2 pt-4 border-t border-slate-50">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition">
                            Appliquer les filtres
                        </button>
                        @if($filiere_id || $groupe_id || $module_id || $search)
                            <a href="{{ route('admin.notes.index') }}" class="text-sm text-slate-400 hover:text-slate-600 px-3">Réinitialiser</a>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Export Dropdown -->
                        <div x-data="{ openExport: false }" class="relative">
                            <button @click="openExport = !openExport" type="button" class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition">
                                <svg class="h-4 w-4 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Exporter
                                <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="openExport" @click.outside="openExport = false" style="display:none" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 z-20 overflow-hidden">
                                <a href="{{ route('admin.notes.export-excel', ['filiere_id' => $filiere_id, 'groupe_id' => $groupe_id, 'module_id' => $module_id]) }}"
                                   class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <svg class="h-4 w-4 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Excel (CSV)
                                </a>
                                <a href="{{ route('admin.notes.export-pdf', ['filiere_id' => $filiere_id, 'groupe_id' => $groupe_id, 'module_id' => $module_id]) }}"
                                   class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition">
                                    <svg class="h-4 w-4 mr-2 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    PDF
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('admin.notes.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Saisir une Note
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Étudiant</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">CC1</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">CC2</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Examen</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Note Finale</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Mention</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Année</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($notes as $note)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-slate-800">{{ $note->etudiant->user->name }}</div>
                                    <div class="text-xs text-slate-400">Matricule : {{ $note->etudiant->matricule }} · {{ $note->etudiant->groupe?->nom ?? 'Aucun groupe' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-800">{{ $note->module->nom }}</div>
                                    <div class="text-xs text-slate-400">Code : {{ $note->module->code }} ({{ $note->etudiant->filiere->code }})</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-700">
                                    {{ $note->cc1 !== null ? number_format($note->cc1, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-700">
                                    {{ $note->cc2 !== null ? number_format($note->cc2, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-700">
                                    {{ $note->examen !== null ? number_format($note->examen, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-indigo-700">
                                    {{ $note->note_finale !== null ? number_format($note->note_finale, 2) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $note->note_finale >= 10 ? 'bg-emerald-50 text-emerald-800' : 'bg-rose-50 text-rose-800' }}">
                                        {{ $note->mention }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs text-slate-500">
                                    {{ $note->annee_universitaire }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.notes.edit', $note->id) }}"
                                           class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition"
                                           title="Modifier">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.notes.destroy', $note->id) }}" method="POST"
                                              onsubmit="return confirm('Supprimer cette note ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                                                    title="Supprimer">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center text-slate-400 text-sm">
                                    Aucune note enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notes->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $notes->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
