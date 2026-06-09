@extends('layouts.app')
@section('title', 'Créer une Réservation')
@section('header-title', 'Créer une Réservation de Salle')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour à la liste
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <form action="{{ route('admin.reservations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Professeur <span class="text-rose-500">*</span></label>
                    <select name="professeur_id" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white">
                        <option value="">-- Sélectionner --</option>
                        @foreach($professeurs as $prof)
                            <option value="{{ $prof->id }}" {{ old('professeur_id') == $prof->id ? 'selected' : '' }}>{{ $prof->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Salle <span class="text-rose-500">*</span></label>
                    <select name="salle_id" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white">
                        <option value="">-- Sélectionner --</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>{{ $salle->nom }} ({{ $salle->capacite }} places)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date') }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Heure début <span class="text-rose-500">*</span></label>
                        <input type="time" name="heure_debut" value="{{ old('heure_debut', '08:30') }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Heure fin <span class="text-rose-500">*</span></label>
                        <input type="time" name="heure_fin" value="{{ old('heure_fin', '10:15') }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Motif <span class="text-rose-500">*</span></label>
                    <input type="text" name="motif" value="{{ old('motif') }}" required class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5" placeholder="Motif de la réservation">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Statut</label>
                    <select name="statut" class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white">
                        <option value="approuvee" {{ old('statut') === 'approuvee' ? 'selected' : '' }}>Approuvée</option>
                        <option value="en_attente" {{ old('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('admin.reservations.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
