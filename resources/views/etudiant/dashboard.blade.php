@extends('layouts.app')

@section('title', 'Étudiant Dashboard')
@section('header-title', 'Espace Étudiant · Tableau de Bord')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Moyenne générale</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['moyenne_generale'] }}</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Modules évalués</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['notes_count'] }}</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl {{ $stats['total_absences'] > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total absences</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['total_absences'] }}</h3>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl {{ $stats['absences_non_justifiees'] > 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Non justifiées</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['absences_non_justifiees'] }}</h3>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left 2 cols: Today's Classes -->
            <div class="lg:col-span-2 bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/55 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Cours aujourd'hui</h3>
                    <a href="{{ route('etudiant.edt.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-500">Voir l'EDT complet</a>
                </div>
                <div class="p-6">
                    @forelse($today_classes as $class)
                        <div class="flex items-start justify-between p-4 rounded-xl border border-slate-100 hover:border-primary-100 hover:bg-primary-50/10 transition mb-4 last:mb-0">
                            <div class="space-y-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">{{ $class->type }}</span>
                                <h4 class="text-base font-bold text-slate-800">{{ $class->module->nom }}</h4>
                                <p class="text-sm text-slate-500">Salle : {{ $class->salle->nom }} · Prof : {{ $class->professeur->nom_complet }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-slate-700 block">{{ substr($class->heure_debut, 0, 5) }} - {{ substr($class->heure_fin, 0, 5) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-sm">
                            <svg class="h-12 w-12 text-slate-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Aucun cours planifié aujourd'hui.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right 1 col: Notifications -->
            <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden h-fit">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/55">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Notifications récentes</h3>
                </div>
                <div class="p-6 divide-y divide-slate-100">
                    @forelse($notifications as $notif)
                        <div class="py-4 first:pt-0 last:pb-0 flex items-start space-x-3">
                            <div class="p-1.5 rounded-lg mt-0.5 flex-shrink-0
                                @if($notif->type === 'success') bg-emerald-50 text-emerald-500
                                @elseif($notif->type === 'warning') bg-amber-50 text-amber-500
                                @else bg-sky-50 text-sky-500 @endif">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-800">{{ $notif->titre }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $notif->message }}</p>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notif->lu)
                                <span class="h-2 w-2 rounded-full bg-primary-500 flex-shrink-0 mt-1"></span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs">
                            Aucune notification.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
