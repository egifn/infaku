<div id="calendar"></div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar @6.1.15/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/api/bookings',
        displayEventTime: true
    });
    calendar.render();
});
</script>
@endpush