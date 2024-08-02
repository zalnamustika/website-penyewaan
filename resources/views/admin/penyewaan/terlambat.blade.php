@extends('admin.main')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Produk Terlambat Dikembalikan</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Produk Terlambat Dikembalikan</li>
        </ol>
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Sewa</th>
                                <th>Tanggal Kembali</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->product->nama_produk }}</td>
                                    <td>{{ $order->user->name }} ({{ $order->user->telepon }})</td>
                                    <td>{{ date('D, d M Y H:i', strtotime($order->starts)) }}</td>
                                    <td>{{ date('D, d M Y H:i', strtotime($order->ends)) }}</td>
                                    <td>
                                        <a href="https://api.whatsapp.com/send?phone=+62{{ $order->user->telepon }}&text=Hai {{ $order->user->name }},%0A%0AKami ingin mengingatkan bahwa produk *{{ $order->product->nama_produk }}* yang Anda sewa telah melewati batas waktu pengembalian. Mohon segera melakukan pengembalian."
                                            target="_blank" class="btn btn-sm btn-success">
                                            Kirim Chat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
