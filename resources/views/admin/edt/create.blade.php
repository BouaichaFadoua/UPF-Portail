@extends('layouts.app')
@section('title', 'Ajouter une Séance')
@section('header-title', 'Ajouter une Séance à l\'Emploi du Temps')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.edt.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à l'emploi du temps
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Planifier une Séance</h3>
                <p class="text-xs text-slate-500 mt-1">L'application vérifiera automatiquement les conflits de salle, de professeur et de groupe sur le créneau horaire choisi.</p>
            </div>

            <form action="{{ route('admin.edt.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="font-semibold mb-1">Erreur de planification :</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6">
                    <!-- Module -->
                    <div>
                        <label for="module_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Module <span class="text-rose-500">*</span></label>
                        <select name="module_id" id="module_id" required
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                            <option value="">-- Sélectionner le module --</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>
                                    {{ $module->nom }} ({{ $module->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Groupe -->
                    <div>
                        <label for="groupe_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Groupe d'étudiants <span class="text-rose-500">*</span></label>
                        <select name="groupe_id" id="groupe_id" required
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                            <option value="">-- Sélectionner le groupe --</option>
                            @foreach($groupes as $groupe)
                                <option value="{{ $groupe->id }}" {{ old('groupe_id') == $groupe->id ? 'selected' : '' }}>
                                    {{ $groupe->nom }} ({{ $groupe->filiere->nom ?? '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Professeur -->
                    <div>
                        <label for="professeur_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Professeur / Enseignant <span class="text-rose-500">*</span></label>
                        <select name="professeur_id" id="professeur_id" required
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                            <option value="">-- Sélectionner le professeur --</option>
                            @foreach($professeurs as $prof)
                                <option value="{{ $prof->id }}" {{ old('professeur_id') == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->user->name }} ({{ $prof->specialite }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Salle -->
                    <div>
                        <label for="salle_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Salle de cours <span class="text-rose-500">*</span></label>
                        <select name="salle_id" id="salle_id" required
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                            <option value="">-- Sélectionner la salle --</option>
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                    {{ $salle->nom }} (Capacité: {{ $salle->capacite }} - {{ $salle->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date & Nature -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">Date de la séance <span class="text-rose-500">*</span></label>
                            <input type="date" name="date" id="date" value="{{ old('date') }}" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Nature du cours -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">Type de séance <span class="text-rose-500">*</span></label>
                            <select name="type" id="type" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                <option value="Cours" {{ old('type') == 'Cours' ? 'selected' : '' }}>Cours Magistral (CM)</option>
                                <option value="TD" {{ old('type') == 'TD' ? 'selected' : '' }}>Travaux Dirigés (TD)</option>
                                <option value="TP" {{ old('type') == 'TP' ? 'selected' : '' }}>Travaux Pratiques (TP)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Horaires -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Heure début -->
                        <div>
                            <label for="heure_debut" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure de début <span class="text-rose-500">*</span></label>
                            <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', '08:30') }}" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Heure fin -->
                        <div>
                            <label for="heure_fin" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure de fin <span class="text-rose-500">*</span></label>
                            <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', '10:15') }}" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.edt.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Planifier la séance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
