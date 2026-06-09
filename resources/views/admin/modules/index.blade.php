@extends('layouts.app')
@section('title', 'Gestion des Modules')
@section('header-title', 'Gestion des Modules')

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
        @if(session('error'))
            <div class="mb-5 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium px-4 py-3 rounded-xl">
                <svg class="h-5 w-5 text-rose-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Action bar -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <form action="{{ route('admin.modules.index') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <select name="filiere_id" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                    <option value="">Toutes les filières</option>
                    @foreach($filieres as $filiere)
                        <option value="{{ $filiere->id }}" {{ $filiere_id == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un module…" class="text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400">
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-slate-700 rounded-xl hover:bg-slate-600 transition">Filtrer</button>
                @if($filiere_id || $search)
                    <a href="{{ route('admin.modules.index') }}" class="text-sm text-slate-400 hover:text-slate-600">Réinitialiser</a>
                @endif
            </form>
            <a href="{{ route('admin.modules.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouveau module
            </a>
        </div>

        {{-- Table --}}
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Filière</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Enseignant</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Coeff.</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Semestre</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Vol. Horaire</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($modules as $module)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-800">{{ $module->nom }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold tracking-wide">
                                        {{ $module->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-600">{{ $module->filiere->nom }}</div>
                                    <div class="text-xs text-slate-400">{{ $module->filiere->code }} · {{ $module->filiere->niveau }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($module->professeur)
                                        <div class="text-sm font-medium text-slate-800">{{ $module->professeur->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $module->professeur->specialite }}</div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Aucun enseignant affecté</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                                    {{ number_format($module->coefficient, 1) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        Semestre {{ $module->semestre }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-600">
                                    {{ $module->volume_horaire }}h
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.modules.edit', $module->id) }}"
                                           class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition"
                                           title="Modifier">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.modules.destroy', $module->id) }}" method="POST"
                                              onsubmit="return confirm('Supprimer le module « {{ $module->nom }} » ?')">
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
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <p class="text-slate-400 text-sm">Aucun module enregistré.</p>
                                        <a href="{{ route('admin.modules.create') }}" class="text-primary-600 text-sm font-medium hover:underline">
                                            Créer le premier module →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($modules->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $modules->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
