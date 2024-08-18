@extends('member.main')

@section('container')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col col-sm-12">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }} &nbsp; <a href="{{ route('keranjang.show') }}">cek keranjang</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (request()->get('search') == null)
                    <div class="d-flex w-100 justify-content-start mb-2" style="overflow: auto">
                        <div class="btn-group" role="group">
                            <a class="btn {{ request('kategori') == null ? 'btn-primary' : 'btn-outline-primary' }}"
                                href="{{ route('member.index') }}">Semua</a>
                            @foreach ($kategori as $cat)
                                <a class="btn {{ request('kategori') == $cat->id ? 'btn-primary' : 'btn-outline-primary' }}"
                                    href="?kategori={{ $cat->id }}">{{ $cat->nama_kategori }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p>Menampilkan hasil pencarian <b>{{ request()->get('search') }}</b>. <a class="link"
                            href="{{ route('member.index') }}">Kembali tampilkan semua.</a></p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" width="25%" placeholder="Cari Produk" name="search"
                            {{ request()->get('search') != null ? 'value=' . request()->get('search') . '' : '' }}>
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
                <div class="card shadow h-100">
                    <div class="card-header"><small class="text-muted">klik nama produk untuk melihat detail</small></div>
                    <div class="card-body">
                        <div class="row row-cols-sm-2 row-cols-lg-4 g-2">
                            @foreach ($products as $item)
                                <div class="col">
                                    <div class="card h-100">
                                        <div class="">
                                            <img class="card-img-top" src="{{ url('') }}/images/{{ $item->gambar }}"
                                                style="object-fit: cover;" alt="">
                                        </div>
                                        <div class="card-body">
                                            <span class="badge bg-warning">{{ $item->category->nama_kategori }}</span><br>
                                            <b><a class="link-dark" href="{{ route('home.detail', ['id' => $item->id]) }}"
                                                    style="text-decoration: none;">{{ $item->nama_produk }}</b></a><br>
                                            <p>Tersedia : {{ $item->stok }} produk</p>
                                            <br>
                                            <hr>
                                            @php
                                                $listHargas = \App\Models\Harga::where('product_id', $item->id)->get();
                                            @endphp
                                            @foreach ($listHargas as $vals)
                                                <div class="d-flex w-100 justify-content-between">
                                                    <small class="mb-1"><b>{{ formatRupiah($vals->harga) }}</b></small>
                                                    <small><b>{{ $vals->hari ?? '' }} hari</b></small>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="card-footer">
                                            <div class="dropdown">
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="addtocartdropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Tambah ke Keranjang
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="addtocartdropdown">
                                                    @php
                                                        $hargas = \App\Models\Harga::where(
                                                            'product_id',
                                                            $item->id,
                                                        )->get();
                                                    @endphp
                                                    @foreach ($hargas as $val)
                                                        <li>
                                                            @guest
                                                                <a class="dropdown-item" href="{{ route('login') }}">
                                                                    <i class="fas fa-shopping-cart"></i>
                                                                    {{ formatRupiah($val->harga ?? '') }}
                                                                    <b>/{{ $val->hari ?? '' }} hari</b>
                                                                </a>
                                                            @else
                                                                <form
                                                                    action="{{ route('cart.store', ['id' => $item->id, 'harga_id' => $val->id, 'userId' => Auth::id()]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item">
                                                                        <i class="fas fa-shopping-cart"></i>
                                                                        {{ formatRupiah($val->harga ?? '') }}
                                                                        <b>/{{ $val->hari ?? '' }} hari</b>
                                                                    </button>
                                                                </form>
                                                            @endguest
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
