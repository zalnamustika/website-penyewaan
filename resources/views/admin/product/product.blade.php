@extends('admin.main')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manajemen Produk</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Manajemen Produk</li>
        </ol>
        <div class="row">
            @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            <div class="col-lg">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        Produk
                    </div>
                    <div class="card-body">
                        <a type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#tambahProduk">Tambah Produk</a>
                        <div class="dropdown" style="float: right;">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                              Filter Kategori
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="{{ route('product.index') }}">Semua</a></li>
                                @foreach ($categories as $cat)
                                <li><a class="dropdown-item" href="{{ route('product.index',['id'=>$cat->id]) }}">{{ $cat->nama_kategori }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <form action="">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" width="25%" placeholder="Cari Produk" name="search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow:scroll;">
                        <div class="row row-cols-sm-2 row-cols-lg-6 g-2">
                            @foreach ($products as $product)
                            <div class="col-6">
                                <div class="card h-100">
                                    <img class="card-img-top" src="{{ url('') }}/images/{{ $product->gambar }}" alt="">
                                    <div class="card-body">
                                        <span class="badge bg-warning">{{ $product->category->nama_kategori }}</span>
                                        <h6 class="card-title">{{ $product->nama_produk }}</h6>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">{{ formatRupiah($product->harga1h) }}<span class="badge bg-light text-dark" style="float: right;">1 Hari</span></li>
                                        <li class="list-group-item">{{ formatRupiah($product->harga3h) }}<span class="badge bg-light text-dark" style="float: right;">3 Hari</span></li>
                                        <li class="list-group-item">{{ formatRupiah($product->harga7h) }}<span class="badge bg-light text-dark" style="float: right;">7 Hari</span></li>
                                    </ul>
                                    <div class="card-footer">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('product.edit',['id' => $product->id]) }}" class="btn btn-sm btn-primary">Edit</a>
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
</main>

<!-- Modal -->
<div class="modal fade" id="tambahProduk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Produk" required>
                </div>
                <div class="mb-3">
                    <select class="form-select" name="kategori" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi singkat"></textarea>
                </div>
                <div class="mb-3">
                    <div class="row">
                        <span class="form-text mb-2">Harga ditulis angka saja, tidak perlu tanda titik</span>
                        <div class="col col-4"><input type="number" name="harga1h" class="form-control" placeholder="Harga 1Hari" required></div>
                        <div class="col col-4"><input type="number" name="harga3h" class="form-control" placeholder="Harga 3Hari" required></div>
                        <div class="col col-4"><input type="number" name="harga7h" class="form-control" placeholder="Harga 7Hari" required></div>
                    </div>
                </div>
                <div class="mb-3">
                    <span class="form-text">Upload Gambar Produk</span>
                    <input type="file" name="gambar" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
@endsection
