@extends('layouts.app')
@section('title', 'Mon Emploi du Temps')
@section('header-title', 'Mon Emploi du Temps')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
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
      events: '{{ route("prof.edt.index") }}',
      eventContent: function(arg) {
        return {
            html: `
                <div class="p-1 h-full flex flex-col justify-between overflow-hidden">
                    <div class="font-bold text-xs leading-tight mb-1">${arg.event.title}</div>
                    <div class="text-[10px] space-y-0.5 opacity-90">
                        <div>Gr: ${arg.event.extendedProps.groupe}</div>
                    </div>
                </div>
            `
        };
      }
    });
    calendar.render();
});
</script>
@endsection
