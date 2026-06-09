@extends('layouts.app')
@section('title', 'Mes Cours')
@section('header-title', 'Espace Classroom — Mes Modules')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($modules as $module)
                <a href="{{ route('etudiant.cours.show', $module->id) }}" class="bg-white shadow rounded-2xl border border-slate-100 p-6 hover:shadow-md hover:border-primary-100 transition group">
                    <div class="flex items-start justify-between">
                        <div class="p-3 rounded-xl bg-primary-50 text-primary-600 group-hover:bg-primary-100 transition">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222 4 2.222V20"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $module->code }}</span>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mt-4">{{ $module->nom }}</h3>
                    <p class="text-sm text-slate-500 mt-1">Prof : {{ $module->professeur->nom_complet }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex space-x-3 text-xs text-slate-400">
                            <span>Coeff. {{ $module->coefficient }}</span>
                            <span>·</span>
                            <span>S{{ $module->semestre }}</span>
                        </div>
                        <svg class="h-4 w-4 text-primary-400 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-400 text-sm">
                    Aucun module disponible pour votre filière.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
