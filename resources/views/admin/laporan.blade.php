<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Reservasi dan Pemasukan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h4 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
        }

        .total-border-top {
            border-top: 2px solid #000;
        }
    </style>
</head>

<body>
    <h4>Laporan Reservasi dan Pemasukan</h4><h4>Rumah Penyewaan Pakaian</h4><br>
    <p><strong>Tanggal :</strong> {{ $dariFormatted }}<strong> s/d</strong> {{ $sampaiFormatted }}</p>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Invoice - Nama Penyewa</th>
                <th>Produk</th>
                <th class="text-right">Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKeseluruhan = 0; @endphp
            @foreach ($laporan as $invoiceWithUser => $orders)
                @php
                    // Mengambil nama penyewa dari string invoiceWithUser
                    $parts = explode(' - ', $invoiceWithUser);
                    $invoice = $parts[0];
                    $userName = $parts[1];
                @endphp
                <tr>
                    <td rowspan="{{ $orders->count() }}">{{ $loop->iteration }}</td>
                    <td rowspan="{{ $orders->count() }}">{{ $invoice }} - {{ $userName }}</td>
                    <td>{{ $orders->first()->product->nama_produk }}</td>
                    <td class="text-right">{{ formatRupiah($orders->first()->harga) }}</td>
                </tr>
                @foreach ($orders->slice(1) as $order)
                    <tr>
                        <td>{{ $order->product->nama_produk }}</td>
                        <td class="text-right">{{ formatRupiah($order->harga) }}</td>
                    </tr>
                @endforeach
                @php $totalKeseluruhan += $orders->sum('harga'); @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right"><b>Total</b></td>
                <td class="text-right"><b>{{ formatRupiah($totalKeseluruhan) }}</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
