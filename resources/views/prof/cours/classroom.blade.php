@extends('layouts.app')
@section('title', $module->nom . ' - Classroom')
@section('header-title', $module->nom)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('prof.cours.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Retour aux modules
            </a>
            
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-violet-100 text-violet-800 uppercase">
                    {{ $module->code }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white">
                    Filière : {{ $module->filiere->code }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Announcements feed -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Poster Annonce -->
                <div class="bg-white shadow rounded-2xl border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Publier une annonce</h3>
                    <form action="{{ route('prof.cours.annonce', $module->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="titre" placeholder="Sujet de l'annonce..." required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition">
                        </div>
                        <div>
                            <textarea name="contenu" rows="3" placeholder="Écrivez votre message aux étudiants..." required
                                class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow-sm transition">
                                Publier
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Feed -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider px-2">Flux d'annonces</h3>
                    
                    @forelse($annonces as $annonce)
                        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-9 w-9 rounded-full bg-violet-100 text-violet-700 flex items-center justify-center font-bold text-sm">
                                            PR
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-800">{{ $annonce->titre }}</h4>
                                            <p class="text-[10px] text-slate-400">Publié {{ $annonce->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('prof.cours.annonce.destroy', $annonce->id) }}" method="POST" onsubmit="return confirm('Supprimer cette annonce ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-4 text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $annonce->contenu }}</div>
                            </div>
                            
                            <!-- Comment section -->
                            <div class="bg-slate-50/50 border-t border-slate-100 p-6 space-y-4">
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Commentaires ({{ $annonce->commentaires->count() }})</h5>
                                
                                <div class="space-y-3">
                                    @forelse($annonce->commentaires as $com)
                                        <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex items-start space-x-3">
                                            <div class="h-7 w-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                                {{ substr($com->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="flex items-baseline space-x-2">
                                                    <span class="text-xs font-bold text-slate-800">{{ $com->user->name }}</span>
                                                    <span class="text-[9px] text-slate-400 capitalize">({{ $com->user->role }})</span>
                                                </div>
                                                <p class="text-xs text-slate-600 mt-1">{{ $com->contenu }}</p>
                                                <span class="text-[9px] text-slate-400 block mt-1">{{ $com->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">Aucun commentaire sur cette annonce.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white shadow rounded-2xl border border-slate-100 p-8 text-center text-slate-400 text-sm">
                            Aucune annonce n'a été publiée.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right 1 Col: File manager -->
            <div class="space-y-6">
                <!-- Upload Panel -->
                <div class="bg-white shadow rounded-2xl border border-slate-100 p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Téléverser un document</h3>
                    
                    <form action="{{ route('prof.cours.support', $module->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="titre_file" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Titre du fichier <span class="text-rose-500">*</span></label>
                            <input type="text" name="titre" id="titre_file" placeholder="Ex: TD1 - Algorithmes..." required
                                class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2 focus:outline-none focus:border-primary-400 transition">
                        </div>

                        <div>
                            <label for="type_file" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Type de document <span class="text-rose-500">*</span></label>
                            <select name="type" id="type_file" required
                                class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2 bg-white focus:outline-none focus:border-primary-400 transition">
                                <option value="Cours">Cours</option>
                                <option value="TD">TD</option>
                                <option value="TP">TP</option>
                                <option value="Examen">Examen</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="fichier" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Fichier (Max 10Mo) <span class="text-rose-500">*</span></label>
                            <input type="file" name="fichier" id="fichier" required
                                class="w-full text-xs border border-slate-200 rounded-xl px-3.5 py-2 bg-slate-50 file:mr-4 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition">
                        </div>

                        <button type="submit" class="w-full py-2 px-4 text-xs font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow-sm transition">
                            Envoyer le fichier
                        </button>
                    </form>
                </div>

                <!-- Supports list -->
                <div class="bg-white shadow rounded-2xl border border-slate-100 p-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Documents mis en ligne</h3>
                    
                    <div class="space-y-3.5">
                        @forelse($supports as $support)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/50 border border-slate-100 group hover:bg-white transition">
                                <div class="flex items-center space-x-2.5 min-w-0">
                                    <div class="h-8 w-8 rounded bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                        {{ strtoupper(substr($support->type, 0, 3)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 truncate" title="{{ $support->titre }}">{{ $support->titre }}</p>
                                        <p class="text-[9px] text-slate-400 truncate mt-0.5">{{ $support->fichier_nom }} ({{ $support->taille_formatee }})</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-1 flex-shrink-0">
                                    <!-- Download from public folder direct link -->
                                    <a href="{{ asset('storage/' . $support->fichier_path) }}" download class="p-1 rounded text-slate-400 hover:text-primary-600 hover:bg-primary-50 transition">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                    
                                    <!-- Delete support -->
                                    <form action="{{ route('prof.cours.support.destroy', $support->id) }}" method="POST" onsubmit="return confirm('Supprimer ce fichier ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-4">Aucun support téléversé.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
