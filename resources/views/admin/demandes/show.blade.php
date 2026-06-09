@extends('layouts.app')
@section('title', 'Demande #' . $demande->id)
@section('header-title', 'Traiter la Demande')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-800">{{ \App\Models\Demande::$typeLabels[$demande->type] ?? $demande->type }}</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Reçue le {{ $demande->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                    @if($demande->statut === 'validee') bg-emerald-50 text-emerald-800
                    @elseif($demande->statut === 'en_attente') bg-amber-50 text-amber-800
                    @else bg-rose-50 text-rose-800 @endif">
                    {{ $demande->statut === 'validee' ? 'Validée' : ($demande->statut === 'en_attente' ? 'En attente' : 'Refusée') }}
                </span>
            </div>

            <!-- Demandeur Info -->
            <div class="px-6 py-5 border-b border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Informations du demandeur</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-400 text-xs">Nom</span>
                        <p class="font-semibold text-slate-800">{{ $demande->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-xs">Email</span>
                        <p class="font-semibold text-slate-800">{{ $demande->user->email }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400 text-xs">Rôle</span>
                        <p class="font-semibold text-slate-800 capitalize">{{ $demande->user->role }}</p>
                    </div>
                    @if($demande->user->isEtudiant() && $demande->user->etudiant)
                        <div>
                            <span class="text-slate-400 text-xs">Matricule</span>
                            <p class="font-semibold text-slate-800">{{ $demande->user->etudiant->matricule }}</p>
                        </div>
                    @endif
                    @if($demande->type === 'ordre_mission' && $demande->destination)
                        <div>
                            <span class="text-slate-400 text-xs">Destination</span>
                            <p class="font-semibold text-slate-800">{{ $demande->destination }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 text-xs">Dates</span>
                            <p class="font-semibold text-slate-800">{{ $demande->date_depart?->format('d/m/Y') }} → {{ $demande->date_retour?->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-slate-400 text-xs">Objet de la mission</span>
                            <p class="font-semibold text-slate-800">{{ $demande->motif_mission }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($demande->statut === 'en_attente')
                <!-- Action Area -->
                <div class="px-6 py-5 space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Traiter la demande</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Valider -->
                        <form action="{{ route('admin.demandes.valider', $demande->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-500 transition shadow">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Valider & Générer PDF
                            </button>
                        </form>
                        <!-- Refuser -->
                        <div x-data="{ open: false }">
                            <button @click="open = true" type="button" class="w-full flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-500 transition shadow">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Refuser
                            </button>
                            <div x-show="open" x-cloak class="mt-3">
                                <form action="{{ route('admin.demandes.refuser', $demande->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <textarea name="motif_refus" required rows="3" placeholder="Motif du refus…" class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-rose-400"></textarea>
                                    <button type="submit" class="w-full px-4 py-2 text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-500 transition">Confirmer le refus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($demande->statut === 'validee' && $demande->document)
                <div class="px-6 py-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Document généré avec succès</p>
                        <p class="text-xs text-slate-400">{{ $demande->document->fichier_nom }}</p>
                    </div>
                    <a href="{{ route('admin.demandes.telecharger', $demande->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-500 transition shadow">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Télécharger le PDF
                    </a>
                </div>
            @elseif($demande->statut === 'refusee')
                <div class="px-6 py-5">
                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-sm text-rose-700">
                        <strong>Motif du refus :</strong> {{ $demande->motif_refus }}
                    </div>
                </div>
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.demandes.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
