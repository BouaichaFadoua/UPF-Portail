@extends('layouts.app')
@section('title', 'Saisie des Notes')
@section('header-title', 'Saisie des Notes Académiques')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white shadow rounded-2xl border border-slate-100 p-6 mb-6">
            <h3 class="text-base font-bold text-slate-800">Mes Enseignements</h3>
            <p class="text-xs text-slate-500 mt-1">Sélectionnez un groupe d'étudiants sous l'un de vos modules pour saisir ou modifier leurs notes (CC1, CC2 et Examen).</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($modules as $module)
                <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden flex flex-col justify-between hover:border-primary-200 transition">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800 uppercase">
                                    {{ $module->code }}
                                </span>
                                <h4 class="text-lg font-bold text-slate-800 mt-2">{{ $module->nom }}</h4>
                                <p class="text-xs text-slate-500 mt-1">Filière : <span class="font-semibold">{{ $module->filiere->nom }}</span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-400">Coef. : {{ $module->coefficient }}</span>
                                <p class="text-xs font-bold text-slate-400">Vol. Hor. : {{ $module->volume_horaire }}h</p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Groupes Assignés</h5>
                            <div class="space-y-2.5">
                                @forelse($module->filiere->groupes as $groupe)
                                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-sm">
                                                {{ substr($groupe->nom, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-slate-800">{{ $groupe->nom }}</p>
                                                <p class="text-[10px] text-slate-400">Capacité : {{ $groupe->capacite }} étudiants</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('prof.notes.saisir', [$module->id, $groupe->id]) }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-500 shadow-sm transition">
                                            Saisir notes
                                            <svg class="ml-1 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endforeach
                                    @if($module->filiere->groupes->isEmpty())
                                        <p class="text-xs text-slate-400 italic">Aucun groupe associé à cette filière.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white shadow rounded-2xl border border-slate-100 p-12 text-center text-slate-400">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-sm font-semibold">Aucun module assigné</p>
                    <p class="text-xs text-slate-400 mt-1">Vous n'avez pas encore été assigné à des modules d'enseignement.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
