@component('mail::message')
# Pembayaran Berhasil!
Pembayaran anda telah terkonfirmasi. Silakan ambil produk pada tanggal dan jam pengambilan yang tertera pada detail

# Detail Reservasi
<b>Nama : {{$payment->user->name}}</b><br>
<b>No Invoice : {{ $payment->no_invoice }}</b> <br>
<b>Tanggal Pengambilan : {{ date('d M Y H:i', strtotime($payment->order->first()->starts)) }}</b>
@component('mail::table')
| produk       | Durasi         | Harga  |
| ------------- |:-------------:| --------:|
@foreach ($payment->order as $item)
| {{$item->product->nama_produk}} | {{ $item->durasi }} Hari | {{ formatRupiah($item->harga) }} |
@endforeach
@endcomponent
<b>Telah Melakukan Pembayaran sebesar {{ formatRupiah($item->harga) }}</b><br><br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
