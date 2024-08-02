<link rel="stylesheet" href="/js/fullcalendar/main.css">
<script src="/js/fullcalendar/main.js"></script>
<script src="/js/fullcalendar/locales/id.js"></script>
<style>
    /* Disable underline and pointer events for day numbers without events */
    .fc-daygrid-day.fc-day-other,
    .fc-daygrid-day:not(.fc-day-today) a,
    .fc-daygrid-day.fc-day,
    .fc-daygrid-day-number {
        pointer-events: none;
        text-decoration: none;
        color: inherit;
    }

    /* Ensure days with events are styled correctly */
    .fc-daygrid-day.fc-day.fc-day-today a {
        pointer-events: auto;
        text-decoration: underline;
        color: initial;
    }

    /* Remove underline from day names (Sun, Mon, etc.) */
    .fc-col-header-cell a {
        text-decoration: none !important;
        color: initial;
    }
</style>

<div id="calendar"></div>

{{-- <script>
    var endpoint = "/api/kalender-produk/";
    // var param = {!! json_encode($detail->id) !!};
    var withParam = endpoint + param;
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            selectable: true,
            navLinks: true,
            locale: 'id', // Set the locale to Indonesian
            eventSources: [{
                url: withParam,
                color: 'white',
                textColor: 'black'
            }],
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                hour12: false
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridDay'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                day: 'Hari'
            },
            eventContent: function(arg) {
                // Use FullCalendar's provided content injection
                let eventLink = document.createElement('a');
                eventLink.href = '#';
                eventLink.textContent = arg.event.title;

                let eventEl = document.createElement('div');
                eventEl.appendChild(eventLink);

                return {
                    domNodes: [eventEl]
                };
            },
            dateClick: function(info) {
                // Prevent navigation for dates without events
                if (!info.dateStr) {
                    info.jsEvent.preventDefault();
                }
            },
            datesSet: function(info) {
                // Remove links from dates without events
                let dates = document.querySelectorAll('.fc-daygrid-day');
                dates.forEach(function(date) {
                    if (!date.querySelector('.fc-event')) {
                        date.classList.add('no-event');
                    }
                });
            },
            allDayText: 'semua'
        });
        calendar.render();
    });
</script> --}}
