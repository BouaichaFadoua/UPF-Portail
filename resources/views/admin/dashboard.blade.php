@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header-title', 'Administration · Tableau de Bord')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1 -->
            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Étudiants</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['etudiants_count'] }}</h3>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-violet-50 text-violet-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Enseignants</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['professeurs_count'] }}</h3>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Demandes en attente</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['demandes_en_attente'] }}</h3>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white overflow-hidden shadow rounded-2xl border border-slate-100 flex items-center p-6 space-x-4">
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Réservations en attente</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-0.5">{{ $stats['reservations_en_attente'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Secondary Stats Banner -->
        <div class="mt-6 bg-slate-900 rounded-2xl p-6 text-white flex flex-wrap justify-between items-center border border-slate-800">
            <div class="space-y-1">
                <h2 class="text-xl font-bold">Portail Académique UPF</h2>
                <p class="text-slate-400 text-sm">Gestion centralisée des cursus, présences et réservations.</p>
            </div>
            <div class="flex space-x-6 mt-4 sm:mt-0">
                <div class="text-center">
                    <span class="block text-slate-400 text-xs uppercase font-semibold">Filières</span>
                    <span class="text-xl font-bold text-primary-400">{{ $stats['filieres_count'] }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-slate-400 text-xs uppercase font-semibold">Salles de Cours</span>
                    <span class="text-xl font-bold text-primary-400">{{ $stats['salles_count'] }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-slate-400 text-xs uppercase font-semibold">Justificatifs</span>
                    <span class="text-xl font-bold text-primary-400">{{ $stats['justificatifs_en_attente'] }}</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Absences -->
            <div class="bg-white shadow rounded-2xl border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Absences par Module</h3>
                <div class="relative h-64 w-full">
                    <canvas id="absencesChart"></canvas>
                </div>
            </div>

            <!-- Moyennes -->
            <div class="bg-white shadow rounded-2xl border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Moyennes par Filière</h3>
                <div class="relative h-64 w-full">
                    <canvas id="averagesChart"></canvas>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white shadow rounded-2xl border border-slate-100 p-5">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Distribution des Notes</h3>
                <div class="relative h-64 w-full">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Left panel: Reservations & Justificatifs -->
            <div class="space-y-6">
                <!-- Recent Reservations -->
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Réservations de salles récentes</h3>
                        <a href="{{ route('admin.reservations.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-500">Voir tout</a>
                    </div>
                    <ul class="divide-y divide-slate-100">
                        @forelse($recent_reservations as $res)
                            <li class="p-5 flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-slate-800">{{ $res->salle->nom }} · {{ $res->date->format('d/m/Y') }}</p>
                                    <p class="text-xs text-slate-500">Demandé par : {{ $res->professeur->nom_complet }}</p>
                                    <p class="text-xs text-slate-400">Créneau : {{ substr($res->heure_debut, 0, 5) }} - {{ substr($res->heure_fin, 0, 5) }} · Motif : {{ $res->motif }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($res->statut === 'approuvee') bg-emerald-50 text-emerald-800
                                        @elseif($res->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @elseif($res->statut === 'refusee') bg-rose-50 text-rose-800
                                        @else bg-slate-50 text-slate-800 @endif">
                                        {{ ucfirst($res->statut) }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="p-6 text-center text-sm text-slate-400">Aucune réservation de salle</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Recent Justifications -->
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Justificatifs d'absences récents</h3>
                        <a href="{{ route('admin.absences.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-500">Voir tout</a>
                    </div>
                    <ul class="divide-y divide-slate-100">
                        @forelse($recent_justificatifs as $just)
                            <li class="p-5 flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-slate-800">{{ $just->etudiant->nom_complet }} ({{ $just->etudiant->matricule }})</p>
                                    <p class="text-xs text-slate-500">Séance du {{ $just->absence->seance->date->format('d/m/Y') }} · {{ $just->absence->seance->module->nom }}</p>
                                    <p class="text-xs text-slate-400">Fichier : {{ $just->fichier_nom }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($just->statut === 'valide') bg-emerald-50 text-emerald-800
                                        @elseif($just->statut === 'en_attente') bg-amber-50 text-amber-800
                                        @else bg-rose-50 text-rose-800 @endif">
                                        {{ $just->statut === 'valide' ? 'Validé' : ($just->statut === 'en_attente' ? 'En attente' : 'Rejeté') }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="p-6 text-center text-sm text-slate-400">Aucun justificatif d'absence</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Right panel: Recent administrative requests -->
            <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden h-fit">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Demandes administratives récentes</h3>
                    <a href="{{ route('admin.demandes.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-500">Voir tout</a>
                </div>
                <ul class="divide-y divide-slate-100">
                    @forelse($recent_demandes as $dem)
                        <li class="p-5 flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-slate-800">{{ \App\Models\Demande::$typeLabels[$dem->type] }}</p>
                                <p class="text-xs text-slate-500">Par : {{ $dem->user->name }} · {{ ucfirst($dem->user->role) }}</p>
                                <p class="text-[10px] text-slate-400">Date : {{ $dem->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($dem->statut === 'validee') bg-emerald-50 text-emerald-800
                                    @elseif($dem->statut === 'en_attente') bg-amber-50 text-amber-800
                                    @else bg-rose-50 text-rose-800 @endif">
                                    {{ $dem->statut === 'validee' ? 'Validée' : ($dem->statut === 'en_attente' ? 'En attente' : 'Refusée') }}
                                </span>
                                <a href="{{ route('admin.demandes.show', $dem->id) }}" class="p-1 text-slate-400 hover:text-slate-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-sm text-slate-400">Aucune demande de document</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const absData = @json($absencesByModule);
        new Chart(document.getElementById('absencesChart'), {
            type: 'bar',
            data: {
                labels: absData.map(d => d.module),
                datasets: [{
                    label: 'Absences',
                    data: absData.map(d => d.total),
                    backgroundColor: '#f43f5e',
                    borderRadius: 4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        const avgData = @json($averagesByFiliere);
        new Chart(document.getElementById('averagesChart'), {
            type: 'doughnut',
            data: {
                labels: avgData.map(d => d.filiere),
                datasets: [{
                    data: avgData.map(d => d.moyenne),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#64748b']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        const distData = @json($distribution);
        new Chart(document.getElementById('distributionChart'), {
            type: 'line',
            data: {
                labels: Object.keys(distData),
                datasets: [{
                    label: 'Étudiants',
                    data: Object.values(distData),
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14, 165, 233, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endsection
