@extends('layouts.app')
@section('title', 'Nouvelle Demande')
@section('header-title', 'Nouvelle Demande de Document')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Type de document à demander</h3>
            </div>
            <form action="{{ route('etudiant.demandes.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Choisissez un document <span class="text-rose-500">*</span></label>
                    <div class="space-y-3">
                        @php
                            $types = [
                                'attestation_scolarite' => ['label' => 'Attestation de scolarité', 'icon' => '🎓', 'desc' => 'Certifie votre inscription pour l\'année universitaire en cours.'],
                                'certificat_inscription' => ['label' => 'Certificat d\'inscription', 'icon' => '📋', 'desc' => 'Document officiel de confirmation d\'inscription à l\'université.'],
                                'releve_notes' => ['label' => 'Relevé de notes', 'icon' => '📊', 'desc' => 'Relevé complet de toutes vos notes pour l\'année en cours.'],
                            ];
                        @endphp
                        @foreach($types as $value => $type)
                            <label class="flex items-start p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary-300 hover:bg-primary-50/10 transition has-[:checked]:border-primary-400 has-[:checked]:bg-primary-50">
                                <input type="radio" name="type" value="{{ $value }}" class="mt-1 h-4 w-4 text-primary-600 border-slate-300 focus:ring-primary-500" {{ old('type') === $value ? 'checked' : '' }}>
                                <div class="ml-3">
                                    <span class="text-sm font-bold text-slate-800">{{ $type['icon'] }} {{ $type['label'] }}</span>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $type['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('type') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="bg-sky-50 border border-sky-100 rounded-xl p-4 text-xs text-sky-800">
                    <strong>Délai de traitement :</strong> Votre demande sera traitée par l'administration dans un délai de 24 à 48 heures. Vous serez notifié dès que le document sera disponible.
                </div>
                <div class="flex space-x-3 justify-end">
                    <a href="{{ route('etudiant.demandes.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 transition">Annuler</a>
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 transition">Soumettre la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
