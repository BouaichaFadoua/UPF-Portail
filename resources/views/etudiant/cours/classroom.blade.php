@extends('layouts.app')
@section('title', $module->nom . ' - Classroom')
@section('header-title', $module->nom)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left: Annonces & Commentaires -->
            <div class="lg:col-span-2 space-y-6">
                @forelse($annonces as $annonce)
                    <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-start space-x-3">
                            <div class="h-9 w-9 rounded-full bg-primary-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ substr($annonce->professeur->nom_complet, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $annonce->professeur->nom_complet }}</p>
                                <p class="text-xs text-slate-400">{{ $annonce->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="px-6 py-5">
                            <h4 class="text-base font-bold text-slate-800">{{ $annonce->titre }}</h4>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $annonce->contenu }}</p>
                        </div>
                        <!-- Comments -->
                        @if($annonce->commentaires->count() > 0)
                            <div class="border-t border-slate-100 px-6 py-4 space-y-3 bg-slate-50/50">
                                @foreach($annonce->commentaires as $commentaire)
                                    <div class="flex items-start space-x-3">
                                        <div class="h-7 w-7 rounded-full bg-slate-400 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                            {{ substr($commentaire->user->name, 0, 2) }}
                                        </div>
                                        <div class="flex-1 bg-white border border-slate-100 rounded-xl px-3 py-2">
                                            <p class="text-xs font-semibold text-slate-700">{{ $commentaire->user->name }}</p>
                                            <p class="text-xs text-slate-600 mt-0.5">{{ $commentaire->contenu }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <!-- Comment Form -->
                        <div class="border-t border-slate-100 px-6 py-4">
                            <form action="{{ route('etudiant.cours.commentaire', $annonce->id) }}" method="POST" class="flex items-center space-x-3">
                                @csrf
                                <input type="text" name="contenu" required placeholder="Écrire un commentaire…"
                                    class="flex-1 text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary-400 focus:ring-1 focus:ring-primary-400">
                                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 transition">Poster</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow rounded-2xl border border-slate-100 p-12 text-center text-slate-400 text-sm">
                        Aucune annonce pour ce module.
                    </div>
                @endforelse
            </div>

            <!-- Right: Supports -->
            <div class="space-y-4">
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Supports de cours</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse($supports as $support)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-primary-100 hover:bg-primary-50/10 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="p-2 rounded-lg
                                        {{ $support->type === 'Cours' ? 'bg-sky-50 text-sky-600' :
                                           ($support->type === 'TD' ? 'bg-violet-50 text-violet-600' :
                                           ($support->type === 'TP' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600')) }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-800">{{ $support->titre }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $support->type }} · {{ round($support->taille / 1024) }} Ko</p>
                                    </div>
                                </div>
                                <a href="{{ route('etudiant.cours.support.telecharger', $support->id) }}" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                            </div>
                        @empty
                            <p class="text-center py-4 text-xs text-slate-400">Aucun support déposé.</p>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('etudiant.cours.index') }}" class="flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Retour aux modules
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
