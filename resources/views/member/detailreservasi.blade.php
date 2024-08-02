@extends('member.main')
@section('container')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('order.show') }}"><i class="fas fa-arrow-left"></i></a>
                    <b>Detail Reservasi</b>
                    @if ($paymentStatus == 1)
                        <span class="badge bg-warning">Sedang Ditinjau</span>
                    @elseif ($paymentStatus == 2)
                        <span class="badge bg-info">Belum Bayar</span>
                    @elseif ($paymentStatus == 3)
                        <span class="badge bg-success">Sudah Bayar</span>
                    @elseif ($paymentStatus == 4)
                        <span class="badge bg-secondary">Selesai</span>
                    @elseif ($paymentStatus == 5)
                        <span class="badge bg-danger">Pembayaran Gagal</span>
                    @endif
                </div>
            </div>
            <div class="card-body" style="overflow: auto">
                @if ($paymentStatus == 3)
                    <div class="alert alert-success">Silakan melakukan pengambilan produk pada tanggal yang tertera</div>
                @endif
                <p><span id="countdown">05:00:00</span></p>
                <b>Tanggal Pengambilan :</b> {{ date('d M Y H:i', strtotime($detail->first()->starts)) }} <br>
                <b>No. Invoice :</b> {{ $detail->first()->payment->no_invoice }}
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Pengembalian</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $item)
                            <tr class="{{ $item->status == 3 ? 'table-danger' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a class="link-dark"
                                        href="{{ route('home.detail', ['id' => $item->product->id]) }}">{{ $item->product->nama_produk }}</a>
                                    <span class="badge bg-warning">{{ $item->product->category->nama_kategori }}</span>
                                    <span class="badge bg-secondary">{{ $item->durasi }} Hari</span>
                                    @if ($item->status === 3)
                                        <span class="badge bg-danger" id="ditolak">Ditolak</span>
                                    @elseif ($item->status === 2)
                                        <span class="badge bg-success">ACC</span>
                                    @endif
                                </td>
                                <td>{{ date('d M Y H:i', strtotime($item->ends)) }}</td>
                                <td style="text-align: right"><b>{{ formatRupiah($item->harga) }}</b></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td style="text-align: right"><b>Total</b></td>
                            <td style="text-align: right"><b>{{ formatRupiah($total) }}</b></td>
                        </tr>
                    </tbody>
                </table>
                @if ($paymentStatus == 1)
                    <form action="{{ route('cancel', ['id' => $detail->first()->payment->id]) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit"
                            onclick="javascript: return confirm('Anda yakin akan membatalkan reservasi?');"
                            class="btn btn-danger" style="float: right">Cancel Reservasi</button>
                    </form>
                @endif
                @if ($paymentStatus == 2)
                    <div class="alert {{ $detail->first()->payment->bukti == null ? 'alert-primary' : 'alert-success' }}">
                        @if ($detail->first()->payment->bukti == null)
                            Reservasi anda telah disetujui, silakan bayar sesuai dengan total yang tertera dengan cara
                            transfer ke
                            <h4><b>BNI xxxxxxxxxx</b></h4>
                            <h6><b>a.n Dendra Kurnianto</b></h6>
                            lalu upload bukti bayar dengan menekan tombol dibawah.
                        @else
                            Bukti pembayaran telah di upload, silakan tunggu konfirmasi dari Admin
                        @endif
                        <!-- Hanya aktifkan tombol jika status bukan "Ditolak" atau "Pembayaran Gagal" -->
                        @if ($paymentStatus != 3 && $paymentStatus != 5)
                            <form action="">
                                <button type="button" class="btn btn-success mt-2" data-bs-toggle="modal"
                                    data-bs-target="#bayarModal">Bukti Pembayaran</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary mt-2" disabled>Bukti Pembayaran</button>
                        @endif
                    </div>
                @endif
                <!-- Hanya tampilkan bukti pembayaran jika status tidak "Ditolak" -->
                @if ($paymentStatus != 3 && $paymentStatus != 5)
                    @if ($paymentStatus == 3 || $paymentStatus == 4)
                        <h5>Bukti Pembayaran :</h5>
                        <img src="{{ url('') }}/images/evidence/{{ $bukti }}" alt="" width="500px">
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Bukti Bayar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('bayar', ['id' => $paymentId]) }}" method="POST" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <input type="file" name="bukti" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>
                    <!-- Hanya tampilkan bukti bayar jika status tidak "Ditolak" -->
                    @if ($paymentStatus != 3 && $paymentStatus != 5)
                        <h5 class="mt-2">Bukti Bayar</h5>
                        <img src="{{ url('') }}/images/evidence/{{ $bukti }}" alt="" width="500px">
                    @endif
                </div>
            </div>
        </div>
    @endsection
   @push('custom-script')
<script>
    $(document).ready(function() {
        let timer;
        let countdown; // Timer value

        // Pass the payment status and payment ID from PHP to JavaScript
        let paymentStatus = {{ $paymentStatus }};
        let paymentId = {{ $paymentId }};

        // Calculate the remaining time
        function calculateRemainingTime() {
            const savedTime = localStorage.getItem('countdownEndTime');
            if (savedTime) {
                const currentTime = Math.floor(Date.now() / 1000); // Current time in seconds
                countdown = savedTime - currentTime;

                if (countdown <= 0) {
                    countdown = 0;
                    sendPostRequest(); // Trigger POST request if time is up
                }
            } else {
                countdown = 5 * 60 * 60; // 5 hours in seconds
                localStorage.setItem('countdownEndTime', Math.floor(Date.now() / 1000) + countdown);
            }
        }

        function startCountdown() {
            // If payment status is 1, 3, or 4, stop the countdown
            if (paymentStatus == 1 || paymentStatus == 3 || paymentStatus == 4) {
                $('#countdown').text('00:00:00');
                return; // Stop the countdown
            }

            calculateRemainingTime(); // Initialize countdown based on saved time

            timer = setInterval(function() {
                countdown--;
                $('#countdown').text(formatTime(countdown));
                localStorage.setItem('countdownEndTime', Math.floor(Date.now() / 1000) + countdown); // Update end time in localStorage

                if (countdown <= 0) {
                    clearInterval(timer);
                    localStorage.removeItem('countdownEndTime'); // Clear localStorage
                    sendPostRequest(); // Send a POST request
                }
            }, 1000);
        }

        function formatTime(seconds) {
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds % 3600) / 60);
            let secs = seconds % 60;

            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function sendPostRequest() {
            const url = '/reservasi/ditolakbayar'; // Your POST URL
            const data = {
                id: paymentId
            }; // Echo the PHP variable into JavaScript

            $.ajax({
                url: url,
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token for Laravel
                },
                success: function(response) {
                    console.log('Success:', response);
                    localStorage.removeItem('countdownEndTime'); // Clear localStorage
                    window.location.href = '/reservasi'; // Redirect after successful POST
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim data.');
                }
            });
        }

        startCountdown(); // Start the countdown
    });
</script>
@endpush

