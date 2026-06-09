@extends('layouts.app')
@section('title', 'Classroom - Mes Modules')
@section('header-title', 'Classroom · Enseignement')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white shadow rounded-2xl border border-slate-100 p-6 mb-6">
            <h3 class="text-base font-bold text-slate-800">Espace Classroom</h3>
            <p class="text-xs text-slate-500 mt-1">Gérez le contenu en ligne de vos cours, publiez des annonces importantes et partagez des supports de cours (PDF, Diaporamas, TP) avec vos étudiants.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($modules as $module)
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden flex flex-col hover:shadow-md transition">
                    <div class="p-6 flex-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-50 text-primary-800 uppercase">
                            {{ $module->code }}
                        </span>
                        <h4 class="text-lg font-bold text-slate-800 mt-2 line-clamp-1" title="{{ $module->nom }}">{{ $module->nom }}</h4>
                        <p class="text-xs text-slate-400 font-semibold mt-1">{{ $module->filiere->nom }}</p>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-slate-100">
                            <div class="text-center p-3 bg-slate-50 rounded-xl">
                                <span class="block text-2xl font-bold text-slate-700">{{ $module->annonces_count }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Annonces</span>
                            </div>
                            <div class="text-center p-3 bg-slate-50 rounded-xl">
                                <span class="block text-2xl font-bold text-slate-700">{{ $module->supports_count }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fichiers</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex items-center justify-end">
                        <a href="{{ route('prof.cours.show', $module->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-sm transition">
                            Entrer
                            <svg class="ml-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white shadow rounded-2xl border border-slate-100 p-12 text-center text-slate-400">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-sm font-semibold">Aucun module disponible</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
