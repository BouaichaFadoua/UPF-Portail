@extends('layouts.app')
@section('title', 'Cahiers de Textes')
@section('header-title', 'Consultation des Cahiers de Textes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Filters bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.cahier-textes.index') }}" method="GET" class="flex items-center gap-3 flex-wrap w-full">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Filtrer par Enseignant</label>
                    <select name="professeur_id" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400 bg-white">
                        <option value="">Tous les enseignants</option>
                        @foreach($professeurs as $prof)
                            <option value="{{ $prof->id }}" {{ $professeur_id == $prof->id ? 'selected' : '' }}>{{ $prof->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Filtrer par Module</label>
                    <select name="module_id" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400 bg-white">
                        <option value="">Tous les modules</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod->id }}" {{ $module_id == $mod->id ? 'selected' : '' }}>{{ $mod->nom }} ({{ $mod->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Date spécifique</label>
                    <input type="date" name="date" value="{{ $date }}" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                </div>

                <div class="pt-5">
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition h-10">Filtrer</button>
                    @if($professeur_id || $module_id || $date)
                        <a href="{{ route('admin.cahier-textes.index') }}" class="ml-2 text-sm text-slate-400 hover:text-slate-600">Réinitialiser</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date & Horaire</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Enseignant</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Nature</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Objectif Pédagogique</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Détails du contenu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($cahiers as $cahier)
                            <tr class="hover:bg-slate-50 transition align-top">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-slate-800">{{ \Carbon\Carbon::parse($cahier->date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ substr($cahier->heure_debut, 0, 5) }} - {{ substr($cahier->heure_fin, 0, 5) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-800">{{ $cahier->module->nom }}</div>
                                    <div class="text-xs text-slate-400">Code: {{ $cahier->module->code }} · {{ $cahier->seance?->groupe?->nom ?? 'Hors EDT' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-800">{{ $cahier->professeur->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $cahier->professeur->specialite }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        {{ $cahier->nature === 'Cours' ? 'bg-indigo-50 text-indigo-800' :
                                           ($cahier->nature === 'TD' ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-800') }}">
                                        {{ $cahier->nature }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div class="text-sm text-slate-700 font-medium">{{ $cahier->objectif }}</div>
                                </td>
                                <td class="px-6 py-4 max-w-sm">
                                    <div class="text-xs text-slate-500 whitespace-pre-line">{{ $cahier->contenu ?? '—' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-slate-400 text-sm">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Aucune entrée enregistrée dans les cahiers de textes.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cahiers->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $cahiers->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
