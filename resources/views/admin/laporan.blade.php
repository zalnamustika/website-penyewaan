<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <div class="row mb-4">
            <h4>Laporan Reservasi dan Pemasukan</h4>
            <small>from <b>{{ date('D, d M Y', strtotime(request('dari'))) }}</b> to <b>{{ date('D, d M Y', strtotime(request('sampai'))) }}</b></small>
        </div>
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Reservasi</th>
                        <th>Produk</th>
                        <th>Penyewa</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('D, d M Y', strtotime($item->tanggal)) }}</td>
                        <td>{{ $item->product->nama_produk }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td><b>{{ formatRupiah($item->harga) }}</b></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"></td>
                        <td><b>Total</b></td>
                        <td ><b>{{ formatRupiah($total) }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        window.print()
    </script>
</body>
</html>
