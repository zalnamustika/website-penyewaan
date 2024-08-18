@component('mail::message')
    @php
        $image = $message->embed(public_path('/images/logo_penyewaan1.png'));
    @endphp

    <div style="text-align: center;">
        <img src="{{ $image }}" alt="Logo" style="max-width: 200px; margin-bottom: 20px;">
    </div>


    # Pembayaran Berhasil!

    Pembayaran Anda telah terkonfirmasi. Silakan ambil produk pada tanggal dan jam pengambilan yang tertera pada detail
    berikut:

    ---

    ### Detail Reservasi

    **Nama:** {{ $payment->user->name }}
    **No Invoice:** {{ $payment->no_invoice }}
    **Tanggal Pengambilan:** {{ date('d M Y H:i', strtotime($payment->order->first()->starts)) }}

    
        | **Produk** | **Durasi** | **Harga** |
        | ------------- |:-------------:| --------:|
        @foreach ($payment->order as $item)
        | {{ $item->product->nama_produk }} | {{ $item->durasi }} Hari | {{ formatRupiah($item->harga) }} |
        @endforeach
    

    ---

    **Total Pembayaran:** {{ formatRupiah($payment->total) }}

    Terima kasih telah melakukan pembayaran. Kami tunggu kedatangan Anda!

    Thanks,
    {{ config('app.name') }}
@endcomponent
