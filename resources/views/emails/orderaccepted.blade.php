@component('mail::message')
@php
    $image = $message->embed(public_path('/images/logo_penyewaan1.png'));
@endphp

<!-- Logo -->
<div style="text-align: center;">
    <img src="{{ $image }}" alt="Logo" style="max-width: 200px; margin-bottom: 20px;">
</div>

<!-- Pesan Utama -->
<p style="font-size: 16px; line-height: 1.5; font-weight: bold;">
    Reservasi Anda telah Disetujui!
</p>

<p style="font-size: 14px; line-height: 1.5;">
    Reservasi Anda telah disetujui oleh Admin. Langkah selanjutnya adalah melakukan pembayaran melalui transfer melalui ATM ke rekening:
</p>

<!-- Informasi Pembayaran -->
<p style="font-size: 14px; line-height: 1.5; font-weight: bold;">
    BRI 0058 0102 8552 539 a/n Siti Aisyah Yus<br>
    Jumlah Pembayaran: {{ formatRupiah($payment->total) }}
</p>
<p style="font-size: 14px; line-height: 1.5;">
    Setelah pembayaran, silakan upload bukti bayar pada website.
</p>

<!-- Detail Reservasi -->
<p style="font-size: 16px; line-height: 1.5; font-weight: bold;">
    Detail Reservasi
</p>
<p style="font-size: 14px; line-height: 1.5;">
    Nama: {{ $payment->user->name }}<br>
    No Invoice: {{ $payment->no_invoice }}<br>
    Tanggal Pengambilan: {{ date('d M Y H:i', strtotime($payment->order->first()->starts)) }}
</p>

<!-- Tabel Produk -->
<table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 20px;">
    <thead>
        <tr>
            <th style="border-bottom: 1px solid #ddd; text-align: left; padding: 8px;">Produk</th>
            <th style="border-bottom: 1px solid #ddd; text-align: center; padding: 8px;">Durasi</th>
            <th style="border-bottom: 1px solid #ddd; text-align: right; padding: 8px;">Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payment->order as $item)
        <tr>
            <td style="border-bottom: 1px solid #ddd; padding: 8px;">{{ $item->product->nama_produk }}</td>
            <td style="border-bottom: 1px solid #ddd; text-align: center; padding: 8px;">{{ $item->durasi }} Hari</td>
            <td style="border-bottom: 1px solid #ddd; text-align: right; padding: 8px;">{{ formatRupiah($item->harga) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<p style="font-size: 14px; margin-top: 20px;">
    Thanks,<br>
    {{ config('app.name') }}
</p>
@endcomponent
