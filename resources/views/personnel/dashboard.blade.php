@extends('layouts.app')
@section('title', 'Espace Personnel')
@section('header-title', 'Espace Personnel Administratif')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 bg-slate-50 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800">Bienvenue, {{ $user->name }}</h3>
                <p class="text-xs text-slate-500 mt-1">Espace réservé au personnel administratif de l'université.</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Poste</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $user->personnel?->poste ?? '—' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Service</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $user->personnel?->service ?? '—' }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Email</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $user->email }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Téléphone</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $user->personnel?->telephone ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
