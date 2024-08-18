@component('mail::message')
    @php
        $image = $message->embed(public_path('/images/logo_penyewaan1.png'));
    @endphp

    <div style="text-align: center;">
        <img src="{{ $image }}" alt="Logo" style="max-width: 200px; margin-bottom: 20px;">
    </div>

    **Reservasi Anda telah Disetujui!**
    Reservasi anda telah disetujui oleh Admin.
    langkah selanjutnya adalah melakukan pembayaran melalui transfer melalui ATM ke rekening :

    **BNI 12345678 a/n Erawati**
    Jumlah Pembayaran : {{ formatRupiah($payment->total) }}

    setelah pembayaran, silakan upload bukti bayar pada website

    **Detail Reservasi**
    Nama : {{ $payment->user->name }}
    No Invoice : {{ $payment->no_invoice }}
    Tanggal Pengambilan : {{ date('d M Y H:i', strtotime($payment->order->first()->starts)) }}
  
        | **Produk** | **Durasi** | **Harga** |
        |:---------- |:----------:| ---------:|
        @foreach ($payment->order as $item)
            | {{ $item->product->nama_produk }} | {{ $item->durasi }} Hari | {{ formatRupiah($item->harga) }} |
        @endforeach
   

    Thanks,
    {{ config('app.name') }}
@endcomponent
