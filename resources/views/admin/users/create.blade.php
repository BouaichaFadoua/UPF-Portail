@extends('layouts.app')
@section('title', 'Créer un Utilisateur')
@section('header-title', 'Ajouter un Nouvel Utilisateur')

@section('content')
<div class="py-6" x-data="{ role: '{{ old('role', 'admin') }}' }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Formulaire d'Inscription</h3>
                <p class="text-xs text-slate-500 mt-1">Renseignez les informations générales puis les informations spécifiques au rôle de l'utilisateur.</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="font-semibold mb-1">Veuillez corriger les erreurs suivantes :</div>
                        <ul class="list-disc pl-5 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Section 1: Informations Générales -->
                <div>
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 border-b pb-2">1. Informations de Connexion</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom complet -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nom Complet <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Adresse Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Rôle -->
                        <div>
                            <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">Rôle de l'utilisateur <span class="text-rose-500">*</span></label>
                            <select name="role" id="role" x-model="role" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                <option value="admin">Administrateur</option>
                                <option value="professeur">Enseignant / Professeur</option>
                                <option value="etudiant">Étudiant</option>
                                <option value="personnel">Personnel administratif</option>
                            </select>
                        </div>

                        <!-- Compte Actif -->
                        <div class="flex items-center pt-8">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="actif" value="1" class="sr-only peer" {{ old('actif', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                <span class="ml-3 text-sm font-semibold text-slate-700">Activer le compte immédiatement</span>
                            </label>
                        </div>

                        <!-- Mot de passe -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Mot de Passe <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" id="password" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Confirmer le Mot de Passe <span class="text-rose-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informations spécifiques Étudiant -->
                <div x-show="role === 'etudiant'" x-collapse style="display: none;">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 border-b pb-2">2. Informations de Scolarité (Étudiant)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Matricule -->
                        <div>
                            <label for="matricule" class="block text-sm font-semibold text-slate-700 mb-1.5">Numéro de Matricule <span class="text-rose-500">*</span></label>
                            <input type="text" name="matricule" id="matricule" value="{{ old('matricule') }}" x-bind:required="role === 'etudiant'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: ETU2026001">
                        </div>

                        <!-- Filiere -->
                        <div>
                            <label for="filiere_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Filière <span class="text-rose-500">*</span></label>
                            <select name="filiere_id" id="filiere_id" x-bind:required="role === 'etudiant'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                <option value="">-- Sélectionner une filière --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>{{ $filiere->nom }} ({{ $filiere->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Groupe -->
                        <div>
                            <label for="groupe_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Groupe d'étudiants (Optionnel)</label>
                            <select name="groupe_id" id="groupe_id"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                                <option value="">-- Aucun groupe --</option>
                                @foreach($groupes as $groupe)
                                    <option value="{{ $groupe->id }}" {{ old('groupe_id') == $groupe->id ? 'selected' : '' }}>{{ $groupe->nom }} ({{ $groupe->filiere->code ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date de naissance -->
                        <div>
                            <label for="date_naissance" class="block text-sm font-semibold text-slate-700 mb-1.5">Date de naissance</label>
                            <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone_etudiant" class="block text-sm font-semibold text-slate-700 mb-1.5">N° Téléphone</label>
                            <input type="text" name="telephone" id="telephone_etudiant" value="{{ old('telephone') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: 06 12 34 56 78">
                        </div>

                        <!-- Adresse -->
                        <div>
                            <label for="adresse" class="block text-sm font-semibold text-slate-700 mb-1.5">Adresse de résidence</label>
                            <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Quartier Riad, Rabat">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informations spécifiques Personnel -->
                <div x-show="role === 'personnel'" x-collapse style="display: none;">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 border-b pb-2">2. Informations Administratives (Personnel)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="poste" class="block text-sm font-semibold text-slate-700 mb-1.5">Poste / Fonction <span class="text-rose-500">*</span></label>
                            <input type="text" name="poste" id="poste" value="{{ old('poste') }}" x-bind:required="role === 'personnel'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Secrétaire, Agent de scolarité">
                        </div>
                        <div>
                            <label for="service" class="block text-sm font-semibold text-slate-700 mb-1.5">Service / Département</label>
                            <input type="text" name="service" id="service" value="{{ old('service') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Scolarité, RH">
                        </div>
                        <div>
                            <label for="telephone_personnel" class="block text-sm font-semibold text-slate-700 mb-1.5">N° Téléphone</label>
                            <input type="text" name="telephone" id="telephone_personnel" value="{{ old('telephone') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informations spécifiques Professeur -->
                <div x-show="role === 'professeur'" x-collapse style="display: none;">
                    <h4 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 border-b pb-2">2. Informations Académiques (Enseignant)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Grade -->
                        <div>
                            <label for="grade" class="block text-sm font-semibold text-slate-700 mb-1.5">Grade / Titre <span class="text-rose-500">*</span></label>
                            <input type="text" name="grade" id="grade" value="{{ old('grade') }}" x-bind:required="role === 'professeur'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: PES, PH, Assistant">
                        </div>

                        <!-- Spécialité -->
                        <div>
                            <label for="specialite" class="block text-sm font-semibold text-slate-700 mb-1.5">Spécialité d'enseignement <span class="text-rose-500">*</span></label>
                            <input type="text" name="specialite" id="specialite" value="{{ old('specialite') }}" x-bind:required="role === 'professeur'"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Intelligence Artificielle, Réseaux, PHP">
                        </div>

                        <!-- Bureau -->
                        <div>
                            <label for="bureau" class="block text-sm font-semibold text-slate-700 mb-1.5">Bureau assigné (Optionnel)</label>
                            <input type="text" name="bureau" id="bureau" value="{{ old('bureau') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Bureau A-12">
                        </div>

                        <!-- Téléphone -->
                        <div>
                            <label for="telephone_prof" class="block text-sm font-semibold text-slate-700 mb-1.5">N° Téléphone</label>
                            <input type="text" name="telephone" id="telephone_prof" value="{{ old('telephone') }}"
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: 06 98 76 54 32">
                        </div>
                    </div>
                </div>

                <!-- Submit bar -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
