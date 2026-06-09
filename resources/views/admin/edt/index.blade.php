@extends('layouts.app')
@section('title', 'Gestion de l\'Emploi du Temps')
@section('header-title', 'Gestion de l\'Emploi du Temps')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Toolbar / Filters -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <form action="{{ route('admin.edt.index') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                <select name="groupe_id" onchange="this.form.submit()" class="text-sm border border-slate-200 rounded-xl px-3 py-2 bg-white focus:outline-none focus:border-primary-400">
                    <option value="">Tous les groupes</option>
                    @foreach($groupes as $g)
                        <option value="{{ $g->id }}" {{ $groupe_id == $g->id ? 'selected' : '' }}>{{ $g->nom }}</option>
                    @endforeach
                </select>
                @if($groupe_id)
                    <a href="{{ route('admin.edt.index') }}" class="text-sm text-slate-400 hover:text-slate-600">Tous</a>
                @endif
            </form>

            <a href="{{ route('admin.edt.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-500 shadow transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajouter une séance
            </a>
        </div>

        <!-- Calendar Container -->
        <div class="bg-white shadow rounded-2xl border border-slate-100 p-6">
            <div id="calendar"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'timeGridWeek',
      locale: 'fr',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'timeGridWeek,timeGridDay'
      },
      slotMinTime: '08:00:00',
      slotMaxTime: '19:00:00',
      allDaySlot: false,
      hiddenDays: [0], // Hide Sunday
      events: '{{ route("admin.edt.index") }}' + (window.location.search || ''),
      eventContent: function(arg) {
        return {
            html: `
                <div class="p-1 h-full flex flex-col justify-between overflow-hidden">
                    <div class="font-bold text-xs leading-tight mb-1">${arg.event.title}</div>
                    <div class="text-[10px] space-y-0.5 opacity-90">
                        <div>Prof: ${arg.event.extendedProps.professeur}</div>
                        <div>Gr: ${arg.event.extendedProps.groupe}</div>
                    </div>
                </div>
            `
        };
      },
      eventClick: function(info) {
          window.location.href = '/admin/edt/' + info.event.id + '/edit';
      }
    });
    calendar.render();
});
</script>
@endsection
