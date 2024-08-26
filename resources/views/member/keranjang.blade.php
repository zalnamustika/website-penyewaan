@extends('member.main')
@section('container')
    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
        <div class="col-md-6 col-sm-12">
            <div class="card shadow" id="keranjang">
                <div class="card-header">
                    <b>Keranjang</b> <span class="badge bg-secondary">{{ Auth::user()->cart->count() }}</span>
                </div>
                <div class="card-body">
                    <div>
                        <div class="list-group">
                            @forelse ($cartItems as $item)
                                <div class="list-group-item list-group-item-action" aria-current="true">
                                    Terhapus pada : <span class="countdown"
                                        data-item-id="{{ $item->id }}">2:00:00</span>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $item->product->nama_produk }}</h6>
                                        <p class="justify-content-between">{{ formatRupiah($item->harga) }}<br>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <p class="mb-1">{{ $item->durasi }} Hari</p>
                                        <form action="{{ route('cart.destroy', ['id' => $item->id]) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-danger" type="submit"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                    <p class="justify-content-between">Jumlah : {{ $item->jumlah }}x</p>
                                </div>
                            @empty
                                <p class="text-center">Kamu belum menambahkan apapun kedalam keranjang</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex w-100 justify-content-between mb-2">
                        <b><span>Total</span></b>
                        <b><span>{{ formatRupiah($total) }}</span></b>
                    </div>
                    <form action="{{ route('order.create') }}" method="POST">
                        @csrf
                        <b><small>Tanggal Pengambilan</small></b>
                        <input type="date" name="start_date" class="form-control mb-2" required>
                        <b><small>Jam Pengambilan</small></b><br>
                        <small style="color: grey">silahkan pilih dalam jam operasional 07.00 - 22.00 WIB</small>
                        <input type="time" name="start_time" class="form-control mb-3" required>
                        <button type="submit" style="width:100%" class="btn btn-success"
                            {{ Auth::user()->cart->count() == 0 ? 'disabled' : '' }}>Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('custom-script')
        <script>
            $(document).ready(function() {
                let countdown = 2 * 60 * 60; // 2 hours in seconds

                function startCountdown() {
                    $('.countdown').each(function() {
                        let itemId = $(this).data('item-id');
                        let savedTime = localStorage.getItem('cartCountdownEndTime_' + itemId);

                        if (!savedTime) {
                            savedTime = Math.floor(Date.now() / 1000) + countdown;
                            localStorage.setItem('cartCountdownEndTime_' + itemId, savedTime);
                        }

                        let countdownInterval = setInterval(() => {
                            let currentTime = Math.floor(Date.now() / 1000);
                            let remainingTime = savedTime - currentTime;

                            if (remainingTime <= 0) {
                                clearInterval(countdownInterval);
                                sendPostRequest(itemId);
                            } else {
                                $(this).text(formatTime(remainingTime));
                            }
                        }, 1000);
                    });
                }

                function formatTime(seconds) {
                    let hours = Math.floor(seconds / 3600);
                    let minutes = Math.floor((seconds % 3600) / 60);
                    let secs = seconds % 60;

                    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                }

                function sendPostRequest(cartItemId) {
                    const url = '{{ route('cart.clear') }}';
                    const data = {
                        id: cartItemId
                    };

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Success:', response);
                            localStorage.removeItem('cartCountdownEndTime_' + cartItemId);
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim data.');
                        }
                    });
                }

                startCountdown();
            });

            function formatTime(seconds) {
                let hours = Math.floor(seconds / 3600);
                let minutes = Math.floor((seconds % 3600) / 60);
                let secs = seconds % 60;

                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }

            document.querySelectorAll('input[type="time"]').forEach(input => {
                input.addEventListener('change', function() {
                    const timeValue = this.value;
                    // Convert time to 24-hour format if needed
                });
            });
        </script>
    @endpush
@endsection
