@extends('member.main')

@section('container')
    <style>
        /* Mengatur ulang tata letak menjadi grid yang lebih profesional */
        .product-card {
            border: 1px solid #e9ecef;
            border-radius: 0.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            position: relative;
            background-color: #fff;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .product-card img {
            object-fit: cover;
            border-bottom: 1px solid #e9ecef;
        }

        .product-card .badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #e0b126;
            opacity: 90%;
            color: #ffffff;
            padding: 0.5rem;
            font-size: 0.85rem;
            border-radius: 0.25rem;
        }

        .product-card .card-body {
            padding: 1rem;
        }

        .product-card .card-body a {
            font-size: 1.1rem;
            color: #343a40;
            text-decoration: none;
            font-weight: bold;
        }

        .product-card .card-body a:hover {
            color: #007bff;
        }

        .product-card .card-body p {
            margin-top: 0.5rem;
            color: #6c757d;
        }

        .product-card .price-item {
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .product-card .price-item:last-child {
            border-bottom: none;
        }

        .product-card .price-item .price {
            font-size: 1.2rem;
            color: #28a745;
            font-weight: bold;
        }

        .product-card .price-item .duration {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .product-card .card-footer {
            padding: 0.75rem;
            background-color: #f8f9fa;
            text-align: center;
            border-top: none;
        }

        .product-card .card-footer .btn-success {
            background-color: #007bff;
            border: none;
            color: #fff;
            width: 100%;
            padding: 0.75rem;
            transition: background-color 0.2s ease-in-out;
        }

        .product-card .card-footer .btn-success:hover {
            background-color: #0056b3;
        }

        .product-card img.card-img-top {
            max-height: 500px;
            width: 100%;
            object-fit: cover;
        }

        .search-bar {
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .search-bar form {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-bar button {
            background-color: #007bff;
            border: none;
            padding: 0.2rem;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
            border-radius: 0 0.25rem 0.25rem 0;
            font-size: 0.9rem;
        }

        .search-bar input {
            width: 100%;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem 0 0 0.25rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-bottom: 2rem;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col col-sm-12">
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }} &nbsp;
                        <a href="{{ route('keranjang.show') }}" class="alert-link">Cek Keranjang</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (request()->get('search') == null)
                    <div class="btn-group" role="group">
                        <a class="btn {{ request('kategori') == null ? 'custom-btn' : 'custom-btn-off' }}"
                            href="{{ route('member.index') }}">Semua</a>
                        @foreach ($kategori as $cat)
                            <a class="btn {{ request('kategori') == $cat->id ? 'custom-btn' : 'custom-btn-off' }}"
                                href="?kategori={{ $cat->id }}">{{ $cat->nama_kategori }}</a>
                        @endforeach
                    </div>
                @else
                    <p>Menampilkan hasil pencarian <b>{{ request()->get('search') }}</b>.
                        <a class="link" href="{{ route('member.index') }}">Kembali tampilkan semua.</a>
                    </p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="search-bar">
                    <form action="">
                        <input type="text" class="form-control" placeholder="Cari Produk" name="search"
                            value="{{ request()->get('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </form>
                </div>
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach ($products as $item)
                        <div class="col">
                            <div class="product-card">
                                <div class="badge">{{ $item->category->nama_kategori }}</div>
                                <div class="img-container">
                                    <img class="card-img-top" src="{{ url('') }}/images/{{ $item->gambar }}"
                                        alt="{{ $item->nama_produk }}">
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('home.detail', ['id' => $item->id]) }}">{{ $item->nama_produk }}</a>
                                    <p>Tersedia: {{ $item->stok }} produk</p>
                                    <div class="price-section">
                                        @php
                                            $listHargas = \App\Models\Harga::where('product_id', $item->id)->get();
                                        @endphp
                                        @foreach ($listHargas as $vals)
                                            <div class="d-flex w-100 justify-content-between">
                                                <span class="price">{{ formatRupiah($vals->harga) }}</span>
                                                <span class="duration">{{ $vals->hari ?? '' }} hari</span>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-success" data-bs-toggle="dropdown">
                                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        @php
                                            $hargas = \App\Models\Harga::where('product_id', $item->id)->get();
                                        @endphp
                                        @foreach ($hargas as $val)
                                            <li>
                                                @guest
                                                    <a class="dropdown-item" href="{{ route('login') }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                        {{ formatRupiah($val->harga ?? '') }}
                                                        /{{ $val->hari ?? '' }} hari
                                                    </a>
                                                @else
                                                    <form
                                                        action="{{ route('cart.store', ['id' => $item->id, 'harga_id' => $val->id, 'userId' => Auth::id()]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            {{ formatRupiah($val->harga ?? '') }}
                                                            /{{ $val->hari ?? '' }} hari
                                                        </button>
                                                    </form>
                                                @endguest
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
