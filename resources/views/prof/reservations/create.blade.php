@extends('layouts.app')
@section('title', 'Réserver une Salle')
@section('header-title', 'Demande de Réservation de Salle')

@section('content')
<div class="py-6" x-data="{
    date: '{{ old('date', date('Y-m-d')) }}',
    heure_debut: '{{ old('heure_debut', '08:30') }}',
    heure_fin: '{{ old('heure_fin', '10:15') }}',
    salles: [],
    isLoading: false,
    searched: false,
    selectedSalle: '{{ old('salle_id') }}',
    async fetchSalles() {
        if (!this.date || !this.heure_debut || !this.heure_fin) return;
        this.isLoading = true;
        this.searched = true;
        try {
            const url = '{{ route('prof.reservations.salles-disponibles') }}?date=' + this.date + '&heure_debut=' + this.heure_debut + '&heure_fin=' + this.heure_fin;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if(res.ok) {
                this.salles = await res.json();
            }
        } catch(e) { console.error(e); }
        this.isLoading = false;
    }
}" x-init="fetchSalles()">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('prof.reservations.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à mes réservations
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Formulaire de Réservation</h3>
                <p class="text-xs text-slate-500 mt-1">Les réservations de salles doivent être validées par l'administration avant d'être programmées.</p>
            </div>

            <form action="{{ route('prof.reservations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="font-semibold mb-1">Erreur de réservation :</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6">
                    <!-- Horaires & Date Group -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">Date <span class="text-rose-500">*</span></label>
                            <input type="date" name="date" id="date" x-model="date" @change="fetchSalles()" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Heure début -->
                        <div>
                            <label for="heure_debut" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure début <span class="text-rose-500">*</span></label>
                            <input type="time" name="heure_debut" id="heure_debut" x-model="heure_debut" @change="fetchSalles()" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Heure fin -->
                        <div>
                            <label for="heure_fin" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure fin <span class="text-rose-500">*</span></label>
                            <input type="time" name="heure_fin" id="heure_fin" x-model="heure_fin" @change="fetchSalles()" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>
                    </div>

                    <!-- Salle -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="salle_id" class="block text-sm font-semibold text-slate-700">Salles disponibles <span class="text-rose-500">*</span></label>
                            <span x-show="isLoading" style="display: none;" class="text-xs text-primary-600 flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-primary-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Recherche...
                            </span>
                        </div>
                        <select name="salle_id" id="salle_id" required x-model="selectedSalle"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                            <option value="">-- Sélectionnez une salle --</option>
                            <template x-for="salle in salles" :key="salle.id">
                                <option :value="salle.id" x-text="`${salle.nom} (Capacité: ${salle.capacite} - ${salle.type})`"></option>
                            </template>
                        </select>
                        <p x-show="searched && salles.length === 0 && !isLoading" style="display: none;" class="text-xs text-rose-500 mt-2">Aucune salle disponible pour ce créneau.</p>
                    </div>

                    <!-- Motif -->
                    <div>
                        <label for="motif" class="block text-sm font-semibold text-slate-700 mb-1.5">Motif de la réservation / Événement <span class="text-rose-500">*</span></label>
                        <input type="text" name="motif" id="motif" value="{{ old('motif') }}" required
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Rattrapage de cours de PHP, Réunion département...">
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('prof.reservations.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Soumettre la demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
