@extends('layouts.app')
@section('title', __('Recherche globale (Étudiants, Modules...)'))
@section('header-title', __('Recherche globale (Étudiants, Modules...)'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Résultats pour "{{ $q }}"</h2>

        <div class="space-y-6">
            <!-- Utilisateurs -->
            <div class="bg-white dark:bg-slate-800 shadow rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ __('Utilisateurs') }} ({{ $users->count() }})</h3>
                </div>
                <div class="p-0">
                    @if($users->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($users as $user)
                            <li class="px-6 py-4 flex items-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/50 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }} - <span class="capitalize">{{ $user->role }}</span></p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-6 text-center text-sm text-slate-500 dark:text-slate-400">Aucun utilisateur trouvé.</div>
                    @endif
                </div>
            </div>

            <!-- Modules -->
            <div class="bg-white dark:bg-slate-800 shadow rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">{{ __('Modules') }} ({{ $modules->count() }})</h3>
                </div>
                <div class="p-0">
                    @if($modules->count() > 0)
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($modules as $module)
                            <li class="px-6 py-4 flex items-center hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $module->nom }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Code: {{ $module->code }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-6 text-center text-sm text-slate-500 dark:text-slate-400">Aucun module trouvé.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
