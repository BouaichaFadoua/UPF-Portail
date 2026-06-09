@extends('layouts.app')
@section('title', 'Remplir le Cahier de Textes')
@section('header-title', 'Rédiger un Compte Rendu')

@section('content')
<div class="py-6" x-data="{ 
    entryType: 'seance',
    seances: @js($seances->mapWithKeys(fn($s) => [$s->id => [
        'module_id' => $s->module_id,
        'date' => \Carbon\Carbon::parse($s->date)->format('Y-m-d'),
        'heure_debut' => substr($s->heure_debut, 0, 5),
        'heure_fin' => substr($s->heure_fin, 0, 5),
        'type' => $s->type
    ]])),
    selectedSeanceId: '',
    onSeanceChange() {
        if (this.selectedSeanceId && this.seances[this.selectedSeanceId]) {
            const data = this.seances[this.selectedSeanceId];
            document.getElementById('date').value = data.date;
            document.getElementById('heure_debut').value = data.heure_debut;
            document.getElementById('heure_fin').value = data.heure_fin;
            document.getElementById('nature').value = data.type;
        }
    }
}">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('prof.cahier-textes.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour au cahier de textes
            </a>
        </div>

        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Ajouter un compte rendu</h3>
                <p class="text-xs text-slate-500 mt-1">Sélectionnez une séance de votre emploi du temps ou saisissez une séance manuellement.</p>
            </div>

            <!-- Tab selector -->
            <div class="flex border-b border-slate-100 bg-slate-50/50">
                <button type="button" @click="entryType = 'seance'; selectedSeanceId = ''" 
                    :class="entryType === 'seance' ? 'border-primary-500 text-primary-600 font-bold bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100/30'"
                    class="flex-1 py-3 text-center border-b-2 text-xs transition">
                    Depuis une séance planifiée
                </button>
                <button type="button" @click="entryType = 'libre'; selectedSeanceId = ''" 
                    :class="entryType === 'libre' ? 'border-primary-500 text-primary-600 font-bold bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:bg-slate-100/30'"
                    class="flex-1 py-3 text-center border-b-2 text-xs transition">
                    Saisie manuelle libre
                </button>
            </div>

            <form action="{{ route('prof.cahier-textes.store') }}" method="POST" class="p-6 space-y-6">
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

                <!-- Case 1: Planified session -->
                <div x-show="entryType === 'seance'" x-collapse>
                    <label for="seance_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Choisir la séance concernée <span class="text-rose-500">*</span></label>
                    <select name="seance_id" id="seance_id" x-model="selectedSeanceId" @change="onSeanceChange()" x-bind:required="entryType === 'seance'"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        <option value="">-- Sélectionner une séance récente --</option>
                        @foreach($seances as $s)
                            <option value="{{ $s->id }}">
                                {{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }} ({{ substr($s->heure_debut, 0, 5) }}) · {{ $s->module->nom }} · {{ $s->groupe->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Case 2: Free entry (Select Module manually) -->
                <div x-show="entryType === 'libre'" x-collapse style="display: none;">
                    <label for="module_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Choisir le module <span class="text-rose-500">*</span></label>
                    <select name="module_id" id="module_id" x-bind:required="entryType === 'libre'"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 bg-white focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        <option value="">-- Sélectionner un module --</option>
                        @foreach($modules as $m)
                            <option value="{{ $m->id }}" {{ old('module_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nom }} ({{ $m->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date & Nature (Visible and editable in both modes, but pre-filled in seance mode) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date" class="block text-sm font-semibold text-slate-700 mb-1.5">Date de la séance <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                            :readonly="entryType === 'seance'"
                            :class="entryType === 'seance' ? 'bg-slate-50 text-slate-500 pointer-events-none' : 'bg-white focus:border-primary-400 focus:ring-primary-100'"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 transition">
                    </div>

                    <div>
                        <label for="nature" class="block text-sm font-semibold text-slate-700 mb-1.5">Type de séance <span class="text-rose-500">*</span></label>
                        <select name="nature" id="nature" required
                            :tabindex="entryType === 'seance' ? '-1' : '0'"
                            :class="entryType === 'seance' ? 'bg-slate-50 text-slate-500 pointer-events-none' : 'bg-white focus:border-primary-400 focus:ring-primary-100'"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 transition">
                            <option value="Cours">Cours</option>
                            <option value="TD">TD</option>
                            <option value="TP">TP</option>
                        </select>
                    </div>
                </div>

                <!-- Hours -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="heure_debut" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure de début <span class="text-rose-500">*</span></label>
                        <input type="time" name="heure_debut" id="heure_debut" value="{{ old('heure_debut', '08:30') }}" required
                            :readonly="entryType === 'seance'"
                            :class="entryType === 'seance' ? 'bg-slate-50 text-slate-500 pointer-events-none' : 'bg-white focus:border-primary-400 focus:ring-primary-100'"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 transition">
                    </div>

                    <div>
                        <label for="heure_fin" class="block text-sm font-semibold text-slate-700 mb-1.5">Heure de fin <span class="text-rose-500">*</span></label>
                        <input type="time" name="heure_fin" id="heure_fin" value="{{ old('heure_fin', '10:15') }}" required
                            :readonly="entryType === 'seance'"
                            :class="entryType === 'seance' ? 'bg-slate-50 text-slate-500 pointer-events-none' : 'bg-white focus:border-primary-400 focus:ring-primary-100'"
                            class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 transition">
                    </div>
                </div>

                <!-- Objective -->
                <div>
                    <label for="objectif" class="block text-sm font-semibold text-slate-700 mb-1.5">Objectif pédagogique / Titre du chapitre <span class="text-rose-500">*</span></label>
                    <input type="text" name="objectif" id="objectif" value="{{ old('objectif') }}" required
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Ex: Introduction aux architectures MVC en PHP">
                </div>

                <!-- Content -->
                <div>
                    <label for="contenu" class="block text-sm font-semibold text-slate-700 mb-1.5">Contenu détaillé & avancement (Optionnel)</label>
                    <textarea name="contenu" id="contenu" rows="4"
                        class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition" placeholder="Sujets traités, exercices réalisés, devoirs donnés..."></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('prof.cahier-textes.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">Annuler</a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Enregistrer l'entrée
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
