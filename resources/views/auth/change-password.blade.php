@extends('layouts.app')
@section('title', 'Changer le mot de passe')
@section('header-title', 'Modifier mon mot de passe')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center text-sm text-slate-500 dark:text-slate-400">
            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <a href="javascript:history.back()" class="hover:text-primary-600 dark:hover:text-primary-400 transition">Retour</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700 dark:text-white font-semibold">Sécurité du compte</span>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-slate-800 shadow-xl rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 bg-gradient-to-r from-primary-600 to-primary-700 flex items-center space-x-4">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Modifier le mot de passe</h2>
                    <p class="text-xs text-primary-100 mt-0.5">{{ auth()->user()->name }} · <span class="capitalize">{{ auth()->user()->role }}</span></p>
                </div>
            </div>

            <div class="p-6">
                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm">
                        <div class="flex items-center mb-2">
                            <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span class="font-semibold">Erreur(s) :</span>
                        </div>
                        <ul class="list-disc pl-6 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5" id="password-form" x-data="{
                    newPass: '',
                    show1: false,
                    show2: false,
                    show3: false,
                    get strength() {
                        let s = 0;
                        if (this.newPass.length >= 8) s++;
                        if (/[A-Z]/.test(this.newPass)) s++;
                        if (/[0-9]/.test(this.newPass)) s++;
                        if (/[^A-Za-z0-9]/.test(this.newPass)) s++;
                        return s;
                    },
                    get label() {
                        return ['', 'Faible', 'Moyen', 'Fort', 'Très fort'][this.strength];
                    }
                }">
                    @csrf
                    @method('PUT')

                    <!-- Mot de passe actuel -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Mot de passe actuel <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="show1 ? 'text' : 'password'"
                                name="current_password"
                                id="current_password"
                                autocomplete="current-password"
                                required
                                class="w-full pr-10 text-sm border {{ $errors->has('current_password') ? 'border-rose-400 bg-rose-50 dark:bg-rose-950/20 dark:border-rose-700' : 'border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white' }} rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition"
                                placeholder="Votre mot de passe actuel"
                            >
                            <button type="button" @click="show1 = !show1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <svg x-show="!show1" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show1" style="display:none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Nouveau mot de passe <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="show2 ? 'text' : 'password'"
                                name="password"
                                id="password"
                                autocomplete="new-password"
                                required
                                minlength="8"
                                x-model="newPass"
                                class="w-full pr-10 text-sm border {{ $errors->has('password') ? 'border-rose-400 bg-rose-50 dark:bg-rose-950/20 dark:border-rose-700' : 'border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white' }} rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition"
                                placeholder="Au moins 8 caractères"
                            >
                            <button type="button" @click="show2 = !show2" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <svg x-show="!show2" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show2" style="display:none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">{{ $message }}</p>
                        @enderror

                        <!-- Password strength bar -->
                        <div class="mt-2">
                            <div class="flex space-x-1 mt-1">
                                <div class="h-1 flex-1 rounded-full transition-all duration-300" :class="strength >= 1 ? 'bg-rose-400' : 'bg-slate-200 dark:bg-slate-700'"></div>
                                <div class="h-1 flex-1 rounded-full transition-all duration-300" :class="strength >= 2 ? 'bg-amber-400' : 'bg-slate-200 dark:bg-slate-700'"></div>
                                <div class="h-1 flex-1 rounded-full transition-all duration-300" :class="strength >= 3 ? 'bg-emerald-400' : 'bg-slate-200 dark:bg-slate-700'"></div>
                                <div class="h-1 flex-1 rounded-full transition-all duration-300" :class="strength >= 4 ? 'bg-emerald-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                            </div>
                            <p class="text-xs mt-1 text-slate-400 dark:text-slate-500" x-text="label ? 'Force : ' + label : 'Entrez un mot de passe'"></p>
                        </div>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Confirmer le nouveau mot de passe <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                :type="show3 ? 'text' : 'password'"
                                name="password_confirmation"
                                id="password_confirmation"
                                autocomplete="new-password"
                                required
                                class="w-full pr-10 text-sm border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-white rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition"
                                placeholder="Répétez le nouveau mot de passe"
                            >
                            <button type="button" @click="show3 = !show3" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                <svg x-show="!show3" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show3" style="display:none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Security tips -->
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4 text-xs text-slate-500 dark:text-slate-400 space-y-1.5">
                        <p class="font-semibold text-slate-600 dark:text-slate-300 flex items-center">
                            <svg class="h-4 w-4 mr-1.5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Conseils de sécurité
                        </p>
                        <ul class="space-y-1 pl-5 list-disc">
                            <li>Au moins <strong>8 caractères</strong></li>
                            <li>Mélangez <strong>lettres, chiffres et symboles</strong></li>
                            <li>Évitez votre nom ou date de naissance</li>
                            <li>N'utilisez pas ce mot de passe sur d'autres sites</li>
                        </ul>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end space-x-3">
                        <a href="javascript:history.back()" class="px-5 py-2.5 text-sm font-semibold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                            Annuler
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow-md shadow-primary-600/25 transition flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer le nouveau mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
