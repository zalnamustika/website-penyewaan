@extends('admin.main')
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="row mt-4">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h1>{{ $total_penyewaan }}</h1> Reservasi
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small link-dark stretched-link" href="{{ route('penyewaan.index') }}">Kelola Reservasi</a>
                        <div class="small"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h1>{{ $total_user }}</h1> Penyewa
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small link-dark stretched-link" href="{{ route('admin.user') }}">Kelola Penyewa</a>
                        <div class="small"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h1>{{ $total_product }}</h1> Produk
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small link-dark stretched-link" href="{{ route('product.index') }}">Kelola Produk</a>
                        <div class="small"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h1>{{ $total_kategori }}</h1> Kategori
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small link-dark stretched-link" href="{{ route('kategori.index') }}">Kelola Kategori</a>
                        <div class="small"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col col-lg-6 col-sm-12">
                @include('partials.kalender')
            </div>
            <div class="col col-lg-6 col-sm-12">
                <div class="card shadow mb-4 g-2">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Statistik
                    </div>
                    <div class="card-body">
                        <b><h5>5 Penyewa Terbanyak</h5></b>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_user as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="d-flex justify-content-between">{{ $user->name }} <span class="badge bg-secondary">{{ $user->payment_count }} Transaksi</span></td>
                                    <td>{{ $user->telepon }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <b><h5>5 Produk Terfavorit</h5></b>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($top_products as $barang)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="d-flex justify-content-between">{{ $barang->nama_produk }} <span class="badge bg-secondary">{{ $barang->order_count }} Reservasi</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
