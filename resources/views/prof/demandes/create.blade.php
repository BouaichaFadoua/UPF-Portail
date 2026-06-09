@extends('layouts.app')
@section('title', 'Nouvelle Demande')
@section('header-title', 'Nouvelle Demande de Document')

@section('content')
<div class="py-6" x-data="{ type: '{{ old('type', 'attestation_travail') }}' }">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('prof.demandes.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à mes demandes
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Formuler une Demande Administrative</h3>
                <p class="text-xs text-slate-500 mt-1">Choisissez le type de document souhaité. Les ordres de mission nécessitent des informations supplémentaires.</p>
            </div>

            <form action="{{ route('prof.demandes.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="font-semibold mb-1">Erreur de validation :</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Quel document demandez-vous ?</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="relative flex p-4 rounded-xl border cursor-pointer hover:bg-slate-50 transition focus:outline-none"
                            :class="type === 'attestation_travail' ? 'border-primary-500 bg-primary-50/20' : 'border-slate-200'">
                            <input type="radio" name="type" value="attestation_travail" x-model="type" class="sr-only">
                            <div class="flex flex-col">
                                <span class="block text-sm font-bold text-slate-800">Attestation de travail</span>
                                <span class="block text-xs text-slate-400 mt-1">Justificatif d'activité professionnelle à l'UPF.</span>
                            </div>
                        </label>

                        <label class="relative flex p-4 rounded-xl border cursor-pointer hover:bg-slate-50 transition focus:outline-none"
                            :class="type === 'ordre_mission' ? 'border-primary-500 bg-primary-50/20' : 'border-slate-200'">
                            <input type="radio" name="type" value="ordre_mission" x-model="type" class="sr-only">
                            <div class="flex flex-col">
                                <span class="block text-sm font-bold text-slate-800">Ordre de mission</span>
                                <span class="block text-xs text-slate-400 mt-1">Autorisation de déplacement professionnel temporaire.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Mission Fields -->
                <div x-show="type === 'ordre_mission'" x-collapse class="space-y-6 pt-4 border-t border-slate-100" style="display: none;">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Détails de la mission</h4>
                    
                    <!-- Destination -->
                    <div>
                        <label for="destination" class="block text-sm font-semibold text-slate-700 mb-1.5">Lieu de destination <span class="text-rose-500">*</span></label>
                        <input type="text" name="destination" id="destination" value="{{ old('destination') }}" x-bind:required="type === 'ordre_mission'"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Casablanca, Paris, Université Cadi Ayyad Marrakech">
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date_depart" class="block text-sm font-semibold text-slate-700 mb-1.5">Date de départ <span class="text-rose-500">*</span></label>
                            <input type="date" name="date_depart" id="date_depart" value="{{ old('date_depart') }}" x-bind:required="type === 'ordre_mission'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <div>
                            <label for="date_retour" class="block text-sm font-semibold text-slate-700 mb-1.5">Date de retour <span class="text-rose-500">*</span></label>
                            <input type="date" name="date_retour" id="date_retour" value="{{ old('date_retour') }}" x-bind:required="type === 'ordre_mission'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>
                    </div>

                    <!-- Motif / Objet -->
                    <div>
                        <label for="motif_mission" class="block text-sm font-semibold text-slate-700 mb-1.5">Objet / Motif de la mission <span class="text-rose-500">*</span></label>
                        <textarea name="motif_mission" id="motif_mission" rows="4" x-bind:required="type === 'ordre_mission'"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Participation au jury de thèse, Animation d'un atelier scientifique..."></textarea>
                    </div>
                </div>

                <!-- Submit Bar -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('prof.demandes.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Annuler</a>
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
