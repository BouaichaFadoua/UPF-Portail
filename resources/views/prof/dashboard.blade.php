@extends('layouts.app')

@section('title', 'Enseignant Dashboard')
@section('header-title', 'Espace Enseignant · Tableau de Bord')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mes Modules</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['modules_count'] }}</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Séances de cours</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['seances_count'] }}</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Réservations</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['reservations_count'] }}</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Cahiers rédigés</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['cahier_textes_count'] }}</h3>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left 2 cols: Today's Classes -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/55 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Cours aujourd'hui</h3>
                        <span class="text-xs text-slate-400">{{ Carbon\Carbon::today()->translatedFormat('l d F Y') }}</span>
                    </div>
                    <div class="p-6">
                        @forelse($today_classes as $class)
                            <div class="flex items-start justify-between p-4 rounded-xl border border-slate-100 hover:border-primary-100 hover:bg-primary-50/10 transition mb-4 last:mb-0">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                        {{ $class->type }}
                                    </span>
                                    <h4 class="text-base font-bold text-slate-800">{{ $class->module->nom }}</h4>
                                    <p class="text-sm text-slate-500">Groupe : {{ $class->groupe->nom }} · Salle : {{ $class->salle->nom }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-slate-700 block">{{ substr($class->heure_debut, 0, 5) }} - {{ substr($class->heure_fin, 0, 5) }}</span>
                                    <div class="mt-2 flex space-x-2">
                                        <a href="{{ route('prof.absences.appel', $class->id) }}" class="inline-flex items-center px-2.5 py-1 border border-slate-200 text-xs font-semibold rounded-lg bg-white text-slate-700 hover:bg-slate-50">
                                            Appel
                                        </a>
                                        @if(!$class->cahierTexte)
                                            <a href="{{ route('prof.cahier-textes.create', ['seance_id' => $class->id]) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg bg-primary-600 text-white hover:bg-primary-500">
                                                Cahier de Texte
                                            </a>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                Rempli
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-slate-400 text-sm">
                                Aucun cours planifié aujourd'hui.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right 1 col: Pending Reservations -->
            <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden h-fit">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/55">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Réservations en attente</h3>
                </div>
                <div class="p-6 divide-y divide-slate-100">
                    @forelse($pending_reservations as $res)
                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-semibold text-slate-800">{{ $res->salle->nom }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 uppercase">En attente</span>
                            </div>
                            <p class="text-xs text-slate-500">{{ $res->date->format('d/m/Y') }} · {{ substr($res->heure_debut, 0, 5) }} - {{ substr($res->heure_fin, 0, 5) }}</p>
                            <p class="text-xs text-slate-400 font-medium italic">Motif : "{{ $res->motif }}"</p>
                            <form action="{{ route('prof.reservations.annuler', $res->id) }}" method="POST" class="pt-1">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-500">Annuler la demande</button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs">
                            Aucune réservation en attente.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
