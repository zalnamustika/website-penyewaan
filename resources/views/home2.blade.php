<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Rumah Penyewaan</title>

    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="css/styles.css" rel="stylesheet" />

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            {{-- <a class="navbar-brand" href="#">Navbar</a> --}}
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mx-auto">
                    @if (!Auth::check())
                        <li class="nav-item">
                            <a type="button" class="nav-link" aria-current="page" data-bs-toggle="modal"
                                data-bs-target="#loginModal">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('daftar') }}">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('logout') }}">Logout</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-3">
            <h1 class="display-4 fw-normal">Rumah Penyewaan Pakaian</h1>
            <p class="fw-normal">Cek Ketersediaan - Reservasi - Bayar - Ambil - Jangan lupa balikin</p>
            @if (!Auth::check())
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#loginModal">Mulai Sewa</button>
            @endif
        </div>
    </div>

    {{-- <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div id="carouselExampleFade" class="carousel slide carousel-fade">
            <div class="carousel-inner">   
                <div class="carousel-item active">
                    <img src="images/banner1.png" class="d-block w-100" alt="...">
                    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light" style="color: black;">
                        <h3 class="display-5 fw-normal">Rumah Penyewaan Pakaian</h3>
                        <p class="fw-normal">Cek Ketersediaan - Reservasi - Bayar - Ambil - Jangan lupa balikin</p>
                        @if (!Auth::check())
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Mulai Sewa!</button>
                        @endif
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/bg.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h4 class="display-4 fw-normal">Rumah Penyewaan Pakaian</h4>
                        <h5>Memberikan Pelayanan Terbaik</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/bg.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Tersedia berbagai jenis</h3>
                        <p>Pakaian Adat Se indonesia</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div> --}}

    <div class="container">
        {{-- Feebacks --}}
        @if (session()->has('registrasi'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('registrasi') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('success_reset_password'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('success_reset_password') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (Auth::check() && Auth::user()->role == 0)
            <div class="alert alert-warning" role="alert">
                Anda telah login sebagai <b>{{ Auth::user()->name }}</b>&nbsp; <a class="btn btn-success"
                    href="{{ route('member.index') }}">Mulai Menyewa</a>
            </div>
        @elseif (Auth::check() && Auth::user()->role != 0)
            <div class="alert alert-warning" role="alert">
                Anda telah login sebagai Admin(<b>{{ Auth::user()->name }}</b>)&nbsp; <a class="btn btn-success"
                    href="{{ route('admin.index') }}">Halaman Admin</a>
            </div>
        @endif
        {{-- End of Feedbacks --}}
        <div class="row mx-3">
            @if (request()->get('search') == null)
                <div class="d-flex w-100 justify-content-start mb-4 mt-2" style="overflow: auto">
                    <div class="btn-group" role="group">
                        <a class="btn {{ request('kategori') == null ? 'btn-secondary' : 'btn-outline-secondary' }}"
                            href="{{ route('home') }}">Semua</a>
                        @foreach ($categories as $cat)
                            <a class="btn {{ request('kategori') == $cat->id ? 'btn-secondary' : 'btn-outline-secondary' }}"
                                href="?kategori={{ $cat->id }}">{{ $cat->nama_kategori }}</a>
                        @endforeach
                    </div>
                </div>
            @else
                <p>Menampilkan hasil pencarian <b>{{ request()->get('search') }}</b>. <a class="link"
                        href="{{ route('home') }}">Kembali tampilkan semua.</a></p>
            @endif
            <form action="">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" width="25%" placeholder="Cari Produk"
                        name="search"
                        {{ request()->get('search') != null ? 'value = ' . request()->get('search') . '' : '' }}>
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit">Cari</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row row-cols-sm-2 row-cols-lg-6 g-2 mb-5 mx-3">
            @foreach ($products as $product)
                <div class="col-6">
                    <div class="card h-100">
                        <img class="card-img-top" src="{{ url('') }}/images/{{ $product->gambar }}"
                            alt="">
                        <div class="card-body">
                            <span class="badge bg-warning">{{ $product->category->nama_kategori }}</span>
                            <h6 class="card-title">{{ $product->nama_produk }}</h6>
                            <p>Tersedia {{ $product->stok }} produk</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ formatRupiah($product->harga1h) }}<span
                                    class="badge bg-light text-dark" style="float: right;">1 Hari</span></li>
                            <li class="list-group-item">{{ formatRupiah($product->harga3h) }}<span
                                    class="badge bg-light text-dark" style="float: right;">3 Hari</span></li>
                            <li class="list-group-item">{{ formatRupiah($product->harga7h) }}<span
                                    class="badge bg-light text-dark" style="float: right;">7 Hari</span></li>
                        </ul>
                        <div class="card-footer">
                            <div class="btn-group" role="group">
                                <a href="{{ route('home.detail', ['id' => $product->id]) }}"
                                    class="btn btn-sm btn-secondary">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <footer class="py-5 bg-dark">
        <div class="container px-4">
            <p class="m-0 text-center text-white">&copy; 2024 Website Rumah Penyewaan Pakaian</p>
        </div>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-block justify-content-around">
                        <div class="text-center my-auto">
                            <h5 class="fw-bold mb-3">Nikmati kemudahan dalam melakukan reservasi</h5>
                            <a href="{{ route('daftar') }}" class="btn btn-success mb-4">Daftar Sekarang</a>
                        </div>
                        <small>Sudah punya akun? silakan login</small>
                        <div>
                            @include('partials.login')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
