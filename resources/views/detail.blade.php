<html>

<head>
    <title>Detail - {{ $detail->nama_produk }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="stylesheet" href="/js/fullcalendar/main.css">
    <script src="/js/fullcalendar/main.js"></script>
    <script src="/js/fullcalendar/locales/id.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
</head>

<body>
    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row justify-content-center mt-4">
            <div class="col-md-12 col-lg-4">
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        @if (Auth::guest())
                            <a href="{{ route('home') }}" class="link-dark" style="text-decoration: none;"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        @elseif (Auth::user()->role == 0)
                            <a href="{{ route('member.index') }}" class="link-dark" style="text-decoration: none;"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        @elseif (Auth::user()->role != 0)
                            <a href="{{ url()->previous() }}" class="link-dark" style="text-decoration: none;"><i
                                    class="fas fa-arrow-left"></i> Kembali</a>
                        @endif
                    </div>
                    <img class="card-img-top" src="{{ url('') }}/images/{{ $detail->gambar }}" alt="">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> {{ formatRupiah($detail->harga1h) }}<span
                                class="badge bg-light text-dark" style="float: right;">/ 1hari</span></li>
                        <li class="list-group-item"> {{ formatRupiah($detail->harga3h) }}<span
                                class="badge bg-light text-dark" style="float: right;">/ 3hari</span></li>
                        <li class="list-group-item"> {{ formatRupiah($detail->harga7h) }}<span
                                class="badge bg-light text-dark" style="float: right;">/ 7hari</span></li>

                    </ul>
                </div>
            </div>
            <div class="col-md-12 col-lg-8 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h1><b>{{ $detail->nama_produk }}</b></h1>
                        <p class="text-muted">{{ $detail->deskripsi }}</p>
                        @if (Auth::check() && Auth::user()->role == 0)
                            <form
                                action="{{ route('cart.store', ['id' => $detail->id, 'userId' => Auth::user()->id]) }}"
                                method="POST">
                                @csrf
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-success mx-2" name="btn" value="1"><i
                                            class="fas fa-shopping-cart"></i> {{ formatRupiah($detail->harga1h) }}
                                        <b>1hari</b></button>
                                    <button type="submit" class="btn btn-success mx-2" name="btn" value="3"><i
                                            class="fas fa-shopping-cart"></i> {{ formatRupiah($detail->harga3h) }}
                                        <b>3hari</b></button>
                                    <button type="submit" class="btn btn-success mx-2" name="btn" value="7"><i
                                            class="fas fa-shopping-cart"></i> {{ formatRupiah($detail->harga7h) }}
                                        <b>7hari</b></button>
                                </div>
                            </form>
                            <p class="text-muted">Anda sedang login sebagai <b>{{ Auth::user()->name }}</b></p>
                        @endif
                        <hr>
                        <h6><i>Daftar Pinjaman Mendatang</i></h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal Keluar</th>
                                    <th>Tanggal Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order as $item)
                                    <tr>
                                        @if ($item->payment->status == 3)
                                            <td>{{ date('d M Y H:i', strtotime($item->starts)) }} <span
                                                    class="badge bg-secondary">{{ $item->durasi }} Hari</span></td>
                                            <td>{{ date('d M Y H:i', strtotime($item->ends)) }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var endpoint = "/api/kalender-produk/";
        var param = {!! $detail->id !!};
        var withParam = endpoint + param;
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                selectable: true,
                navLinks: true,
                eventSources: [{
                    url: withParam,
                    color: 'yellow',
                    textColor: 'black'
                }],
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: false
                },
                headerToolbar: {
                    left: 'dayGridMonth,timeGridDay',
                    center: 'title',
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
    </script>
</body>

</html>
