@extends('layouts.app')

@section('title', 'Connexion — UPF Portal')

@section('content')
<div class="min-h-screen flex">

    {{-- ===== LEFT PANEL : UPF Branding ===== --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative flex-col items-center justify-center overflow-hidden"
         style="background: linear-gradient(135deg, #0B3D91 0%, #062A6E 60%, #041C4A 100%);">

        {{-- Decorative circles --}}
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full opacity-10"
             style="background: radial-gradient(circle, #C9127A, transparent);"></div>
        <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full opacity-10"
             style="background: radial-gradient(circle, #4A90D9, transparent);"></div>
        <div class="absolute top-1/3 -right-10 w-56 h-56 rounded-full opacity-5"
             style="background: radial-gradient(circle, #C9127A, transparent);"></div>

        {{-- Logo UPF --}}
        <div class="relative z-10 flex flex-col items-center text-center px-12">
            {{-- SVG Logo UPF --}}
            <div class="mb-8">
                <svg width="140" height="140" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Blue main square -->
                    <rect x="0" y="0" width="112" height="112" fill="#0B3D91"/>
                    <!-- Pink/magenta accent square -->
                    <rect x="84" y="84" width="56" height="56" fill="#C9127A"/>
                    <!-- White overlap area (where the two squares meet) -->
                    <rect x="84" y="84" width="28" height="28" fill="#0B3D91"/>
                    <!-- UPF Text -->
                    <text x="12" y="82" font-family="Arial Black, sans-serif" font-size="62" font-weight="900" fill="white" letter-spacing="-2">UPF</text>
                </svg>
            </div>

            <h1 class="text-3xl xl:text-4xl font-black text-white tracking-wide mb-3">
                UPF <span style="color:#C9127A;">Portal</span>
            </h1>
            <p class="text-blue-200 text-base xl:text-lg font-medium mb-2">
                Université Privée de Fès
            </p>
            <div class="mt-6 w-16 h-1 rounded-full" style="background:#C9127A;"></div>
            <p class="mt-8 text-blue-300 text-sm leading-relaxed max-w-sm">
                Votre espace académique numérique — Notes, Emploi du temps, Absences et bien plus, accessibles en un clic.
            </p>

            {{-- Feature pills --}}
            <div class="mt-10 flex flex-wrap gap-3 justify-center">
                <span class="flex items-center gap-2 text-xs text-white bg-white/10 border border-white/20 rounded-full px-4 py-1.5 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Notes & Résultats
                </span>
                <span class="flex items-center gap-2 text-xs text-white bg-white/10 border border-white/20 rounded-full px-4 py-1.5 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Emploi du temps
                </span>
                <span class="flex items-center gap-2 text-xs text-white bg-white/10 border border-white/20 rounded-full px-4 py-1.5 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.834-1.964-.834-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    Absences
                </span>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="absolute bottom-6 text-center text-xs text-blue-400">
            © {{ date('Y') }} Université Privée de Fès — Tous droits réservés
        </div>
    </div>

    {{-- ===== RIGHT PANEL : Login Form ===== --}}
    <div class="w-full lg:w-1/2 xl:w-2/5 flex items-center justify-center bg-slate-50 px-6 py-12">
        <div class="w-full max-w-md">

            {{-- Mobile logo (visible only on small screens) --}}
            <div class="flex justify-center mb-8 lg:hidden">
                <svg width="80" height="80" viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" y="0" width="112" height="112" fill="#0B3D91"/>
                    <rect x="84" y="84" width="56" height="56" fill="#C9127A"/>
                    <rect x="84" y="84" width="28" height="28" fill="#0B3D91"/>
                    <text x="12" y="82" font-family="Arial Black, sans-serif" font-size="62" font-weight="900" fill="white" letter-spacing="-2">UPF</text>
                </svg>
            </div>

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8 xl:p-10">

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-800">Bon retour ! 👋</h2>
                    <p class="text-slate-500 text-sm mt-1">Connectez-vous à votre espace académique</p>
                </div>

                {{-- Error alert --}}
                @if ($errors->any())
                    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Adresse email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4.5 h-4.5 text-slate-400" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                value="{{ old('email') }}"
                                placeholder="votreemail@gmail.com"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:border-transparent transition-all"
                                style="focus:ring-color: #0B3D91;"
                                onfocus="this.style.boxShadow='0 0 0 3px rgba(11,61,145,0.2)'; this.style.borderColor='#0B3D91';"
                                onblur="this.style.boxShadow=''; this.style.borderColor='';"
                            >
                        </div>
                    </div>

                    {{-- Password --}}
                    <div x-data="{ showPass: false }">
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg style="width:18px;height:18px;" class="text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input
                                id="password"
                                name="password"
                                :type="showPass ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-12 py-3 rounded-xl border @error('password') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror text-slate-800 placeholder-slate-400 text-sm focus:outline-none transition-all"
                                onfocus="this.style.boxShadow='0 0 0 3px rgba(11,61,145,0.2)'; this.style.borderColor='#0B3D91';"
                                onblur="this.style.boxShadow=''; this.style.borderColor='';"
                            >
                            <button type="button" @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!showPass" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPass" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember me --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                style="accent-color: #0B3D91;">
                            <span class="text-sm text-slate-600">Se souvenir de moi</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-3 px-6 rounded-xl font-semibold text-sm text-white shadow-lg transition-all duration-200 mt-2"
                        style="background: linear-gradient(135deg, #0B3D91, #1a5cb8);"
                        onmouseover="this.style.background='linear-gradient(135deg, #062A6E, #0B3D91)'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 8px 25px rgba(11,61,145,0.4)';"
                        onmouseout="this.style.background='linear-gradient(135deg, #0B3D91, #1a5cb8)'; this.style.transform=''; this.style.boxShadow='';">
                        <span class="flex items-center justify-center gap-2">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Se connecter
                        </span>
                    </button>

                </form>

            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-slate-400 mt-6">
                Université Privée de Fès &mdash; Système de gestion académique
            </p>
        </div>
    </div>

</div>
@endsection
